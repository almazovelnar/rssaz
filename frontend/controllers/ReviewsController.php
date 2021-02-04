<?php

namespace frontend\controllers;

use core\repositories\interfaces\WebsiteRepositoryInterface;
use yii\web\Controller;
use core\repositories\CustomerReviewRepository;

/**
 * Class ReviewController
 * @package frontend\controllers
 */
class ReviewsController extends Controller
{
    private CustomerReviewRepository $customerReviewRepository;
    private WebsiteRepositoryInterface $websiteRepository;

    public function __construct(
        string $id,
        $module,
        WebsiteRepositoryInterface $websiteRepository,
        CustomerReviewRepository $customerReviewRepository,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);

        $this->customerReviewRepository = $customerReviewRepository;
        $this->websiteRepository = $websiteRepository;
    }

    public function actionIndex()
    {
        $reviews = [];

        foreach ($this->customerReviewRepository->all() as $key => $review) {
            $reviews[$key] = $review;
            $reviews[$key]['website'] = $this->websiteRepository->get($review['website_id']);
        }

        return $this->render('index', compact('reviews'));
    }
}