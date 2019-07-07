<?php
/* view для отображения графика изменения определенной валюты */
/* @var $data array */
/* @var $currencyName string */
/* @var $nominal integer */

use miloschuman\highcharts\Highcharts;
use yii\helpers\Html;

if(!isset($data['Record'])){
    echo Html::tag(
        'p',
        Yii::t('app', 'По данному курсу валют информации нет'),
        [
            'class' => 'bg-danger',
            'style' => [
                'padding' => '15px'
            ]
        ]
    );
} else {
    $dateFrom = $data['@attributes']['DateRange1'];
    $dateTo = $data['@attributes']['DateRange2'];
    $xk = [];
    $series = [];
    foreach ($data['Record'] as $item){
        $yk[] = (float) str_replace(',','.',$item['Value']);
        $xk[] = $item['@attributes']['Date'];
    }

    echo Highcharts::widget([
        'options' => [
            'title' => ['text' => $currencyName.' c '.$dateFrom.' по '.$dateTo],
            'legend' => [
                'layout' => 'vertical',
                'align' => 'right',
                'verticalAlign' => 'middle'
            ],
            'plotOptions' => [
                'series' => [
                    'label' => [
                        'connectiorAllowed' => true
                    ]
                ],
                'pointStart' => $xk[0]
            ],
            'xAxis' => [
                'categories' => $xk
            ],
            'yAxis' => [
                'title' => ['text' => Yii::t(
                    'app',
                    'Стоимость {nominal} ед.',
                    [
                        'nominal' => $nominal
                    ]
                )],
                'categories' => $yk
            ],
            'series' => [
                ['name' => $currencyName, 'data' => $yk],
            ]
        ]
    ]);
}