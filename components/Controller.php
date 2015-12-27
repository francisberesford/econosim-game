<?php
namespace app\components;

use Yii;
use yii\authclient\Collection;
use yii\helpers\ArrayHelper;

/**
 * EconoSim Base Controller
 */
class Controller extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) 
        {
            Yii::$app->view->params['main_registry_url'] = false;
            //Get the first auth client which is the main one.
            $AuthClient = ArrayHelper::getValue(array_values(Yii::$app->esAuthClients->getClients()), 0);
            if($AuthClient)
            {
                $parts = parse_url($AuthClient->tokenUrl);
                Yii::$app->view->params['main_registry_url'] = $parts['scheme'] . '://' . $parts['host'];
            }
            return true;
        }
        
        return false;
    }
    
    public function goHome()
    {
        $mainUrl = Yii::$app->view->params['main_registry_url'];
        $homeUrl = $mainUrl ? $mainUrl : Yii::$app->getHomeUrl();
        return Yii::$app->getResponse()->redirect($homeUrl);
    }
}
