<?php

use yii\web\View;
use yii\widgets\ActiveForm;
use common\models\Book;

/* @var $this View */
$model = new Book();

?>
<?php
$form = ActiveForm::begin([
        'id' => 'book-form',
    ]);
?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'team_id')->dropDownList([
    0=>'(none)'
]) ?>

<?php ActiveForm::end(); ?>
