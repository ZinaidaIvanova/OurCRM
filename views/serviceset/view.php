<?php

use yii\helpers\Html;
use yii\widgets\DetailView;


/* @var $this yii\web\View */
/* @var $model app\models\Serviceset */

$this->title = $model->id_serviceset;
$this->params['breadcrumbs'][] = ['label' => 'Servicesets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="serviceset-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_serviceset], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_serviceset], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_serviceset',
            'id_project',
            'id_state',
            'delivery',
            'payment',
        ],
    ]) ?>

</div>

<div class="servicelist-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formServiceLIst',[
        'modelForm' => $modelForm,
        'itemsService' => $itemsService,
        'idServiceSet' => $idServiceSet,
    ]) ?>


</div>

