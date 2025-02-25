<?php

declare(strict_types=1);

namespace mineceit\duels\players;

use mineceit\player\MineceitPlayer;

class QueuedPlayer{

	/* @var string */
	private $queue;

	/* @var bool */
	private $ranked;

	/* @var MineceitPlayer */
	private $player;

	/* @var bool */
	private $peOnly;

	public function __construct(MineceitPlayer $player, string $queue, bool $ranked = false){
		$this->ranked = $ranked;
		$this->queue = $queue;
		$this->player = $player;
		$this->peOnly = $player->getSettingsInfo()->hasPEOnlyQueues();
	}

	/**
	 * @return bool
	 */
	public function isPeOnly() : bool{
		return $this->peOnly;
	}

	/**
	 * @return MineceitPlayer
	 */
	public function getPlayer() : MineceitPlayer{
		return $this->player;
	}

	/**
	 * @return bool
	 */
	public function isRanked() : bool{
		return $this->ranked;
	}

	/**
	 * @return string
	 */
	public function getQueue() : string{
		return $this->queue;
	}
}
