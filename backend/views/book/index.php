<?php

use yii\helpers\Html;
use common\models\Book;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $book Book */

$this->title = 'Books';
$model = new Book();

$this->registerJs($this->render('index.js'));
?>

    <?php foreach ($teams as $team): ?>
        <div class="box box-solid">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-user"></i> &nbsp; <?= $team['name'] ?></h3>
            </div>
            <div class="box-body">
                <?php foreach (array_chunk($team['books'], 4) as $chunk): ?>
                    <div class="row">
                        <?php foreach ($chunk as $book): ?>
                            <div class="col-md-3">
                                <?php
                                if ($book) {
                                    echo Html::a($this->render('_book-item', [
                                            'color' => 'green',
                                            'name' => $book->name,
                                            'description' => $book->description,
                                        ]), ['view', 'id' => $book->id], []);
                                } else {
                                    echo Html::a($this->render('_book-item', [
                                            'color' => 'gray',
                                            'name' => 'Create new book',
                                            'description' => '',
                                        ]), null, ['data-toggle' => 'modal', 'data-target' => '#book-dlg', 'role' => 'button', 'data-team' => $team['id']]);
                                }
                                ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
    <?= Html::a('Create team', null, ['data-toggle' => 'modal', 'data-target' => '#book-dlg', 'role' => 'button']);?>


<div class="modal fade" id="book-dlg" tabindex="-1" role="dialog" aria-labelledby="bookDialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" >New Book</h4>
            </div>
            <div class="modal-body">
                <?php
                $form = ActiveForm::begin([
                        'id' => 'book-form',
                        'action'=> ['create']
                ]);
                ?>

                <?= $form->field($model, 'name', ['template'=>"{label}\n{input}"]) ?>

                <?= $form->field($model, 'team_id')->dropDownList($itemTeams, ['prompt' => '(none)'])->label('Team') ?>

                <?php ActiveForm::end(); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-save">Save</button>
            </div>
        </div>
    </div>
</div>
