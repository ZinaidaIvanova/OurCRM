<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ServicesetSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Servicesets';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="serviceset-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Serviceset', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_serviceset',
            'id_project',
            'id_state',
            'delivery',
            'payment',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>