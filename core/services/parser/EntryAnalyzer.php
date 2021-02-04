<?php

namespace core\services\parser;

use Yii;
use SimplePie_Item;
use RuntimeException;
use core\valueObjects\ComputedImageHash;
use core\exceptions\RssParseItemException;
use Jenssegers\ImageHash\{ImageHash, Hash};
use core\entities\{Image, Customer\Website\Post};
use Intervention\Image\Exception\NotReadableException;
use core\repositories\interfaces\{ImageRepositoryInterface, PostRepositoryInterface};

/**
 * Class EntryAnalyzer
 *
 * @package core\services\parser
 */
class EntryAnalyzer
{
    private ?Post $post = null;
    private ?Image $image = null;
    private ImageHash $hasher;
    private PostRepositoryInterface $postRepository;
    private ImageRepositoryInterface $imageRepository;
    private DuplicatedPostInfoDto $duplicatedPostInfoDto;

    private string $computedHash;
    private string $analyzedImageUrl;
    private bool $hasDuplicatedImage = false;
    private bool $hasDuplicatedPost = false;

    public function __construct(
        ImageHash $hasher,
        ImageRepositoryInterface $imageRepository,
        PostRepositoryInterface $postRepository
    )
    {
        $this->hasher = $hasher;
        $this->imageRepository = $imageRepository;
        $this->postRepository = $postRepository;
    }

    /**
     * @param SimplePie_Item $item
     * @param ParserDto $parserDto
     * @throws RssParseItemException
     */
    public function analyze(SimplePie_Item $item, ParserDto $parserDto): void
    {
        $enclosure = $item->get_enclosure();
        if (!isset($enclosure->link))
            throw new RssParseItemException('enclosure', $item, "Can't get the link from enclosure.");
        $this->analyzedImageUrl = $enclosure->link;
        $this->post = $this->postRepository->getByGuid($item->get_id());

        try {
            $hash = $this->hasher->hash($this->analyzedImageUrl); // getting the Hash object.
            $this->computedHash = $hash->toHex(); // storing HEX version of Hash.
            // Analyzing remote image
            if (($this->image = $this->imageRepository->getByHash(['hash' => $this->computedHash])) === null) {
                if (($imageByChunkOfHash = $this->imageRepository->getByHash(['chunk' => new ComputedImageHash($this->computedHash)])) !== null) {
                    if ($this->hasher->distance($hash, Hash::fromHex($imageByChunkOfHash->getHash())) <= 15)
                        $this->image = $imageByChunkOfHash;
                }
            }

            if ($this->image instanceof Image) $this->hasDuplicatedImage = true;
        } catch (NotReadableException $e) {
            return; // throw new RssParseItemException('enclosure', $item, $e->getMessage());
        }

        if ($this->post !== null) return; // No need to continue. Existing post detected. Updating...

        // check for title duplicate
        $title = $item->get_title();
        foreach (array_merge($parserDto->getParsedPosts()->toArray(), $parserDto->getLastPosts()) as $post) {
            $similarity = $this->getTitleSimilarity($this->getFilteredTitle($title), $this->getFilteredTitle($post->getTitle()));
            if ($similarity >= 75) {
                $this->hasDuplicatedPost = true;
                $this->duplicatedPostInfoDto = new DuplicatedPostInfoDto($post->getId(), Yii::t('post', 'duplicated_title'), $similarity);
                return;
            }
        }

        // check for image duplicate
        if (($postsImages = $parserDto->getPostsImages()) === null) return;
        foreach ($postsImages as $postImage) {
            if (($similarity = $this->hasher->distance($hash, Hash::fromHex($postImage->postImage->hash))) <= 15) {
                $this->hasDuplicatedPost = true;
                $this->duplicatedPostInfoDto = new DuplicatedPostInfoDto(
                    $postImage->getId(),
                    Yii::t('post', 'duplicated_image'),
                    (100 - $similarity)
                );
                return;
            }
        }
    }

    private function getFilteredTitle(string $title): string
    {
        return str_replace(['ı'], ['i'], mb_strtolower(trim($title), 'UTF-8'));
    }

    private function getTitleSimilarity(string $firstTitle, string $secondTitle): float
    {
        similar_text($firstTitle, $secondTitle, $similarity);
        return $similarity;
    }

    public function hasDuplicatedImage(): bool
    {
        return $this->hasDuplicatedImage;
    }

    public function hasDuplicatedPost(): bool
    {
        return $this->hasDuplicatedPost;
    }

    public function rememberEntry(array $downloadedImage): Image
    {
        return $this->imageRepository->save(Image::create(
            $this->computedHash,
            $downloadedImage['basename'],
            $downloadedImage['color']
        ));
    }

    public function getAnalyzedImageUrl(): string
    {
        return $this->analyzedImageUrl;
    }

    public function getDuplicatedPostInfoDto(): DuplicatedPostInfoDto
    {
        return $this->duplicatedPostInfoDto;
    }

    public function getExistingImage(): array
    {
        if (!$this->hasDuplicatedImage)
            throw new RuntimeException("Entry is not duplicated. You can't get data !");

        return [
            'basename' => $this->image->getFilename(),
            'color'    => $this->image->getColor(),
        ];
    }

    public function getExistingPost(): ?Post
    {
        return $this->post;
    }

    public function resetAnalysis(): void
    {
        $this->hasDuplicatedImage = false;
        $this->hasDuplicatedPost = false;
    }
}
