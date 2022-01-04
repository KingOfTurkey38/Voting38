<?php

declare(strict_types=1);

namespace kingofturkey38\voting38\threads;

use kingofturkey38\voting38\operations\BaseThreadedPlayerOperation;
use pocketmine\snooze\SleeperNotifier;
use pocketmine\thread\Thread;
use Threaded;

class VotingThread extends Thread{
	public function __construct(
		private SleeperNotifier $notifier,
		private Threaded $in,
		private Threaded $out
	){
	}

	protected function onRun() : void{
		while(!$this->isKilled){
			while(is_string(($raw = $this->in->shift()))){
				$operation = igbinary_unserialize($raw);

				if($operation instanceof BaseThreadedPlayerOperation){
					$data = $operation->run();

					$this->out[] = igbinary_serialize([$operation->getIdentifier(), $data]);
					$this->notifier->wakeupSleeper();
				}
			}

			usleep(250000);
		}
	}
}