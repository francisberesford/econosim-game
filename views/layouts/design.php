<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
?>
<?php $this->beginContent('@app/views/layouts/minimal.php'); ?>
<nav class="navbar-inverse navbar navbar-fixed-top" role="navigation">
    <div class="container" id="menu-bar">
        <?php
        echo Nav::widget([
            'options' => ['id' => 'server-status', 'class' => 'navbar-nav navbar-left'],
            'encodeLabels' => false,
            'items'   => [
                '<li><a href="#"><span class="fa fa-circle text-danger" title="Disconnected"></span></a></li>'
            ],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-left'],
            'encodeLabels' => false,
            'items'   => [
                [
                    'label' => '<span class="fa fa-gamepad"></span> Game', 'url' => '#',
                    'items' => [
                        ['label' => '<span class="fa fa-save"></span> Save', 'url' => '#'],
                        '<li class="divider"></li>',
                        ['label' => '<span class="fa fa-power-off"></span> Quit', 'url' => ['game/index']],
                    ]
                ],
            ],
        ]);
        echo Nav::widget([
            'options' => ['id' => 'dynamic-menu', 'class' => 'navbar-nav navbar-left'],
            'encodeLabels' => false,
            'items'   => [
                
            ],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'encodeLabels' => false,
            'items'   => [
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
        ?>
    </div>
</nav>
<div class="container-fluid full-height game-wrap">
    <?php echo $content; ?>
</div>
<?php $this->endContent(); ?>