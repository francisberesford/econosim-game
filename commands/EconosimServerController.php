<?php


namespace app\commands;

use Yii;
use yii\console\Controller;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use linslin\yii2\curl\Curl;
use app\commands\components\Server;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

class EconosimServerController extends Controller
{
    /**
     * @var \app\commands\components\Server An EconoSim Server
     */
    protected $Server;
    
    /**
     * Runs the RBEGame Server
     * @param integer $port The port number to run the server on
     * @param boolean $connectToRegistries Whether you want to connect one or more registry websites
     */
    public function actionRun($port=8080, $connectToRegistries=true)
    {
        $this->Server = new Server();
        
        if($connectToRegistries) 
        {
            $AuthClient = $this->connectToRegistries();
            if($AuthClient) {
                $Server->addAuthClient($AuthClient);
            }
        }
        $s = IoServer::factory(
            new HttpServer(
                new WsServer(
                    $this->Server
                )
            ), $port
        );
        $s->socket->on('end', [$this, 'cleanUp']);
        $this->Server->registerTimers($s);
        $s->run();
    }
    
    /**
     * Internal function to remove game names from server lists.
     */
    protected function cleanUp()
    {
        $this->Server->stop();
        echo "Cleaning Up. \n";
    }
    
    /**
     * Connect to EconoSim registry websites
     */
    protected function connectToRegistries()
    {
        $AuthClients = Yii::$app->esAuthClients->getClients();
        
        foreach($AuthClients as $AuthClient)
        {
            $parts = parse_url($AuthClient->tokenUrl);
            echo "Please provide your login details for the registry at " . $parts['host'] . "\n";

            $username = $this->prompt("Username:");
            $password = $this->prompt_silent("Password:");

            $url = $AuthClient->buildAuthUrl(['redirect_uri' => null, 'display_response' => 1]); // Build authorization URL

            $curl = new Curl;
            $response = $curl->setOption(
                CURLOPT_POSTFIELDS, 
                http_build_query([
                    'LoginForm' => [
                        'username' => $username,
                        'password' => $password
                    ]
                ]
            ))
            ->post($url);

            $arr = Json::decode($response);
            $success = ArrayHelper::getValue($arr, 'success');

            if($success)
            {
                $code = ArrayHelper::getValue($arr, 'data.code');
                $accessToken = $AuthClient->fetchAccessToken($code, ['redirect_uri' => null]); // Get access token
                $this->Server->addAuthClient($AuthClient);
            }
            else
            {
                echo ArrayHelper::getValue($arr, 'error.message');
            }
        }
    }
    
    /**
     * A "silent" prompt used for passwords
     * @param string $prompt prompt string
     * @return string
     */
    public function prompt_silent($prompt = "Password:")
    {
        if (preg_match('/^win/i', PHP_OS))
        {
            $vbscript = sys_get_temp_dir() . 'prompt_password.vbs';
            file_put_contents(
                    $vbscript, 'wscript.echo(InputBox("'
                    . addslashes($prompt)
                    . '", "", "password here"))');
            $command = "cscript //nologo " . escapeshellarg($vbscript);
            $password = rtrim(shell_exec($command));
            unlink($vbscript);
            return $password;
        }
        else
        {
            $command = "/usr/bin/env bash -c 'echo OK'";
            if (rtrim(shell_exec($command)) !== 'OK')
            {
                trigger_error("Can't invoke bash");
                return;
            }
            $command = "/usr/bin/env bash -c 'read -s -p \""
                    . addslashes($prompt)
                    . "\" mypassword && echo \$mypassword'";
            $password = rtrim(shell_exec($command));
            echo "\n";
            return $password;
        }
    }

}