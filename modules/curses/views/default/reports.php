<?php
/* view для отчеттов */
/* @var $model \app\modules\curses\models\HighchartsForm */
/* @var $list array */
/* @var $options array */

use yii\helpers\Html;
use dosamigos\datepicker\DateRangePicker;
use yii\widgets\ActiveForm;
use yii\web\JqueryAsset;

$this->registerJsFile('@web/js/highcharts_form.js',
    ['depends' => [JqueryAsset::class]]);

Html::tag('h3', 'Выберите промежуток времени и валюту, и нажмите "Запросить отчет"');


$form = ActiveForm::begin([
    'enableClientValidation' => false,
]);
echo $form->field($model, 'dateFrom')
    ->label('Выберите промежуток времени:')
    ->widget(DateRangePicker::class, [
        'options' => [
            'readonly' => true,
            'required' => true,
            'placeholder' => 'Кликните для выбора даты'
        ],
        'optionsTo' => [
            'readonly' => true,
            'required' => true,
            'placeholder' => 'Кликните для выбора даты'
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
echo $form->field($model, 'currency')->dropDownList(
    $list,
    [
        'options' => $options,
        'prompt' => 'Выберите валюту',
        'required' => true
    ]
);
echo $form->field($model, 'nominal', [
    'enableClientValidation' => false
])
    ->hiddenInput()
    ->label(false);

echo $form->field($model, 'currencyName', [
    'enableClientValidation' => false
])
    ->hiddenInput()
    ->label(false);

echo Html::submitButton('Получить отчет', ['class' => 'btn btn-primary']);
ActiveForm::end();