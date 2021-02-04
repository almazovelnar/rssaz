<?php

namespace core\useCases\manager;

use core\entities\Customer\Review\Review;
use core\forms\manager\CustomerReview\Form;
use core\repositories\CustomerReviewRepository;

/**
 * Class CustomerReviewService
 * @package core\useCases\manager
 */
class CustomerReviewService
{
    private CustomerReviewRepository $customerReviewRepository;

    public function __construct(CustomerReviewRepository $customerReviewRepository)
    {
        $this->customerReviewRepository = $customerReviewRepository;
    }

    public function create(Form $form)
    {
        $review = Review::create($form->websiteId, $form->status);

        foreach ($form->translations as $translation) {
            $review->setVersion(
                $translation->language,
                $translation->review
            );
        }

        $this->customerReviewRepository->save($review);
    }

    public function update(Review $review, Form $form)
    {
        $review->edit($form->websiteId, $form->status);

        foreach ($form->translations as $translation) {
            $review->setVersion(
                $translation->language,
                $translation->review
            );
        }

        $this->customerReviewRepository->save($review);
    }

    public function delete(int $id)
    {
        $this->customerReviewRepository->remove(
            $this->customerReviewRepository->get($id)
        );
    }

}