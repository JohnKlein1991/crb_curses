<?php
/* @var $model \app\modules\curses\models\HighchartsForm */
/* @var $list array */
/* @var $options array */

use yii\helpers\Html;
use dosamigos\datepicker\DateRangePicker;
use yii\widgets\ActiveForm;
use yii\web\JqueryAsset;

$this->registerJsFile('@web/js/highcharts_view.js',
    ['depends' => [JqueryAsset::class]]);

echo Html::tag('h3', 'Выберите промежуток времени и валюту');


$form = ActiveForm::begin([
    'enableClientValidation' => false,
]);
echo $form->field($model, 'dateFrom')
    ->label('Выберите промежуток времени:')
    ->widget(DateRangePicker::class, [
        'options' => [
            'readonly' => true
        ],
        'optionsTo' => [
            'readonly' => true
        ],
        'labelTo' => '',
        'attributeTo' => 'dateTo',
        'form' => $form,
        'language' => 'ru',
        'size' => 'sm',
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy/mm/dd',
            'endDate' => 'today',
        ]
    ]);
echo $form->field($model, 'currency')
    ->label('Выберите валюту:')
    ->dropDownList(
    $list,
        [
            'options' => $options,
            'prompt' => 'Выберите валюту',
            'required' => true
        ]
    );
echo $form->field($model, 'nominal', [
    'enableClientValidation' => false,
])
    ->hiddenInput()
    ->label(false);

echo $form->field($model, 'currencyName', [
    'enableClientValidation' => false
])
    ->hiddenInput()
    ->label(false);

echo Html::submitButton('Построить график', ['class' => 'btn btn-primary']);
ActiveForm::end();