<?php
/* view форма для запроса графика валюты */
/* @var $model \app\modules\curses\models\Currency */
/* @var $list array */
/* @var $options array */
/* @var $lang string */

use yii\helpers\Html;
use dosamigos\datepicker\DateRangePicker;
use yii\widgets\ActiveForm;
use yii\web\JqueryAsset;

$this->registerJsFile('@web/js/highcharts_form.js',
    ['depends' => [JqueryAsset::class]]);

echo Html::tag('h3', Yii::t('app', 'Выберите промежуток времени и валюту'));


$form = ActiveForm::begin([
    'enableClientValidation' => false,
]);
echo $form->field($model, 'dateFrom')
    ->label(Yii::t('app', 'Выберите промежуток времени:'))
    ->widget(DateRangePicker::class, [
        'options' => [
            'readonly' => true,
            'required' => true,
            'placeholder' => Yii::t('app', 'Кликните для выбора даты')
        ],
        'optionsTo' => [
            'readonly' => true,
            'required' => true,
            'placeholder' => Yii::t('app', 'Кликните для выбора даты')
        ],
        'labelTo' => '',
        'attributeTo' => 'dateTo',
        'form' => $form,
        'language' => $lang,
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
            'prompt' => Yii::t('app', 'Выберите валюту'),
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

echo Html::submitButton(Yii::t('app', 'Построить график'), ['class' => 'btn btn-primary']);
ActiveForm::end();