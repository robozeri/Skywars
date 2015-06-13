<?php 
//plugin made by Robozeri and Svile
namespace SkyWars;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;

class Main extends PluginBase implements CommandExecutor
{
 public $commands;
 public $arenaManager;
 public $gameKit;
 public $setup;
 public $controller; 
 public $profileprovider;
  public function onLoad() 
  {
        $this->commands = new SkyWarsCommand ( $this );
	$this->arenaManager = new SkyWarsArenaManager ( $this );
	$this->gameKit = new SkyWarsGameKit($this);
	$this->setup = new SKyWarsSetup($this);
	$this->profileprovider = new SKyWarsProvider ( $this );
	
  }
  public function onEnable
  {
  	$this->initConfigFile ();
		$this->enabled = true;
		$this->getServer ()->getPluginManager ()->registerEvents ( new SkyWarsWarsListener ( $this ), $this );
		
		$this->arenaManager->loadArenas ();
		
		$this->statueManager = new StatueManager( $this );
		$this->statueManager->loadStatues ();
		
		if ($this->profileprovider != null) {
			$this->profileprovider->initlize ();
  	$this->initScheduler();
		
		$this->log ( TextFormat::GREEN . "- SKyWars Enable -" );
  	
  }
  public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
		$this->commands->onCommand ( $sender, $command, $label, $args );
  public function onDisable
  {
                $this->log ( TextFormat::RED . "- SKyWars Disable -" ); 
  }
}
