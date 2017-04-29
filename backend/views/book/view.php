<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Book */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-2">
        <div class="box box-solid">
            <div class="box-body">
                xxxxx
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box box-solid">
            <div class="box-body">
                <div id="content-transaction">
                    <div class="row">
                        <div class="col-sm-2">
                            17/03<br><span class="label label-success">&nbsp;</span>
                        </div>
                        <div class="col-sm-6">
                            Text
                        </div>
                        <div class="col-sm-2">

                        </div>
                        <div class="col-sm-2" style="text-align: right;">
                            Rp2000
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            17/03<br><span class="label label-success">&nbsp;</span>
                        </div>
                        <div class="col-sm-6">
                            Text
                        </div>
                        <div class="col-sm-2" style="text-align: right;">
                            Rp2000
                        </div>
                        <div class="col-sm-2" style="text-align: right;">

                        </div>
                    </div>
                </div>
                <div id="button-transaction">
                    <a role="button">Add new line...</a>
                    <a role="button">Add new Chart...</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="box box-solid">
            <div class="box-body">

            </div>
        </div>
    </div>
</div>

