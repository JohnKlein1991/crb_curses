<?php
/* view для отображения таблицы с валютами */
/* @var $data array */
/* @var $model \app\modules\curses\models\CurrentCurses */
/* @var $date string */
/* @var $lang string */

use fedemotta\datatables\DataTables;
use dosamigos\datepicker\DatePicker;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

if(Yii::$app->session->hasFlash('danger')){
    Yii::$app->session->getFlash('danger');
}
$form = ActiveForm::begin();
echo $form->field($model, 'date')
    ->label(Yii::t('app', 'Выберите дату:'))
    ->widget(
        DatePicker::class, [
            'attribute' => 'date',
            'template' => '{addon}{input}',
            'language' => $lang,
            'options' => [
                'readonly' => true,
                'placeholder' => Yii::t('app', 'Кликните для выбора даты')
            ],
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'dd/mm/yyyy',
                'endDate' => 'today',
            ]
    ]);

echo Html::submitButton(Yii::t('app', 'Показать курсы валют'), ['class' => 'btn btn-primary']);
ActiveForm::end();
if($data){
    echo '<br>';
    echo Html::tag('h3', Yii::t('app', 'Курсы валют на ').$date);
    echo DataTables::widget([
        'dataProvider' => $data,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'Name', 'CharCode', 'Nominal', 'Value',
        ],
        'clientOptions' => [
            'language' => 'fr',
            "lengthMenu"=> [[15,-1], [15,Yii::t('app',"All")]],
            "info"=>'Test info',
            "responsive"=>true,
        ],
    ]);
}