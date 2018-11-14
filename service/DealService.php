<?php

namespace app\service;

use Yii;
use app\models\User;
use app\models\Project;
use app\models\Client;

class DealService
{
    private $startParams;
    private $dataControl;

    public function __construct()
    {
        $this->setStartParams(new StartParamsService()) ;
        $this->setDataControl(new DataControlService());
    }

    public function setDataControl($dataControlService)
    {
        $this->dataControl = $dataControlService;
    }

    public function setStartParams($startParams)
    {
        $this->startParams = $startParams;
    }

    public function actionDealCreate()
    {
        $user = User::findOne(Yii::$app->user->identity->id_user);//todo использовать метод из сервиса
        $project = new Project();
        $client = new Client();
        if (!isset($user, $project, $client)) {
            throw new NotFoundHttpException("Something get wrong");
        }
        $this->startParams->takeStartParams($project);
        $this->startParams->takeStartParams($client);
        if ($this->dataControl->dataControl($project) && $this->dataControl->dataControl($client)) {
            if ($project->load(Yii::$app->request->post()) && $client->load(Yii::$app->request->post())) {
                {
                    $client->save(false);
                    $project->id_client = $client->id_client;
                    $project->save(false);
                    return [
                        'user' => $user,
                        'project' => $project,
                        'client' => $client,
                        'action' => 'redirect',
                    ];
                }
            }
        }
        return [
            'user' => $user,
            'project' => $project,
            'client' => $client,
            'action' => 'curr',
        ];
    }


}