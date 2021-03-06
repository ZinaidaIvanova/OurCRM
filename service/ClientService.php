<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 07.12.2018
 * Time: 20:27
 */

namespace app\service;


use app\models\ClientSearch;
use yii;
use app\db_modules\PersonDbQuery;
use yii\helpers\ArrayHelper;
use app\models\ProjectSearch;
use app\models\EventSearch;
use app\models\Client;
use app\models\Person;

class ClientService
{

    private $startParams;
    private $dataControl;
// аписать коструктор для этого класса __construct
//попробовать подключать класс конфигурации и подавать туда конкретный data control
    public function __construct()
    {
        $this->setStartParams(new StartParamsService());
        $this->setDataControl(new DataValidateService());
    }

    public function setDataControl($dataControlService)
    {
        $this->dataControl = $dataControlService;
    }

    public function setStartParams($startParams)
    {
        $this->startParams = $startParams;
    }

    public function getAllClients()
    {
        $searchModel = new ClientSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ];
    }

    public function getClientViewData($id)
    {
        $session = Yii::$app->session;
        $session->set('id_client', $id);
        $searchProjectModel = new ProjectSearch();
        $dataProjectProvider = $searchProjectModel->searchClientProject($id);
        $searchEventModel = new EventSearch();
        $eventDataProvider = $searchEventModel->searchEventId($id, Yii::$app->user->identity->id_user, 1);
        $searchClientEventModel = new EventSearch();
        $clientEventDataProvider = $searchClientEventModel->searchClientEventId($id, Yii::$app->user->identity->id_user, 1);
        $person = $this->GetMainPersonInfo($id);
        $searchClientModel = new ClientSearch();
        $clientModel = $searchClientModel->findOne($id, Yii::$app->user->identity->id_user);
        return [
            'model' => $clientModel,
            'searchModel' => $searchProjectModel,
            'dataProvider' => $dataProjectProvider,
            'searchEventModel' => $searchEventModel,
            'eventDataProvider' => $eventDataProvider,
            'clientEventDataProvider' => $clientEventDataProvider,
            'person' => $person,
        ];
    }

    public function setClient()
    {
        $model = new Client();
        $modelPerson = new Person();
        $this->startParams->takeStartParams($model);
        $this->startParams->takeStartParams($modelPerson);
        if ($model->load(Yii::$app->request->post()) && $modelPerson->load(Yii::$app->request->post())) {
            if ($this->dataControl->dataControl($model) && $this->dataControl->dataControl($modelPerson)) {
                if ($this->SaveNewClientAndPerson($model, $modelPerson)) {
                    return ['view', 'model' => $model, 'action' => 'redirect'];
                }
            }
        } else
            return [
                'model' => $model,
                'modelPerson' => $modelPerson,
                'action' => 'curr'
            ];
    }

    public function setClientUpdate($id)
    {
        $model = $this->findModel($id);
        try {
            if ($this->dataControl->checkElemAvailable($model)) {
                if ($model->load(Yii::$app->request->post()) && $this->dataControl->dataControl($model) && $model->save()) {
                    return $this->redirect(['view', 'id' => $model->id_client]);
                }
                return [
                    'model' => $model, 'action' => 'redirect'];
            }
            return [
                'model' => $model, 'action' => 'curr'];
        } catch (StaleObjectException $e) {
            throw new StaleObjectException(Yii::t('app', 'Error data version'));
        }
    }

    public function SaveNewClientAndPerson($client, $person)
    {
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        $result = false;

        try {
            $client->save();
            $person->main = 1;
            $person->id_client = $client->id_client;
            $person->save();
            $transaction->commit();
            $result = true;
        } catch (\Exception $e) {
            $transaction->rollBack();
        }

        return $result;
    }

    public function GetMainPersonInfo($idClient) //todo в перенести в сервис person и поправить код сверху
    {
        $personSearch = new PersonDbQuery();
        $arr = $personSearch->SearchMainPerson($idClient);
        $info = [];
        if ($arr) {
            $info = [
                'id' => $arr[0]['id_person'],
                'first_name' => $arr[0]['first_name'],
                'last_name' => $arr[0]['last_name'],
                'position' => $arr[0]['position'],
                'contact' => $arr[0]['contact'],
                'email' => $arr[0]['email']
            ];
        }


        return $info;
    }

    public function GetClientList($idUser)
    {
        $arr = (new ClientSearch())->searchClientList($idUser);
        $result = ArrayHelper::map($arr, 'id_client', 'name');
        return $result;
    }

}