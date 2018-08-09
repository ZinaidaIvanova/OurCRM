<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>
<div class="post panel">
    <div class="panel-body">
        <h2><?= Html::encode($model->name) ?></h2>
        <?= HtmlPurifier::process($model->id_client) ?>
        <a href="#" class="btn btn_more">view more</a>
    </div>
</div>
