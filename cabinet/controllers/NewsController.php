<?php

namespace cabinet\controllers;

use core\entities\Customer\Website\Post;
use Yii;
use Exception;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use cabinet\models\PostSearch;
use core\services\parser\ParseService;
use core\useCases\cabinet\PostService;
use core\exceptions\NotFoundException;
use core\repositories\interfaces\{RssRepositoryInterface, PostRepositoryInterface, WebsiteRepositoryInterface};

/**
 * Class NewsController
 * @package cabinet\controllers
 */
class NewsController extends Controller
{
    private PostRepositoryInterface $postRepository;
    private WebsiteRepositoryInterface $websiteRepository;
    private RssRepositoryInterface $rssRepository;
    private PostService $postService;
    private ParseService $parseService;

    public function __construct(
        string $id,
        $module,
        PostRepositoryInterface $postRepository,
        WebsiteRepositoryInterface $websiteRepository,
        RssRepositoryInterface $rssRepository,
        PostService $postService,
        ParseService $parseService,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);

        $this->postRepository = $postRepository;
        $this->websiteRepository = $websiteRepository;
        $this->rssRepository = $rssRepository;
        $this->postService = $postService;
        $this->parseService = $parseService;
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'update-rss' => ['POST'],
                ],
            ],
            [
                'class' => 'yii\filters\AjaxFilter',
                'only' => ['prioritize']
            ],
        ];
    }

    public function actionIndex()
    {
        $request = Yii::$app->request;
        $searchModel = new PostSearch($this->websiteRepository, $request->get('website_id'));
        $dataProvider = $searchModel->search($request->get());
        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPrioritize(int $id)
    {
        try {
            $this->postService->prioritize($this->postRepository->get($id, ['status' => Post::STATUS_ACTIVE]));
            return $this->asJson(['status' => true, 'message' => 'Post prioritized']);
        } catch (NotFoundException | Exception $e) {
            return $this->asJson(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function actionDelete($id)
    {
        try {
            $this->postRepository->remove($this->postRepository->get($id, ['status' => Post::STATUS_ACTIVE]));
        } catch (NotFoundException | Exception $e) {
            Yii::$app->session->setFlash('error', 'Xəbər silinə bilmədi!');
            Yii::$app->errorHandler->logException($e);
        }

        return $this->redirect(Url::previous());
    }

    public function actionUpdateRss(int $id, string $language)
    {
        try {
            return $this->asJson(['success' => $this->parseService->handle($this->rssRepository->getByWebsite($id, $language))]);
        } catch (Exception $e) {
            Yii::$app->errorHandler->logException($e);
            return $this->asJson(['success' => false, 'error' => 'Some error occurred during parsing rss feed.']);
        }
    }
}
