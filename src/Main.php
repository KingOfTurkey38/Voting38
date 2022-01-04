<?php

declare(strict_types=1);

namespace kingofturkey38\voting38;

use Closure;
use kingofturkey38\voting38\commands\VoteCommand;
use kingofturkey38\voting38\events\PlayerVoteEvent;
use kingofturkey38\voting38\operations\BaseThreadedPlayerOperation;
use kingofturkey38\voting38\operations\vote\PlayerCheckVoteOperation;
use kingofturkey38\voting38\operations\vote\PlayerUpdateVoteOperation;
use kingofturkey38\voting38\storage\ClosureStorage;
use kingofturkey38\voting38\threads\VotingThread;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\snooze\SleeperNotifier;
use SOFe\AwaitGenerator\Await;
use SOFe\AwaitStd\AwaitStd;
use Threaded;

class Main extends PluginBase implements Listener{

	private VotingThread $thread;

	private Threaded $in;

	private Threaded $out;

	private string $key;

	private bool $autoclaim;

	private AwaitStd $std;

	protected function onEnable() : void{
		$this->saveDefaultConfig();

		$this->autoclaim = (bool) $this->getConfig()->get("autoclaim");
		$this->key = $this->getConfig()->get("key");

		if($this->key === ""){
			$this->getLogger()->emergency("No vote api key found in the config, disabling plugin");
			$this->getServer()->getPluginManager()->disablePlugin($this);
			return;
		}

		$this->std = AwaitStd::init($this);
		$this->thread = new VotingThread($notifier = new SleeperNotifier(), $this->out = new Threaded(), $this->in = new Threaded());
		Server::getInstance()->getTickSleeper()->addNotifier($notifier, Closure::fromCallable([$this, "onMessageFromThread"]));

		$this->thread->start();

		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getServer()->getCommandMap()->register("voting38", new VoteCommand($this));
	}

	public function onVote(PlayerVoteEvent $event) : void{
		$player = $event->getPlayer();

		$this->getServer()->broadcastMessage(str_replace("{username}", $player->getName(), $this->getConfig()->get("vote_announcement")));

		foreach($this->getConfig()->get("commands") as $v){
			$this->getServer()->dispatchCommand(new ConsoleCommandSender($this->getServer(), $this->getServer()->getLanguage()), str_replace("{username}", $player->getName(), $v));
		}
	}

	public function onJoin(PlayerJoinEvent $event) : void{
		if($this->autoclaim === true){
			$player = $event->getPlayer();

			Await::f2c(function() use ($player){
				while($player->isOnline() && $this->getServer()->isRunning()){
					$this->checkVote($player, false);

					yield $this->std->sleep(20 * 30);
				}
			});
		}
	}

	public function onMessageFromThread() : void{
		while(($raw = $this->in->shift())){
			$response = igbinary_unserialize($raw);
			$identifier = $response[0];
			$data = $response[1];

			ClosureStorage::executeClosure($identifier, $data);
		}
	}

	public function checkVote(Player $player, bool $sendMessage = true) : void{
		Await::f2c(function() use ($player, $sendMessage){
			$this->addOperation(new PlayerCheckVoteOperation($player->getName(), yield Await::RESOLVE, $this->key));
			$data = yield Await::ONCE;

			switch((int) $data){
				case 0:
					if($sendMessage){
						$player->sendMessage($this->getConfig()->get("message_not_voted"));
					}
					break;
				case 1:
					$this->addOperation(new PlayerUpdateVoteOperation($player->getName(), yield Await::RESOLVE, $this->key));
					$response = yield Await::ONCE;

					if(!$player->isOnline()){
						return;
					}

					if((int) $response === 0){
						$player->sendMessage("§cError happened while processing your vote.");
						return;
					}

					(new PlayerVoteEvent($player))->call();
					break;
				case 2:
					if($sendMessage){
						$player->sendMessage($this->getConfig()->get("message_voted"));
					}
					break;
			}
		});
	}

	public function addOperation(BaseThreadedPlayerOperation $operation) : void{
		$this->out[] = igbinary_serialize($operation);
	}
}
