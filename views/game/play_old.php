<?php
/* @var $this yii\web\View */
use yii\grid\GridView;
use yii\bootstrap\Html;

$this->title = 'Server Games';
?>
<div class="site-index">
    
    <?= Html::a('Create New Game', ['game/create'], ['class' => 'btn btn-primary pull-right']); ?>
    <h2>Server Games</h2>
    
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
