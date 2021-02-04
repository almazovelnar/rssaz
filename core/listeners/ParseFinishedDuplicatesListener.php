<?php

namespace core\listeners;

use core\events\ParseFinished;
use core\services\parser\DuplicatedPostInfoDto;
use core\entities\Customer\Website\PostDuplicateReason;
use core\repositories\interfaces\PostDuplicateReasonRepositoryInterface;

/**
 * Class ParseFinishedDuplicatesListener
 * @package core\listeners
 */
class ParseFinishedDuplicatesListener
{
    private PostDuplicateReasonRepositoryInterface $postDuplicateReasonRepository;

    public function __construct(
        PostDuplicateReasonRepositoryInterface $postDuplicateReasonRepository
    )
    {
        $this->postDuplicateReasonRepository = $postDuplicateReasonRepository;
    }

    /**
     * @param ParseFinished $event
     */
    public function handle(ParseFinished $event): void
    {
        foreach ($event->getParserDto()->getDuplicatesInfos() as $duplicateInfo) {
            /** @var DuplicatedPostInfoDto $duplicateInfo; */
            $this->postDuplicateReasonRepository->save(PostDuplicateReason::create(
                $duplicateInfo->getOriginalId(),
                $duplicateInfo->getDuplicateId(),
                $duplicateInfo->getReason(),
                $duplicateInfo->getSimilarity()
            ));
        }
    }
}