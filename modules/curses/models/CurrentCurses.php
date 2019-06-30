<?php

namespace app\modules\curses\models;

use yii\base\Model;

class CurrentCurses extends Model
{
    public $date;

    public function rules()
    {
        return [
            [['date'], 'date', 'format'=>'dd/mm/YYYY', 'message' => 'Некорректная дата'],
        ];
    }
}