<?php

use yii\helpers\Html;

echo Html::tag(
    'p',
    Yii::t('app', 'По данному запросу в данный момент нет информации'),
    [
        'class' => 'bg-danger',
        'style' => [
            'padding' => '15px'
        ]
    ]
);