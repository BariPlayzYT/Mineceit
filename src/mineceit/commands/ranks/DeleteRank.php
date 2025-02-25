<?php

declare(strict_types=1);

namespace mineceit\commands\ranks;

use mineceit\commands\MineceitCommand;
use mineceit\MineceitCore;
use mineceit\MineceitUtil;
use mineceit\player\language\Language;
use mineceit\player\MineceitPlayer;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\utils\TextFormat;

class DeleteRank extends MineceitCommand{

	public function __construct(){
		parent::__construct('deleterank', 'Delete an existing rank.', 'Usage: /deleterank <name>', []);
		parent::setPermission('mineceit.permission.toggle-ranks');
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param string[]      $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args){
		$msg = null;

		$playerHandler = MineceitCore::getPlayerHandler();

		$language = $sender instanceof MineceitPlayer ? $sender->getLanguageInfo()->getLanguage() : $playerHandler->getLanguage();

		if($this->testPermission($sender) && $this->canUseCommand($sender)){

			$size = count($args);

			if($size === 1){

				$rankName = strval($args[0]);

				$rankHandler = MineceitCore::getRankHandler();

				$rank = $rankHandler->getRank($rankName);

				if($rank !== null){

					$rankHandler->removeRank($rankName);

					$msg = $language->rankMessage($rankName, Language::RANK_DELETE);
				}else $msg = $language->rankMessage($rankName, Language::RANK_NO_EXIST);
			}else $msg = $this->getUsage();
		}

		if($msg !== null) $sender->sendMessage(MineceitUtil::getPrefix() . ' ' . TextFormat::RESET . $msg);

		return true;
	}

	public function testPermission(CommandSender $sender) : bool{

		if($sender instanceof MineceitPlayer && $sender->hasOwnerPermissions()){
			return true;
		}

		return parent::testPermission($sender);
	}
}
