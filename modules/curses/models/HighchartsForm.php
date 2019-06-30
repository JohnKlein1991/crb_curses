<?php


namespace app\modules\curses\models;

use yii\base\Model;

class HighchartsForm extends Model
{
    public $dateFrom;
    public $dateTo;
    public $currency;
    public $currencyName;
    public $nominal;

    public function rules()
    {
        return [
            [['dateFrom', 'dateTo', 'currency'], 'required'],
            [['currencyName', 'nominal'], 'required', 'message' => ''],
            [['dateFrom', 'dateTo'], 'date', 'format' => 'php:Y/m/d',
                'message' => 'Некорректная дата'],
            [['currency', 'currencyName'], 'string'],
            ['dateFrom', 'compare', 'compareAttribute' => 'dateTo', 'operator' => '<', 'type' => 'date',
                'message' => 'Некорректная начальная дата'],
            ['dateTo', 'compare', 'compareAttribute' => 'dateFrom', 'operator' => '>', 'type' => 'date',
                'message' => 'Некорректная конечная дата'],
            [['nominal'], 'integer']
        ];
    }
    public function save()
    {
        if($this->validate()){
            return true;
        }
        return false;
    }
}