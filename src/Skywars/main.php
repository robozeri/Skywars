<?php 
//plugin made by Robozeri and Svile
namespace SkyWars;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
class Main extends PluginBase implements Listener
{
 public $commands;
 public $arenaManager;
 public $gameKit;
 public $setup;
 public $controller; 
 public $profileprovider;
  
  public function onEnable
  {
    public function onLoad() {
		$this->commands = new SkyWarsCommand ( $this );
		$this->arenaManager = new SkyWarsManager ( $this );
		$this->gameKit = new SkyWarsGameKit($this);
		$this->setup = new SKyWarsSetup($this);
		$this->profileprovider = new SKyWarsProvider ( $this );
	}
  }
  public function onDisable
  {
    
  }
}
