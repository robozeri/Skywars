<?php

namespace mcg76\game\turfwars;

use mcg76\game\turfwars\TurfWarsPlugIn;
/**
 * MCPE TurfWarsPlugIn Minigame - Made by minecraftgenius76
 *
 * You're allowed to use for own usage only "as-is".
 * you're not allowed to republish or resell or for any commercial purpose.
 *
 * Thanks for your cooperate!
 *
 * Copyright (C) 2015 minecraftgenius76
 * YouTube Channel: http://www.youtube.com/user/minecraftgenius76
 *
 * @author minecraftgenius76
 *
 */
/**
 * MCG76 Mini-Game Base Class
 *
 */

abstract class MiniGameBase {		
	protected $plugin;
	public function __construct(TurfWarsPlugIn $plugin) {
		if($plugin === null){
			throw new \InvalidStateException("plugin may not be null");
		}
		$this->plugin = $plugin;
	}
	
	protected function getController() {
		return $this->getPlugin ()->controller;
	}
	protected function getPlugin() {
		return $this->plugin;
	}
	protected function getMsg($key) {
		return $this->plugin->messages->getMessageByKey ( $key );
	}
	protected function getSetup() {
		return $this->plugin->setup;
	}
	protected function getBuilder() {
		return $this->plugin->builder;
	}
	
	protected function getGameKit() {
		return $this->getPlugin()->gameKit;
	}
	
	protected function getProfileProvider() {
		return $this->plugin->profileprovider;		
	}
	
	protected function getLog() {
		return $this->plugin->getLogger();
	}
	
	protected function log($msg) {
		return $this->plugin->getLogger()->info($msg);
	}
	
	protected function getArenaManager() {
		return $this->getPlugin()->arenaManager;
	}
	protected function getConfig($key) {
		return $this->plugin->getConfig()->get($key);
	}
	
}
