<?php
namespace app\commands\components;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use yii\base\Component;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use econosim\game\Game;
use yii\authclient\OAuth2;

/**
 * Server runs multiple EconoSim games
 *
 * @author Francis Beresford <francis.beresford@gmail.com>
 */
class Server extends Component implements MessageComponentInterface
{
    /**
     * @var array of \yii\authclient\OAuth2 objects which connect to 
     * and update EconoSim registry websites
     */
    protected $_AuthClients = [];
    
    /**
     * @var \SplObjectStorage to hold connection objects
     */
    protected $clients;
    
    /**
     * @var array of \econosim\game\Game objects which the simulation is currently running
     */
    protected $Games;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    /**
     * Process a new client connecting to the server
     * 
     * @param ConnectionInterface $client connection object
     */
    public function onOpen(ConnectionInterface $client)
    {
        $this->clients->attach($client);
        $this->initClient($client);
        
        echo "New connection! ({$client->resourceId})\n";
    }

    /**
     * Process a message and run a requested function from a client
     * 
     * @param ConnectionInterface $client connection object
     * @param string $msg json message recieved from a client
     */
    public function onMessage(ConnectionInterface $client, $msg)
    {
        $obj = json_decode($msg);
        $method = $obj->method;
        $params = ArrayHelper::toArray($obj->data);
        array_unshift($params, $client);
        call_user_func_array([$this, $method], $params);
    }

    /**
     * Process a client that disconnects
     * 
     * @param ConnectionInterface $client connection object
     */
    public function onClose(ConnectionInterface $client)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($client);
        echo "Connection {$client->resourceId} has disconnected\n";
    }

    /**
     * Process an error
     * 
     * @param ConnectionInterface $conn connection object
     * @param \Exception $e the Exception thrown
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
    
    /**
     * Register the functions to be called at various intervals
     * 
     * @param IoServer $IoServer
     */
    public function registerTimers(IoServer $IoServer)
    {
        $IoServer->loop->addPeriodicTimer(5, [$this, 'tick5']);
    }
    
    /**
     * Functions to be called every 5 seconds
     */
    public function tick5()
    {
        $time_start = microtime(true);
        $this->checkForNewGames();
        
        foreach ($this->Games as $Game) {
            $Game->dayTick();
        }
        
        //Fork the update games function because it takes time
        //and we don't want it affecting the speed of the simulation
        if(function_exists('pcntl_fork'))
        {
            $pid = pcntl_fork();
            if(!$pid) {
                $this->updateGames();
                $sid = posix_setsid();
                posix_kill($pid, SIGINT); //we don't want have a second server instance continuing to run!
            }
        }
        else {
            $this->updateGames();
        }
        
        $time = microtime(true) - $time_start;
        echo "Tick took $time seconds\n";
    }
    
    /**
     * Checks if any new games have been created and adds them to the processing list
     */
    public function checkForNewGames()
    {
        $Games = \app\models\Game::find()->all();
        foreach($Games as $Game)
        {
            if(!isset($this->Games[$Game->id])) {
                $this->Games[$Game->id] = new Game($Game->name);
            }
        }
    }
    
    /**
     * Adds an AuthClient object which connects to an EconoSim registry to send
     * game information updates
     * 
     * @param OAuth2 $AuthClient
     */
    public function addAuthClient(OAuth2 $AuthClient)
    {
        $this->_AuthClients[] = $AuthClient;
    }
    
    /**
     * Update registries with game information
     * 
     * @return boolean whether the update was successful or not
     */
    public function updateGames()
    {
        if(empty($this->_AuthClients)) {
            return false;
        }

        $arr = [];
        foreach($this->Games as $id => $Game)
        {
            if($Game->is_public) 
            {
                $arr[] = [
                    'id' => $id,
                    'launch_url' => Url::to(['game/play', 'id' => $id]),
                    'name' => $Game->name,
                ];
            }
        }
        
        foreach($this->_AuthClients as $AC)
        {
            $resp = $AC->api('update-game', 'POST', [
                'Games' => $arr,
            ]);
        }
        return true;
    }
    
    /**
     * Remove all games from the database when the server has stopped
     */
    public function stop()
    {
        
    }
}
