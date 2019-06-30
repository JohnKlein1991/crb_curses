<?php


namespace app\modules\curses\controllers;

use yii\db\Exception;
use yii\web\Controller;
use yii\httpclient\Client;
use yii\data\ArrayDataProvider;
use app\modules\curses\models\CurrentCurses;
use app\modules\curses\models\HighchartsForm;
use yii\helpers\ArrayHelper;
use Yii;
use yii\helpers\Json;

class DefaultController extends Controller
{
    public function actionIndex($date = null)
    {
        $model = new CurrentCurses();
        if($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $date = $model->date;
            } else {
                Yii::$app->session->setFlash(
                    'danger', 'Некорректная дата'
                );
            }
        }
        $date = $date ?? date('d/m/Y');
        try {
            $cursesInfo = $this->getDataFromCB($date);
        } catch (Exception $e){
            $cursesInfo = false;
        }
        if(!$cursesInfo){
            Yii::$app->session->setFlash(
                'danger', 'Не удалось получить данные'
            );
            $data = [];
        } else {
            $data = $this->prepareDataForView($cursesInfo);
        }
        return $this->render('index', [
            'data' => $data,
            'model' => $model,
            'date' => $date
        ]);
    }
    public function actionHighcharts()
    {
        try {
            $data = $this->getListOfCurrencies();
        } catch (Exception $e) {
            $data = false;
        }
        if(!$data){
            return $this->render('wrongResponse');
        }
        $listOfCurrencies = $data['curList'];
        $options = $data['options'];
        $model = new HighchartsForm();
        if($model->load(Yii::$app->request->post())){
            if($model->validate()){
                try {
                    $highchartsData = $this->getCurrenciesDynamic(
                        $model->dateFrom,
                        $model->dateTo,
                        $model->currency
                    );
                } catch(Exception $e) {
                        $highchartsData = [];
                }
                return $this->render(
                    'renderHighcharts',
                    [
                        'data' => $highchartsData,
                        'currencyName' => $model->currencyName,
                        'nominal' => $model->nominal
                    ]
                );
            }
            Yii::$app->session->setFlash('danger', 'Не удалось обработать запрос');
        }
        return $this->render(
            'highcharts',
            [
                'model' => $model,
                'list' => $listOfCurrencies,
                'options' => $options
            ]
        );
    }
    public function actionGetReports()
    {
        $data = $this->getListOfCurrencies();
        $listOfCurrencies = $data['curList'];
        $options = $data['options'];
        $model = new HighchartsForm();
        if($model->load(Yii::$app->request->post())){
            if ($model->validate()){
                try {
                    $dynamic = $this->getCurrenciesDynamic(
                        $model->dateFrom,
                        $model->dateTo,
                        $model->currency
                    );
                } catch (Exception $e) {
                    $dynamic = [];
                }
                if(!$dynamic || !isset($dynamic['@attributes']) || !isset($dynamic['Record'])){
                    return $this->render('wrong');
                }
                $reportJson = $this->createReportJson($model, $dynamic);
                $this->sendReport($reportJson);
            } else {
                Yii::$app->session->setFlash('danger', 'Не удалось обработать запрос');
            }
        }
        return $this->render(
            'reports',
            [
                'model' => $model,
                'list' => $listOfCurrencies,
                'options' => $options
            ]
        );
    }
    private function createReportJson($model, $dynamic){
        $report = [];
        $report['report_info'] = [
            'currency_name' => $model->currencyName,
            'currency_nominal' => $model->nominal,
            'created_at' => date('d-M-Y'),
            'date_from' => $model->dateFrom,
            'date_to' => $model->dateTo
        ];
        $report['dynamic_info'] = [];
        foreach ($dynamic['Record'] as $day){
            $report['dynamic_info'][] = [
                'Date' => $day['@attributes']['Date'],
                'Value' => $day['Value']
            ];
        }
        $reportJson = Json::encode($report);
        return $reportJson;
    }

    private function sendReport($json)
    {
        header("Pragma: public");
        header("Content-Type: application/json; charset=utf-8");
        header("Content-Disposition: attachment; charset=utf-8; filename=report.json");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . strlen($json));
        echo $json;
        exit();
    }

    private function getCurrenciesDynamic($dateFrom, $dateTo, $code)
    {
        $df = date('d/m/Y' ,strtotime($dateFrom));
        $dt = date('d/m/Y' ,strtotime($dateTo));
        $cd = trim($code);
        $client = new Client([
            'baseUrl' => Yii::$app->params['crbApiUrl'].'XML_dynamic.asp',
            'responseConfig' => [
                'format' => Client::FORMAT_XML
            ],
        ]);
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setData(['date_req1' => $df, 'date_req2' => $dt, 'VAL_NM_RQ' => $cd])
            ->send();
        if ($response->isOk) {
            $result = $response->data;
            return $result;
        }
        return false;
    }
    private function getListOfCurrencies()
    {
        $client = new Client([
            'baseUrl' => Yii::$app->params['crbApiUrl'].'XML_val.asp',
            'responseConfig' => [
                'format' => Client::FORMAT_XML
            ],
        ]);
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setData(['d' => 0])
            ->send();
        if ($response->isOk) {
            $currenciesList = ArrayHelper::map($response->data['Item'], 'ParentCode', 'Name');
            $options = [];
            foreach ($response->data['Item'] as $index => $item) {
                $options[$item['ParentCode']] = [
                    'data-nominal' => $item['Nominal']
                ];
            }
            return [
                'curList' => $currenciesList,
                'options' => $options
            ];
        }
        return false;
    }
    private function prepareDataForView($data)
    {
        $provider = new ArrayDataProvider([
            'allModels' => $data,
            'sort' => [
                'attributes' => ['Name', 'CharCode', 'Nominal', 'Value'],
            ],
        ]);
        return $provider;
    }
    private function getDataFromCB($date)
    {
        $date = $date ?? date('d/m/Y');
        $client = new Client([
            'baseUrl' => Yii::$app->params['crbApiUrl'].'XML_daily.asp',
            'responseConfig' => [
                'format' => Client::FORMAT_XML
            ],
        ]);
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setData(['date_req' => $date])
            ->send();
        if ($response->isOk) {
            return $response->data['Valute'] ?? false;
        }
    }
}