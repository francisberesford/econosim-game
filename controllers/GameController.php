<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\components\Controller;
use app\models\Game;
use app\models\search\GameSearch;

class GameController extends Controller
{
    public $layout = 'main';
    
    public function beforeAction($action)
    {
        Yii::$app->session["_last_game"] = Yii::$app->request->absoluteUrl;
        return parent::beforeAction($action);
    }
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['play', 'design-city'],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new GameSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
    * Creates a new Game model.
    * If creation is successful, the browser will be redirected to the 'view' page.
    * @return mixed
    */
   public function actionCreate()
   {
       $Game = new Game();
       if ($Game->load(Yii::$app->request->post()) && $Game->save()) {
           return $this->redirect(['game/index']);
       } else {
           return $this->render('create', [
               'Game' => $Game,
           ]);
       }
   }
    
    public function actionPlay($id)
    {
        $Game = Game::findOne($id);
        $this->layout = 'game';
        return $this->render('play', [
            'Game' => $Game,
            'User' => Yii::$app->user->identity,
        ]);
    }
    
    public function actionDesignCity()
    {
        $this->layout = 'design';
        //$wsUrl = Yii::$app->params['EconoSim']['defaultWebSocketUrl'];
        $wsUrl = false;
        
        return $this->render('design_city', [
            'User' => Yii::$app->user->identity,
            'wsUrl' => $wsUrl,
        ]);
    }
    
    public function actionDesignOld()
    {
        $set = RBEGame::getSet('venus');
        $this->layout = 'design';
        return $this->render('design_old', [
            'User' => Yii::$app->user->identity,
            'set' => $set,
        ]);
    }
    
    //@todo: make this a proper REST web service
    public function actionRegister($gameName, $port, $hostname=null)
    {
        $ip = ip2long(Yii::$app->request->userIP);
        $Game = Game::find()->where([
            'ip_address' => $ip,
            'port' => $port,
        ])->one();
        
        if(!$Game)
        {
            $Game = new Game;
            $Game->name = $gameName;
            $Game->ip_address = $ip;
            $Game->port = $port;
        }
        return $Game->save();
    }
}
