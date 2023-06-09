<?php

declare(strict_types=1);

namespace kingofturkey38\voting38\threads;

use kingofturkey38\voting38\operations\BaseThreadedPlayerOperation;
use pmmp\thread\ThreadSafeArray;
use pocketmine\snooze\SleeperHandlerEntry;
use pocketmine\thread\Thread;

class VotingThread extends Thread{
	public function __construct(
		private SleeperHandlerEntry $sleeperEntry,
		private ThreadSafeArray $in,
		private ThreadSafeArray $out
	){
	}

	protected function onRun() : void{
		$notifier = $this->sleeperEntry->createNotifier();
		while(!$this->isKilled){
			while(is_string(($raw = $this->in->shift()))){
				$operation = igbinary_unserialize($raw);

				if($operation instanceof BaseThreadedPlayerOperation){
					$data = $operation->run();

					$this->out[] = igbinary_serialize([$operation->getIdentifier(), $data]);
					$notifier->wakeupSleeper();
				}
			}

			usleep(250000);
		}
	}
}