<?php

declare(strict_types=1);

namespace kingofturkey38\voting38\commands;

use kingofturkey38\voting38\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

class VoteCommand extends Command implements PluginOwned{

	public function __construct(private Main $plugin){
		parent::__construct("vote", "Claim your vote");
	}


	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if($sender instanceof Player){
			$this->plugin->checkVote($sender, true);
		}else $sender->sendMessage("§cOnly players can use this!");
	}

	public function getOwningPlugin() : Plugin{
		return $this->plugin;
	}
}