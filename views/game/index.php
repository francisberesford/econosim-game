<?php
/* @var $this yii\web\View */
use yii\grid\GridView;
use yii\bootstrap\Html;

$this->title = 'Server Games';
?>
<div class="site-index">
    
    <div class="page-header">
        <?= Html::a('Create New Game', ['game/create'], ['class' => 'btn btn-primary pull-right']); ?>
        <h1><span class="fa fa-gamepad"></span> Server Games</h1>
    </div>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function($Game) {
                    return Html::a($Game->name, ['game/play', 'id' => $Game->id]);
                }
            ],

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    
</div>
