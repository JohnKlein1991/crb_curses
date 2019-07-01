<?php
/* модель для работы курсами */

namespace app\modules\curses\models;

use yii\base\Model;

class Currency extends Model
{
    const SCENARIO_SHOW_CURSES = 'show_curses';
    const SCENARIO_SHOW_HIGHCHART = 'show_highchart';
    const SCENARIO_MAKE_REPORT = 'make_report';

    public $dateFrom;
    public $dateTo;
    public $currency;
    public $currencyName;
    public $nominal;
    public $date;

    public function scenarios()
    {
        return [
            self::SCENARIO_MAKE_REPORT => ['dateFrom', 'dateTo', 'currency', 'currencyName', 'nominal'],
            self::SCENARIO_SHOW_HIGHCHART => ['dateFrom', 'dateTo', 'currency', 'currencyName', 'nominal'],
            self::SCENARIO_SHOW_CURSES => ['date']
        ];
    }

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
            [['nominal'], 'integer'],
            [['date'], 'date', 'format'=>'dd/mm/YYYY', 'message' => 'Некорректная дата'],
        ];
    }
}