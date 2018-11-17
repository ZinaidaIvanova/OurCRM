<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 06.11.2018
 * Time: 23:01
 */

namespace app\service;

use app\models\Servicelist;
use app\models\ServiceSearch;
use app\models\ServiceListForm;
use app\models\Serviceset;
use app\models\StateCheck;
use yii\helpers\ArrayHelper;
use app\db_modules\servisetDbQuery;
use app\service\ServiceListFormHandler;




class ServicesetHandler
{

    public function CreateNewSet($idProject)
    {
        $model = new Serviceset();
        $stateName = new StateCheck();
        $model->id_project = $idProject;
        $model->id_state = $stateName::MakeContact;
        return $model;
    }

    public function CreateNewLists($id, $modelForm)
    {
        $listHandler = new ServiceListFormHandler();
        $this->saveServiceListArray($listHandler->getServiceList($id, $modelForm));
    }

    public function CreateNewServiceset($idProject, $modelForm)
    {
        $model = $this->CreateNewSet($idProject);
        $this->CreateNewLists($model->id_serviceset, $modelForm);
    }

    public function DeleteServiceset($id)
    {
        if (($modelServiceList = ServiceList::findAll(['id_serviceset' => $id])) != null) {
            foreach ($modelServiceList as $el) {
                $el->delete();
            }
        }

        if (($model = Serviceset::findOne($id)) !== null) {
            $model->delete();
        }
    }



    public function findServiceList($id)
    {
        $serviceListInfo = new servisetDbQuery();
        $setInfo = $serviceListInfo->getServiceSetInfo($id);
        $arr = [];
        for ($i = 0; $i < count($setInfo); $i++) {
            $arr[$i] = ['Service' => $setInfo[$i]['id']];
        }
        return $arr;
    }

    public function getServicelistFormById($id)
    {
        $modelForm = new ServiceListForm();
        $modelForm->serviceList = $this->findServiceList($id);
        return $modelForm;
    }

    public function getServiceListItems()
    {
        $service = new ServiceSearch();
        return $service->getServiceListItems();
    }

    public function getStateList()
    {
        $state = new StateCheck();
        return $state->getStateList();
    }


    public function checkLastPage($pathRefer, $pathCurr, $address)
    {
        $gettingId = $this->getReferrerId($address);

        return ((($this->checkPage($address, $pathRefer)) && ($gettingId != NULL)) || ($this->checkPage($address, $pathCurr)));
    }

    public function updateServiceListArray($arrData, $arrModel)
    {

        $num = min(count($arrData), count($arrModel));

        if ($num != 0) {
            for ($i = 0; $i < $num; $i++) {
                $arrModel[$i]->saveServiceList($arrData[$i]);
            }
        }

        if (count($arrData) > count($arrModel)) {
            for ($i = $num; $i < count($arrData); $i++) {
                $model = new Servicelist();
                $model->saveServiceList($arrData[$i]);
            }
        }

        for ($i = $num; $i < count($arrModel); $i++) {
            $arrModel[$i]->delete();
        }
    }

    public function saveServiceListArray($arr)
    {
        foreach ($arr as $item) {
            $model = new Servicelist();
            $model->saveServiceList($item);
        }
    }

    public function saveNewServiceSet($project_id)
    {
        $model = new Serviceset();
        $model->id_project = $project_id;
        $model->id_state = 1;
        if (!($model->save())) {
            return NULL;
        }
        return $model->id_serviceset;
    }

    public function getReferrerId($str)
    {
        $result = NULL;
        parse_str($str, $el);
        if (ArrayHelper::keyExists('id', $el)) {
            $result = (integer)$el['id'];
        }
        return $result;
    }

    public function checkPage($str, $path)
    {
        $query = parse_url($str, PHP_URL_QUERY);
        parse_str($query, $el);
        if (ArrayHelper::keyExists('r', $el)) {
            return ($el['r'] === $path);
        }
        return false;
    }

    public function checkGetString($str, $key)
    {
        //проверить есть ли в $str выражение вида ' $key.-. цифра '
        $reg = '/' . $key . '-[0-9]{1,}/';
        return preg_match($reg, $str, $result);
    }

    public function getIdFromStringByKey($str, $key)
    {
        //найти в $str из выражение вида ' $key.-. цифра ' цифру
        $arr = explode(' ', $str);
        $reg = '/' . $key . '-[0-9]{1,}/';
        $id = null;
        $counter = 0;
        foreach ($arr as $el) {
            if (preg_match($reg, $el, $findEl)) {
                preg_match('/[0-9]{1,}/', $findEl[0], $result);
                $id = $result[0];
                $counter++;
            }
        }

        if ($counter != 1) {
            $id = null;
        }

        return $id;
    }
}