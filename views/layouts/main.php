<?php
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
?>
<?php $this->beginContent('@app/views/layouts/minimal.php'); ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-left'],
                'encodeLabels' => false,
                'items' => [
                    '<li><a href="#"><span class="fa fa-circle text-danger" title="Disconnected"></span></a></li>',
                    ['label' => '<span class="fa fa-gamepad"></span> All Games', 'url' => $this->params['main_registry_url'], 'visible' => $this->params['main_registry_url']],
                    ['label' => '<span class="fa fa-gamepad"></span> Server Games', 'url' => ['/game/index']],
                    ['label' => '<span class="fa fa-paint-brush"></span> Design', 'url' => ['/game/design-city']],
                ],
            ]);
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'encodeLabels' => false,
                'items'   => [
                    Yii::$app->user->isGuest ?
                        ['label' => 'Login', 'url' => ['/site/login']] :
                        [
                            'label' => '<span class="glyphicon glyphicon-user"></span> '. Yii::$app->user->identity->username, 'url' => '#',
                            'items' => [
                                ['label' => '<span class="glyphicon glyphicon-user"></span> Account', 'url' => ['account/index']],
                                '<li class="divider"></li>',
                                ['label' => '<span class="fa fa-power-off"></span> Logout<br /><small class="text-muted">' . Yii::$app->request->hostInfo . '</small>',
                                    'url' => ['/site/logout'],
                                    'linkOptions' => ['data-method' => 'post']],
                            ],
                        ],
                ]
            ]);
            NavBar::end();
        ?>
        <div class="container">
            <?php Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    </div>
    <footer class="footer">
        <div class="container">
            <p class="pull-left">EconoSim Game</p>
        </div>
    </footer>
<?php $this->endContent(); ?>
