<?php

namespace app\controllers;

use Yii;
use app\models\Client;
use app\models\ClientSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Project;
use app\models\ProjectSearch;
use app\models\Event;
use app\models\EventSearch;
use yii\db\StaleObjectException;


/**
 * ClientController implements the CRUD actions for Client model.
 */
class ClientController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Client models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ClientSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Client model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $searchModel = new ProjectSearch();
        $dataProvider = $searchModel->searchClientProject($id);
        $searchEventModel = new EventSearch();
        $eventDataProvider = $searchEventModel->searchEventId($id, Yii::$app->user->identity->id_user, 1);
        $searchClientEventModel = new EventSearch();
        $clientEventDataProvider = $searchClientEventModel->searchClientEventId($id, Yii::$app->user->identity->id_user, 1);
        return $this->render('view', [
            'model' => $this->findModel($id, Yii::$app->user->identity->id_user),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchEventModel' => $searchEventModel,
            'eventDataProvider' => $eventDataProvider,
            'clientEventDataProvider' => $clientEventDataProvider,
        ]);
    }

    /**
     * Creates a new Client model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Client();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_client]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Client model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

<<<<<<< HEAD
        $model2 = new Client();
        $model2->load(Yii::$app->request->post());
        if (SecurityController::validateParam1($model, $model2)) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id_user]);
=======
        try {
            $model2 = new Client();
            $model2->load(Yii::$app->request->post());
            switch ($model2->id_user) {
                case '':
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                    break;
                case $model->id_user:
                    if ($model->load(Yii::$app->request->post()) && $model->save()) {
                        return $this->redirect(['view', 'id' => $model->id_user]);
                    };
                    break;
                default:
                    return $this->render('update', [
                        'model' => $model,
                    ]);
>>>>>>> parent of d532fda... Набросок Security
            }
        } else {
            return $this->render('update', ['model' => $model,]);
        }
    }

    /**
     * Deletes an existing Client model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public
    function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Client model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Client the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */


    protected
    function findModel($id)
    {
        if (($model = Client::findOne($id)) !== null) {
            if ($model->id_user == Yii::$app->user->identity->id_user) {
                return $model;
            }

        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}

