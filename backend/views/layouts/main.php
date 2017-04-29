<?php
/* @var $this \yii\web\View */
/* @var $content string */

use common\assets\AdminlteAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

$adminLte = AdminlteAsset::register($this);
$baseUrl = $adminLte->baseUrl;
$smallTitle = isset($this->params['smallTitle']) ? $this->params['smallTitle'] : '';
$user = Yii::$app->user;
$ngAppName = isset($this->params['ngApp']) ? $this->params['ngApp'] : '';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" ng-app="<?= $ngAppName ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="hold-transition skin-green-light layout-top-nav">
        <?php $this->beginBody() ?>
        <div class="wrapper">
            <header class="main-header">
                <nav class="navbar navbar-static-top">
                    <div class="container">
                        <div class="navbar-header">
                            <?= Html::a('Cashflow', Yii::$app->homeUrl, ['class' => 'navbar-brand']) ?>
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                                <i class="fa fa-bars"></i>
                            </button>
                        </div>

                        <!-- Navbar Right Menu -->
                        <div class="collapse navbar-collapse pull-right">
                            <ul class="nav navbar-nav">
                                <!-- User Account Menu -->
                                <?php if($user->isGuest): ?>
                                    <li>
                                        <?= Html::a('Sign In', ['/user/login'], ['class'=>'btn btn-danger'])?>
                                    </li>
                                    <li>
                                        <?= Html::a('Sign Up', ['/user/signup'], ['class'=>'btn btn-success'])?>
                                    </li>
                                <?php else: ?>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            <i class="fa fa-plus"></i>
                                        </a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li>
                                                <a href="#">Add Book</a>
                                            </li>
                                            <li>
                                                <a href="#">Add Team</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="dropdown user user-menu">
                                        <!-- Menu Toggle Button -->
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            <img src="<?= $user->avatarUrl ?>" class="user-image" alt="User Image">
                                            <span class="hidden-xs"><?= $user->fullname ?></span>
                                        </a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li>
                                                <?= Html::a('Profile', ['/user/profile'], [])?>
                                            </li>
                                            <li>
                                                <?= Html::a('Logout', ['/user/logout'], ['data-method'=>'post'])?>
                                            </li>
                                        </ul>
                                    </li>
                                <?php endif;?>
                            </ul>
                        </div>
                        <!-- /.navbar-custom-menu -->
                    </div>
                    <!-- /.container-fluid -->
                </nav>
            </header>
            <!-- Full Width Column -->
            <div class="content-wrapper">
                <div class="container">
                    <!-- Content Header (Page header) -->
                    <section class="content-header" style="display: none;">
                        <h1><?= $this->title ?><small><?= $smallTitle ?></small></h1>
                        <?=
                        Breadcrumbs::widget([
                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        ])
                        ?>
                        <?= Alert::widget() ?>
                    </section>

                    <!-- Main content -->
                    <section class="content">
                        <?= $content ?>
                    </section>
                    <!-- /.content -->
                </div>
                <!-- /.container -->
            </div>
            <!-- /.content-wrapper -->
            <footer class="main-footer">
                <div class="container">
                    <div class="pull-right hidden-xs">
                        <b>Version</b> 2.3.8
                    </div>
                    <strong>Copyright &copy; 2014-2016 <a href="http://almsaeedstudio.com">Almsaeed Studio</a>.</strong> All rights
                    reserved.
                </div>
                <!-- /.container -->
            </footer>
        </div>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
