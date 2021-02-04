<?php

namespace core\services\parser;

use Yii;
use SimplePie;
use Exception;
use SimplePie_Item;
use core\helpers\StringHelper;
use core\factories\PostFactory;
use core\validators\DOMValidator;
use core\dispatchers\EventDispatcher;
use core\repositories\CategoryRepository;
use core\entities\Customer\Website\{Rss, Post};
use core\components\Storage\directories\PostDirectory;
use core\exceptions\{RssParseItemException, RssParseException, NotFoundException, PostAlreadyExistsException};
use core\repositories\interfaces\{PostRepositoryInterface, ImageRepositoryInterface, PostDuplicateReasonRepositoryInterface};

/**
 * Class RssParser
 * @package core\services\parser
 */
class RssParser
{
    private const ITEMS_LIMIT = 5000;

    private SimplePie $feed;
    private DOMValidator $domValidator;
    private EventDispatcher $eventDispatcher;
    private EntryAnalyzer $entryAnalyzer;
    private CategoryRepository $categoryRepository;
    private PostRepositoryInterface $postRepository;
    private ImageRepositoryInterface $imageRepository;
    private PostDuplicateReasonRepositoryInterface $duplicateReasonRepository;
    private PostDirectory $postDirectory;
    private ParserDto $parserDto;

    private int $itemIncrementValue;

    public function __construct
    (
        PostDuplicateReasonRepositoryInterface $duplicateReasonRepository,
        PostRepositoryInterface $postRepository,
        ImageRepositoryInterface $imageRepository,
        CategoryRepository $categoryRepository,
        EventDispatcher $eventDispatcher,
        PostDirectory $postDirectory,
        EntryAnalyzer $entryAnalyzer,
        DOMValidator $domValidator,
        ParserDto $parserDto
    )
    {
        $this->feed = new SimplePie();
        $this->domValidator = $domValidator;
        $this->eventDispatcher = $eventDispatcher;
        $this->entryAnalyzer = $entryAnalyzer;
        $this->categoryRepository = $categoryRepository;
        $this->postRepository = $postRepository;
        $this->imageRepository = $imageRepository;
        $this->postDirectory = $postDirectory;
        $this->parserDto = $parserDto;
        $this->parserDto->setLastPosts($this->postRepository->getPosts(['wp.id', 'wp.title'], ['lastHours' => 3]));

        $this->itemIncrementValue = $this->postRepository->getLastInsertedId();

        $this->feed->enable_cache(YII_ENV == YII_ENV_PROD);
        $this->feed->set_cache_location(Yii::getAlias('@console/runtime/cache'));
        $this->duplicateReasonRepository = $duplicateReasonRepository;
    }

    /**
     * @param Rss $rss
     * @return ParserDto
     * @throws RssParseException|Exception
     */
    public function parse(Rss $rss): ParserDto
    {
        $parseStartTime = microtime(true);

        if (!$this->domValidator->validateFeeds($rss->getFeedAddress(), $rss->getFeedLanguage())) {
            throw new RssParseException(
                'Errors occurred during rss feed validation !',
                $this->domValidator->getErrors(),
                $this->domValidator->getXmlContent()
            );
        }

        $this->initFeed($rss->getFeedAddress());
        $this->parserDto
            ->prepareStacks()
            ->setPostsImages($this->postRepository->getPosts(
                ['wp.id', 'wp.image'],
                ['lastHours' => 1, 'excludedDomains' => array_wrap($rss->getWebsiteId()), 'language' => $rss->getFeedLanguage()]
            ));

        foreach ($this->feed->get_items(0, self::ITEMS_LIMIT) as $item) {
            try {
                $this->entryAnalyzer->resetAnalysis();
                $post = $this->parseItem($rss, $item);
                $this->parserDto->appendParsed($post);
                $this->parserDto->appendRssParsed($post);
            } catch (RssParseItemException $e) {
                $this->parserDto->appendParseItemError($e);
                continue;
            } catch (PostAlreadyExistsException $e) {
                $this->parserDto->appendExisting($e->getPost());
                continue;
            } catch (Exception $e) {
                throw $e;
            }
        }

        return $this->parserDto
            ->setParsedRssEntity($rss)
            ->setParsedXmlContent($this->domValidator->getXmlContent())
            ->setElapsedTime(round(microtime(true) - $parseStartTime, 3));
    }

    /**
     * @param Rss $rss
     * @param SimplePie_Item $item
     * @return Post
     * @throws PostAlreadyExistsException|RssParseItemException|Exception
     */
    private function parseItem(Rss $rss, SimplePie_Item $item): Post
    {
        if (!$item->get_category())
            throw new RssParseItemException('category', $item, 'Cannot get category from item.');

        try {
            $category = $this->categoryRepository->getByTitle($rss->getFeedLanguage(), $item->get_category()->term);
        } catch (NotFoundException $e) {
            throw new RssParseItemException('category', $item, $e->getMessage());
        }

        $this->entryAnalyzer->analyze($item, $this->parserDto);

        if (!$this->entryAnalyzer->hasDuplicatedImage()) {
            try {
                $image = $this->postDirectory->saveFromUrl($this->entryAnalyzer->getAnalyzedImageUrl());
                $this->entryAnalyzer->rememberEntry($image);
            } catch (Exception $e) {
                throw new RssParseItemException('enclosure', $item, $e->getMessage());
            }
        } else {
            $image = $this->entryAnalyzer->getExistingImage();
        }

        $title = StringHelper::filter($item->get_title());
        // $image = ['basename' => 'image.jpg', 'color' => json_encode(['test' => true])];
        if (($post = $this->entryAnalyzer->getExistingPost()) !== null) {
            $post->edit(
                $category->getId(),
                $title,
                $item->get_link(),
                stripslashes($item->get_description()),
                $image['basename'],
                $image['color'],
            );

            $this->postRepository->update($post->getId(), $post->getDirtyAttributes());
            throw new PostAlreadyExistsException('Post already exists', $post);
        }

        $this->itemIncrementValue++; // incrementing ID.
        $status = Post::STATUS_ACTIVE; // setting default status for post.
        if ($this->entryAnalyzer->hasDuplicatedPost()) {
            $this->parserDto->appendDuplicatesInfo($this->entryAnalyzer->getDuplicatedPostInfoDto()->setDuplicateId($this->itemIncrementValue));
            $status = Post::STATUS_DUPLICATED;
        }

        return PostFactory::create(
            $this->itemIncrementValue,
            $rss->getWebsiteId(),
            $rss->getId(),
            $category->getId(),
            $rss->getFeedLanguage(),
            $item->get_id(),
            $title,
            $item->get_link(),
            stripslashes($item->get_description()),
            $image['basename'],
            $image['color'],
            $status,
            $item->get_date('Y-m-d H:i:s')
        );
    }

    /**
     * @param $url
     * @throws RssParseException
     */
    private function initFeed(string $url): void
    {
        $this->feed->set_feed_url($url);
        if (!$this->feed->init())
            throw new RssParseException("Can't initialize feed! Message: " . $this->feed->error());
        // $this->feed->handle_content_type();
    }
}
