<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
$regUrl = Yii::$app->view->params['main_registry_url'];
?>
<?php $this->beginContent('@app/views/layouts/minimal.php'); ?>
<nav class="navbar-inverse navbar navbar-static-top" role="navigation">
    <div class="container" id="menu-bar">
        <?=
        Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-left'],
            'encodeLabels' => false,
            'items'   => [
                '<li><a href="#"><span class="fa fa-circle text-danger" title="Disconnected"></span></a></li>',
                [
                    'label' => '<span class="fa fa-gamepad"></span> Game', 'url' => '#',
                    'items' => [
                        ['label' => '<span class="fa fa-save"></span> Save', 'url' => '#'],
                        '<li class="divider"></li>',
                        ['label' => '<span class="fa fa-power-off"></span> Quit', 'url' => $regUrl ? $regUrl : ['game/index']],
                    ]
                ],
                [
                    'label' => '<span class="fa fa-building"></span> City', 'url' => '#',
                    'items' => [
                        ['label' => '<span class="fa fa-home"></span> Current City', 'url' => '#', 'linkOptions' => [
                            //'data-bind' => 'click: showPanel.bind($data, \'panel-city-selector\')',
                        ]],
                        ['label' => '<span class="fa fa-building"></span> My Cities', 'url' => '#', 'linkOptions' => [
                            'data-bind' => 'click: showPanel.bind($data, \'panel-my-cities\')',
                        ]],
                        ['label' => '<span class="fa fa-globe"></span> All Cities', 'url' => '#', 'linkOptions' => [
                            //'data-bind' => 'click: showPanel.bind($data, \'panel-city-selector\')',
                        ]],
                        '<li class="divider"></li>',
                        ['label' => '<span class="fa fa-home"></span> Found New City', 'url' => '#', 'linkOptions' => [
                            'data-bind' => 'click: showPanel.bind($data, \'panel-city-selector\')',
                        ]],
                    ]
                ],
                [
                    'label' => '<span class="fa fa-wrench"></span> Build', 'url' => '#',
                    'items' => [
                        ['label' => '<span class="fa fa-home"></span> Residence', 'url' => '#', 'linkOptions' => [
                            //'data-bind' => 'click: showPanel.bind($data, \'panel-city-selector\')',
                        ]],
                        ['label' => '<span class="fa fa-briefcase"></span> Factory', 'url' => '#', 'linkOptions' => [
                            //'data-bind' => 'click: showPanel.bind($data, \'panel-city-selector\')',
                        ]],
                        ['label' => '<span class="fa fa-university"></span> Facility', 'url' => '#', 'linkOptions' => [
                            //'data-bind' => 'click: showPanel.bind($data, \'panel-city-selector\')',
                        ]],
                    ]
                ],
            ],
        ]);
        ?>
        <?= Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'encodeLabels' => false,
            'items'   => [
                [
                    'label' => '<span class="glyphicon glyphicon-user"></span> '. Yii::$app->user->identity->username, 'url' => '#',
                    'items' => [
                        ['label' => '<span class="glyphicon glyphicon-user"></span> My Info', 'url' => '#', 'linkOptions' => [
                            //'data-bind' => 'click: showCitySelector'
                        ]],
                        '<li class="divider"></li>',
                        ['label' => '<span class="fa fa-power-off"></span> Logout<br /><small class="text-muted">' . Yii::$app->request->hostInfo . '</small>',
                            'url' => ['/site/logout'],
                            'linkOptions' => ['data-method' => 'post']],
                    ],
                ],
            ]
        ]); ?>
    </div>
</nav>
<div class="container-fluid game-wrap">
    <?php echo $content; ?>
</div>
<?php $this->endContent(); ?>