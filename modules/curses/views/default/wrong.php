<?php

use yii\helpers\Html;

echo Html::tag(
    'p',
    'По данному курсу валют информации нет',
    [
        'class' => 'bg-danger',
        'style' => [
            'padding' => '15px'
        ]
    ]
);