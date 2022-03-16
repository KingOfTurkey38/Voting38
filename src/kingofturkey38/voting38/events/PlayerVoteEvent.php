<?php

declare(strict_types=1);

namespace kingofturkey38\voting38\events;

use pocketmine\event\player\PlayerEvent;
use pocketmine\player\Player;

class PlayerVoteEvent extends PlayerEvent{

	private ?string $voteAnnouncement;

	private bool $giveRewards;

	public function __construct(Player $player, ?string $voteAnnouncement, bool $giveRewards = true){
		$this->player = $player;
		$this->voteAnnouncement = $voteAnnouncement;
		$this->giveRewards = $giveRewards;
	}

	public function getPlayer() : Player{
		return $this->player;
	}

	public function getVoteAnnouncement() : ?string{
		return $this->voteAnnouncement;
	}

	public function shouldGiveRewards() : bool{
		return $this->giveRewards;
	}

	public function setVoteAnnouncement(?string $voteAnnouncement) : void{
		$this->voteAnnouncement = $voteAnnouncement;
	}

	public function setGiveRewards(bool $giveRewards) : void{
		$this->giveRewards = $giveRewards;
	}
}