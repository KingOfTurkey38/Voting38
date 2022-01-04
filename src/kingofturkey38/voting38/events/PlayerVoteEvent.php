<?php

declare(strict_types=1);

namespace kingofturkey38\voting38\events;

use pocketmine\event\player\PlayerEvent;
use pocketmine\player\Player;

class PlayerVoteEvent extends PlayerEvent{

	public function __construct(Player $player){
		$this->player = $player;
	}

	public function getPlayer() : Player{
		return $this->player;
	}
}