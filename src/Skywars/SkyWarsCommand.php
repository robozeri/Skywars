<?php

namespace SkyWars;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\level\Position;
use pocketmine\level\Level;
use pocketmine\level\Explosion;
use pocketmine\event\block\BlockEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityMoveEvent;
use pocketmine\event\entity\EntityMotionEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\math\Vector3 as Vector3;
use pocketmine\math\Vector2 as Vector2;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\network\protocol\AddMobPacket;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\network\protocol\UpdateBlockPacket;
use pocketmine\block\Block;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\protocol\DataPacket;
use pocketmine\network\protocol\Info;
use pocketmine\network\protocol\LoginPacket;
use pocketmine\entity\FallingBlock;
use pocketmine\command\defaults\TeleportCommand;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\item\ItemBlock;
use pocketmine\item\Item;
class SkyWarsCommand extends MiniGamesBase 
{
public function __construct(TurfWarsPlugIn $plugin) {
		parent::__construct ( $plugin );
	}
	
	/**
	 * onCommand
	 *
	 * @param CommandSender $sender        	
	 * @param Command $command        	
	 * @param unknown $label        	
	 * @param array $args        	
	 * @return boolean
	 */
	public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
		// check command names
		if (((strtolower ( $command->getName () ) == "skywars" || strtolower ( $command->getName () ) == "sw")) && isset ( $args [0] )) {
			try {
				$output = "";
				if (! $sender instanceof Player) {
					$output .= "Commands only available in-game play.\n";
					$sender->sendMessage ( $output );
					return;
				}
				/**
				particle /skywars particle or /sw particle 
				
				
				*/
				if (strtolower ( $args [0] ) == "particle") {
					if (count ( $args ) != 2) {
						$output = "[SkyWars] Usage: /sw particle [particle name].\n";
						$sender->sendMessage ( $output );
						return;
					}
					if ($sender instanceof Player) {
						if ($args [1] == "clear") {
							unset ( $this->plugin->playerParticles [$sender->getName ()] );
							$output = "[SkyWars] Removed player particles!]\n";
						} else {
							$particle = MagicUtil::getParticle ( $args [1], $sender->getPosition (), 1, 1, 1, null );
							if ($particle == null) {
								$output = "[SkyWars]particle name [" . $args [1] . "] not found! \n";
								$sender->sendMessage ( $output );
								return;
							}
							$this->plugin->playerParticles [$sender->getName ()] = $args [1];
							$output = "[SkyWars] Player set to [" . $args [1] . "]\n";
							$sender->sendMessage ( $output );
						}
						return;
					}
				}
				
				
				
				
				
		   
				if (strtolower ( $args [0] ) == "list") {
					foreach ( $this->getPlugin ()->playArenas as $arena ) {
						$sender->sendMessage ( $arena->name );
					}
				}
				// $playerParticles
				
				if (strtolower ( $args [0] ) == "newarena") {
					if (! $sender->isOp ()) {
						$sender->sendMessage ( "You are not authorized to use this command!" );
						return;
					}
					if (! ($sender instanceof Player)) {
						$output .= "Please run this command in-game.\n";
						$sender->sendMessage ( $output );
						return;
					}
					$this->getPlugin ()->arenaManager->createArena ( $sender->getPlayer (), $args );
					return;
				}
				
				if (strtolower ( $args [0] ) == "arenawand") {
					if (! $sender->isOp ()) {
						$sender->sendMessage ( "You are not authorized to use this command!" );
						return;
					}
					if (! ($sender instanceof Player)) {
						$output .= "Please run this command in-game.\n";
						$sender->sendMessage ( $output );
						return;
					}
					$this->getPlugin ()->arenaManager->handleWandCommand ( $sender->getPlayer (), $args );
					return;
				}
				
				////////////////////////////////////////////////////////////////////////////////////////
				
				
				
				if (strtolower ( $args [0] ) == "clear") {
					if (! $sender->isOp ()) {
						$sender->sendMessage ( "You are not authorized to use this command!" );
						return;
					}
					if (! ($sender instanceof Player)) {
						$output .= "Please run this command in-game.\n";
						$sender->sendMessage ( $output );
						return;
					}
					$this->getPlugin ()->arenaManager->handleDeSelCommand ( $sender );
					return;
				}
				
				if (strtolower ( $args [0] ) == "setlobby") {
					if (! $sender->isOp ()) {
						$sender->sendMessage ( "You are not authorized to use this command!" );
						return;
					}
					if (! ($sender instanceof Player)) {
						$output .= "Please run this command in-game.\n";
						$sender->sendMessage ( $output );
						return;
					}
					$this->getPlugin ()->arenaManager->handleSetLobbyCommand ( $sender, $args );
					return;
				}
				
				if (strtolower ( $args [0] ) == "setserverlobby") {
					if (! $sender->isOp ()) {
						$sender->sendMessage ( "You are not authorized to use this command!" );
						return;
					}
					if (! ($sender instanceof Player)) {
						$output .= "Please run this command in-game.\n";
						$sender->sendMessage ( $output );
						return;
					}
					$this->getPlugin ()->arenaManager->handleSetServerLobbyCommand ( $sender, $args );
					return;
				}
				
				
				
			////////////////////////////////////////////////////////////////////////////////////////
				
				if (strtolower ( $args [0] ) == "setwall") {
					if (! $sender->isOp ()) {
						$sender->sendMessage ( "You are not authorized to use this command!" );
						return;
					}
					if (! ($sender instanceof Player)) {
						$output .= "Please run this command in-game.\n";
						$sender->sendMessage ( $output );
						return;
					}
					$this->getPlugin ()->arenaManager->handleSetWallsCommand ( $sender->getPlayer (), $args );
					return;
				}
				
				if (strtolower ( $args [0] ) == "blockon") {
					if (! $sender->isOp ()) {
						$sender->sendMessage ( "* You are not authorized to use this command!*" );
						return;
					}
					$this->getPlugin ()->pos_display_flag = 1;
					$sender->sendMessage ( "[SkyWars] block xyz" );
					return;
				}
				
				if (strtolower ( $args [0] ) == "blockoff") {
					if (! $sender->isOp ()) {
						$sender->sendMessage ( "* You are not authorized to use this command!*" );
						return;
					}
					$this->getPlugin ()->pos_display_flag = 0;
					$sender->sendMessage ( "[SkyWars] block xyz" );
					return;
				}
				
				
				
				if (strtolower ( $args [0] ) == "setbalance") {
					if (! $sender->isOp ()) {
						$output = "You are not authorized to run this command.\n";
						$sender->sendMessage ( $output );
						return;
					}
					if (count ( $args ) != 3) {
						$sender->sendMessage ( "usage: /skywars setbalance [player name] [amount]" );
						return;
					}
					$rs = $this->getPlugin ()->profileprovider->setBalance ( $args [1], $args [2] );
					$sender->sendMessage ( "[SkyWaers] balance updated! " . $rs );
					return;
				}
				
				if (strtolower ( $args [0] ) == "addvip") {
					if (! $sender->isOp ()) {
						$output = "You are not authorized to run this command.\n";
						$sender->sendMessage ( $output );
						return;
					}
					if (count ( $args ) != 2) {
						$sender->sendMessage ( "usage: /skywars addvip [player name]" );
						return;
					}
					$rs = $this->getPlugin ()->profileprovider->upsetVIP ( $sender->getName (), "true" );
					$sender->sendMessage ( "[SkyWars] vip added! " . $rs );
					return;
				}
				
				if (strtolower ( $args [0] ) == "delvip") {
					if (! $sender->isOp ()) {
						$output = "You are not authorized to run this command.\n";
						$sender->sendMessage ( $output );
						return;
					}
					if (count ( $args ) != 2) {
						$sender->sendMessage ( "usage: /SkyWars delvip [player name]" );
						return;
					}
					$rs = $this->getPlugin ()->profileprovider->upsetVIP ( $sender->getName (), "false" );
					$sender->sendMessage ( "[SkyWars] vip delete! " . $rs );
					return;
				}
				
				if (strtolower ( $args [0] ) == "createprofile") {
					if (! $sender->isOp ()) {
						$output = "You are not authorized to run this command.\n";
						$sender->sendMessage ( $output );
						return;
					}
					if (count ( $args ) != 2) {
						$sender->sendMessage ( "usage: /skywars createprofile [player name]" );
						return;
					}
					$rs = $this->getPlugin ()->profileprovider->addPlayer ( $args [1] );
					$sender->sendMessage ( "[SkyWars] player profile created! " . $rs );
					return;
				}
				
				if (strtolower ( $args [0] ) == "setvip") {
					if (! $sender->isOp ()) {
						$output = "You are not authorized to run this command.\n";
						$sender->sendMessage ( $output );
						return;
					}
					// true 1  false 2    
					if (count ( $args ) != 3) {
						$sender->sendMessage ( "usage: /SkyWars setvip [player name] [true|false] \n es. /SkyWars setvip Mario 1 \n" );
						return;
					}
					$rs = $this->getPlugin ()->profileprovider->upsetVIP ( $sender->getName (), "false" );
					$sender->sendMessage ( "[SkyWars] vip setted! " . $rs );
					return;
				}
				
				if (strtolower ( $args [0] ) == "leave") {
					$this->getPlugin ()->controller->handlePlayerLeavethePlay ( $sender );
					return;
				}
				
				if (strtolower ( $args [0] ) == "home") {
					$sender->teleport ( $this->getPlugin ()->setup->getHomeWorldPos () );
					return;
				}
				
				if (strtolower ( $args [0] ) == "xyz") {
					if ($sender instanceof Player) {
						$portalLevel = $sender->level->getName ();
						$sender->sendMessage ( "You are in world [" . $portalLevel . "] at [" . round ( $sender->x ) . " " . round ( $sender->y ) . " " . round ( $sender->z ) . "]" );
					}
					return;
				}
				
				// cmmands player comandi attenzione molti errori ancora de verificare con supporto forse EconomyApi
				if (strtolower ( $args [0] ) == "stats") {
					if (! ($sender instanceof Player)) {
						$output .= "Please run this command in-game.\n";
						$sender->sendMessage ( $output );
						return;
					}
					$data = $this->getPlugin ()->profileprovider->retrievePlayerStats ( $sender->getName () );
					if (count ( $data ) > 0) {
						$output = "[SkyWars] My stats>\n";
						$output .= "[SkyWars]  Wins: " . $data [0] ["wins"] . "\n";
						$output .= "[SkyWars]  Loss: " . $data [0] ["loss"] . "\n";
						$sender->sendMessage ( $output );
					} else {
						$sender->sendMessage ( "[SkyWars] Error!  You dont have win or loss match!" );
					}
					return;
				}
				// ERROR ECONOMY API UPDATE
				if (strtolower ( $args [0] ) == "balance") {
					$data = $this->getPlugin ()->profileprovider->retrievePlayerBalance ( $sender->getName () );
					if ($data == null || count ( $data ) == 0) {
						$sender->sendMessage ( "[SkyWars] No profile found " );
					} else {
						$sender->sendMessage ( "[SkyWars] My balance is " . $data [0] ["balance"] . " coins." );
					}
					return;
				}
				
				if (strtolower ( $args [0] ) == "profile") {
					$data = $this->getPlugin ()->profileprovider->retrievePlayerByName ( $sender->getName () );
					if ($data == null || count ( $data ) == 0) {
						$output = "[SkyWars] No profile found";
					} else {
						$output = "";
						$output .= "[SkyWars] My Profile> \n";
						$output .= "[SkyWars]   balance: " . $data [0] ["balance"] . "\n";
						$output .= "[SkyWars]      wins: " . $data [0] ["wins"] . "\n";
						$output .= "[SkyWars]      loss: " . $data [0] ["loss"] . "\n";
						$output .= "[SkyWars]       vip: " . $data [0] ["vip"] . "\n";
					}
					$sender->sendMessage ( $output );
					return;
				}
				
				if (strtolower ( $args [0] ) == "help") {
					$output = "\nSkyWars Plugin || Server Sky-Pocket\n";
					$output .= "------------------------------------\n";
					$output .= "/sw profile - player view profile\n";
					$output .= "/sw stats   - player view wins|loss stats\n";
					$output .= "/sw balance - player view balances\n";
					$output .= "/sw leaves  - player leaves the game\n";
					$output .= "/sw xyz     - tell player current location\n";
					$output .= "/sw home    - player return home\n";
					$sender->sendMessage ( $output );
				}
				
				if (strtolower ( $args [0] ) == "adminprofile") {
					$output = "\n SkyWars Plugin || Server Sky-Pocket || Admin Command \n";
					$output .= "------------------------------------\n";
					$output .= "/sw addvip [name] - admin add VIP player as VIP\n";
					$output .= "/sw delvip [name] - admin change VIP status\n";
					$output .= "/sw setbalance [name] -- admin set player coin balance\n";
					$output .= "/sw createprofile [name] -- admin create a player profile\n";
					$sender->sendMessage ( $output );
				}
				// Questo comando non funziona alla perfezione ditabile dal config di ogni arena
				if (strtolower ( $args [0] ) == "adminsetup") {
					$output = "\n  SkyWars Plugin || Server Sky-Pocket || Admin Command Setup \n";
					$output .= "------------------------------------\n";
					$output .= "/sw newarena [name] - create new arena\n";
					$output .= "/sw arenawand [arena name]        - use wand to select arena position\n";
				 
					
					$output .= "/sw clear                         - clear current selection\n";
					$output .= "/sw setlobby [arena name]         - add arena lobby, by break it\n";
					
					$output .= "/sw blockon           - handy command, turn on block location display\n";
					$output .= "/sw blockoff          - handy command, turn off block location display\n";
					$output .= "/sw xyz               - display player current location.\n";
					$sender->sendMessage ( $output );
				}
			} catch ( \Exception $e ) {
				$this->log ( " please reinstall plugin or report bug on github site " . $e->__toString () );
			}
		}
	}
}
}
