<?php

use yii\helpers\Html;

echo Html::tag(
    'p',
    'По данному запросу в данный момент нет информации',
    [
        'class' => 'bg-danger',
        'style' => [
            'padding' => '15px'
        ]
    ]
);