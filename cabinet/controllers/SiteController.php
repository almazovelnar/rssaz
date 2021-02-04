<?php

namespace cabinet\controllers;

use Yii;
use yii\web\Controller;
use core\forms\ChartForm;
use core\entities\Customer\Customer;
use cabinet\models\StatisticsSearch;
use core\services\statistics\TrafficCalculator;
use core\repositories\interfaces\WebsiteRepositoryInterface;

/**
 * Class SiteController
 * @package cabinet\controllers
 */
class SiteController extends Controller
{
    private WebsiteRepositoryInterface $websiteRepository;
    private TrafficCalculator $trafficCalculator;

    public function __construct(
        $id,
        $module,
        WebsiteRepositoryInterface $websiteRepository,
        TrafficCalculator $trafficCalculator,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);

        $this->websiteRepository = $websiteRepository;
        $this->trafficCalculator = $trafficCalculator;
    }

    public function actions()
    {
        return [
            'error' => ['class' => 'yii\web\ErrorAction'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\AjaxFilter',
                'only' => ['chart']
            ],
        ];
    }

    public function actionIndex()
    {
        $customer = Yii::$app->user->identity;
        $searchModel = new StatisticsSearch($this->websiteRepository, $this->trafficCalculator, $customer->id);
        $searchModel->search(Yii::$app->request->get());

        $inTraffic = $searchModel->getInTraffic();
        $outTraffic = $searchModel->getOutTraffic();

        return $this->render('index', [
            'customer' => $customer,
            'inTraffic' => [
                'data' => $inTraffic, 'ctr' => $this->trafficCalculator->calculateCtr($inTraffic)
            ],
            'outTraffic' => [
                'data' => $outTraffic, 'ctr' => $this->trafficCalculator->calculateCtr($outTraffic)
            ],
            'searchModel' => $searchModel,
        ]);
    }

    public function actionChart()
    {
        $request = Yii::$app->request;
        $form = new ChartForm();
        $form->load($request->get());
        if ($form->validate())
            return $this->asJson(['status' => $form->rememberLegends(Yii::$app->response, $request)]);
        return $this->asJson(['status' => false]);
    }
}
