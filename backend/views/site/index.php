<?php

use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';

$books = [];
$books[] = [
    'url' => '#',
    'name' => 'Create new book',
    'description' => ''
];
?>
<div class="row">
    <div class="box box-solid">
        <div class="box-header">
            <h3 class="box-title"><i class="fa fa-user"></i> Personal Books</h3>
        </div>
        <div class="box-body">
            <?php foreach (array_chunk($books, 4) as $chunk): ?>
                <div class="row">
                    <?php foreach ($chunk as $book): ?>
                        <div class="col-md-3">
                            <a href="<?= Url::to($book['url']) ?>">
                                <div class="small-box bg-gray">
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
</div>
