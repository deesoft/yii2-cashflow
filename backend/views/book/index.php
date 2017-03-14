<?php

use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';

?>
<div class="row">
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
                            $url = isset($book['id']) ? ['view','id'=>$book['id']] : ['create', 'team_id' => $team['id']];
                            ?>
                            <a href="<?= Url::to($url) ?>">
                                <div class="small-box bg-<?= isset($book['id']) ? 'green':'gray'?>">
                                    <div class="inner">
                                        <h4><?= $book['name'] ?></h4>
                                        <p>&nbsp;<?= $book['description'] ?></p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-stats-bars"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
