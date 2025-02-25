<?php

declare(strict_types=1);

namespace mineceit\commands\other;

use mineceit\player\MineceitPlayer;
use pocketmine\command\CommandSender;
use pocketmine\command\defaults\TeleportCommand;

class MineceitTeleportCommand extends TeleportCommand{

	public function testPermission(CommandSender $target) : bool{

		if($target instanceof MineceitPlayer && $target->hasHelperPermissions()){
			return true;
		}

		return parent::testPermission($target);
	}
}
