<?php
namespace app\components;

use yii\base\BootstrapInterface;
use Yii;

class LanguageSelector implements BootstrapInterface
{
    public $allowedLanguages = [
        'ru-RU', 'en-EN'
    ];

    public function bootstrap($app)
    {
        $lanCookie = Yii::$app->request->cookies['language'];
        if(in_array($lanCookie, $this->allowedLanguages)){
            Yii::$app->language = $lanCookie;
        }
    }
}