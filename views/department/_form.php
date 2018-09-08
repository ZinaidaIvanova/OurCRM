<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Department */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="department-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= Html::activeHiddenInput($model, 'version'); ?>

    <div class="form-group">
        <?= Html::submitButton(\Yii::t('common', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
