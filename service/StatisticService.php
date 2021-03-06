<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 20.11.2018
 * Time: 19:38
 */

namespace app\service;


use Yii;
use app\db_modules\StatisticDbQuery;
use app\models\StateCheck;
use app\forms\StatisticForm;
use app\forms\HeadStatisticForm;
use DateInterval;
use DateTime;
use app\service\DateService;


class StatisticService
{
    protected $dbQuery;
    protected $dateService;



    public function __construct()
    {
        $this->setDbQuery(new StatisticDbQuery());
        $this->setDateService(new DateService());
    }

    public function setDbQuery($param)
    {
        $this->dbQuery = $param;
    }

    public function setDateService($param)
    {
        $this->dateService = $param;
    }

    public function getServicesetNumByStateInfo($datePeriod)
    {
        $query = $this->dbQuery->getServicesetNumByState($datePeriod->user);

        $columns = ['Этап продаж', 'количество'];

        $result = $this->addState($query, $columns);

        return $result;
    }

    public function getProjectNumByStateForPeriod($datePeriod)
    {
        $query = $this->dbQuery->getProjectNumberForPeriod($datePeriod->user, $datePeriod->from, $datePeriod->to);

        $columns = ['month', 'all', 'close', 'cancellation'];

        $result = $this->dateService->addMonthInfo($query, $columns, $datePeriod->from, $datePeriod->to);
        $result[0] = ['Месяц', 'Всего пакетов', 'Закрытые пакеты', 'Отказы'];

        return $result;
    }

    public function getSalesForLastPeriod($datePeriod)
    {
       $query = $this->dbQuery->getSalesForPeriod($datePeriod->user, $datePeriod->from, $datePeriod->to);

        $columns = ['month', 'sale'];

        $result = $this->dateService->addMonthInfo($query, $columns, $datePeriod->from, $datePeriod->to);
        $result[0] = ['Месяц', 'Сумма продаж'];
        return $result;
    }



    public function getChartInfo($datePeriod)
    {
        $result = null;
        switch ($datePeriod->type){
            case 'project':
                $result = $this->getProjectNumByStateForPeriod($datePeriod);
                break;
            case 'sale':
                $result = $this->getSalesForLastPeriod($datePeriod);
                break;
            case 'serviceset':
                $result = $this->getServicesetNumByStateInfo($datePeriod);
                break;
            default:
                return false;
        }

        return $result;
    }



    public function getInitalPeriod($type)
    {
        $date = new StatisticForm();
        $currDate = new DateTime;
        $date->to = $currDate->format('Y-m-d');
        $currDate->sub(DateInterval::createFromDateString('1 year'));
        $date->from = $currDate->format('Y-m-d');;
        $date->type = $type;
        $date->user = Yii::$app->user->identity->id_user;
        return $date;
    }

    public function addState($data, $colmns)
    {
        $result = [];
        array_push($result, $colmns);
        $state = new StateCheck();
        $list = $state->getStateList();
        $find = false;
        $num = -1;

        for($i = $state::MakeContact; $i <= $state::Delivery; $i++)
        {
            foreach ($data as $el) {
                $find = ($el['state'] == $i);
                $num++;
                if($find) {
                    break;
                }
            }

            if($find)
            {
                $arrEl = [(string)$list[$i],  (int)$data[$num]['num']];
            } else {
                $arrEl = [(string)$list[$i], (int)0];
            }

            array_push($result, $arrEl);
            $find = false;
            $num = -1;
        }

        return $result;
    }

}