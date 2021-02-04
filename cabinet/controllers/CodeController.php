<?php

namespace cabinet\controllers;

use Yii;
use Exception;
use RuntimeException;
use yii\caching\CacheInterface;
use yii\web\Controller;
use core\helpers\CommonHelper;
use core\exceptions\NotFoundException;
use core\useCases\cabinet\CodeService;
use core\forms\cabinet\Website\CodeForm;
use core\forms\cabinet\Website\SelectWebsiteForm;
use core\repositories\interfaces\{PostRepositoryInterface, WebsiteRepositoryInterface, CodeRepositoryInterface};

/**
 * Class CodeController
 * @package cabinet\controllers
 */
class CodeController extends Controller
{
    private WebsiteRepositoryInterface $websiteRepository;
    private PostRepositoryInterface $postRepository;
    private CodeRepositoryInterface $codeRepository;
    private CacheInterface $cache;
    private CodeService $codeService;

    public function __construct(
        string $id,
        $module,
        WebsiteRepositoryInterface $websiteRepository,
        PostRepositoryInterface $postRepository,
        CodeRepositoryInterface $codeRepository,
        CacheInterface $cache,
        CodeService $codeService,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);

        $this->websiteRepository = $websiteRepository;
        $this->postRepository = $postRepository;
        $this->codeRepository = $codeRepository;
        $this->cache = $cache;
        $this->codeService = $codeService;
    }

    public function actionSelectWebsite()
    {
        $form = new SelectWebsiteForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate())
            return $this->redirect(['preview', 'id' => (int) $form->website]);

        return $this->render('select-website', [
            'websites' => CommonHelper::makeDropdown($this->websiteRepository->getByCustomer(Yii::$app->user->id)),
            'model' => $form,
        ]);
    }

    public function actionPreview($id)
    {
        try {
            $website = $this->websiteRepository->get($id);
        } catch (NotFoundException $e) {
            return $this->redirect(['website/index']);
        }

        $code = $website->code;
        $form = new CodeForm($code);
        $form->setWebsite($website->id);
        $hasCode = ($code !== null);

        return $this->render('preview', [
            'model' => $form,
            'hasCode' => $hasCode,
            'websites' => CommonHelper::makeDropdown($this->websiteRepository->getByCustomer(Yii::$app->user->id)),
            'blocks' => $this->renderPartial('partials/slider-item', [
                'data' => $this->cache->getOrSet('preview-posts', fn () => $this->postRepository->all($form->getLimit(), ['exclude' => $website->id], 'rand()'), 86400)
            ]),
            'codeTypes' => $form->getCodeTypes($website->getHash(), $hasCode),
        ]);
    }

    /**
     * @return string|array
     */
    public function actionGenerateCode()
    {
        $form = new CodeForm();

        if (!$form->load(Yii::$app->request->post()) || !$form->validate()) return false;

        try {
            $code = $this->codeService->save($form);
            return $this->asJson([
                'status' => true,
                'message' => Yii::t('code', 'sync-successful'),
                'codeTypes' => $form->getCodeTypes($code->website->getHash(), true),
            ]);
        } catch (RuntimeException | Exception $e) {
            Yii::$app->errorHandler->logException($e);
            return $this->asJson(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * @return string
     */
    public function actionAppendBlock()
    {
        return $this->renderPartial('partials/slider-item', [
            'data' => $this->postRepository->all(1, [], 'rand()')
        ]);
    }
}