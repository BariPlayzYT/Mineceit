<?php

declare(strict_types=1);

namespace mineceit\arenas;

use mineceit\player\MineceitPlayer;
use pocketmine\math\Vector3;

abstract class Arena{

	const TYPE_FFA = 0;
	const TYPE_EVENT = 1;
	const TYPE_DUEL = 2;
	const TYPE_GAMES = 3;

	/**
	 * @param string $arenaName
	 * @param array  $array
	 *
	 * @return Arena|null
	 */
	public static function parseArena(string $arenaName, array $array){

		$result = null;

		$type = self::TYPE_FFA;

		if(isset($array['type'])){
			$type = (int) $array['type'];
		}

		if($type === self::TYPE_FFA){

			if(isset($array['kit'], $array['center'], $array['level'], $array['interrupt'])){

				$kit = strval($array['kit']);
				$interrupt = (bool) ($array['interrupt']);
				$centerData = $array['center'];
				$center = null;
				$level = strval($array['level']);

				$spawnsData[1] = $centerData;
				if(isset($array['spawns'])){
					$spawnsData = $array['spawns'];
				}

				if(isset($centerData['x'], $centerData['y'], $centerData['z'])){
					$x = intval($centerData['x']);
					$y = intval($centerData['y']);
					$z = intval($centerData['z']);
					$center = new Vector3($x, $y, $z);
				}

				$spawns = [];
				foreach($spawnsData as $spawn => $pos){
					$x = intval($pos['x']);
					$y = intval($pos['y']);
					$z = intval($pos['z']);
					$spawns[$spawn] = new Vector3($x, $y, $z);
				}

				if($center !== null){
					$result = new FFAArena($arenaName, $center, $spawns, $level, $kit, $interrupt);
				}
			}
		}elseif($type === self::TYPE_EVENT){

			if(isset($array['kit'], $array['center'], $array['spawn'], $array['p1'], $array['p2'], $array['level'])){

				$kit = strval($array['kit']);
				$level = strval($array['level']);

				$vec3Data = ['center', 'spawn', 'p1', 'p2'];
				$vec3Result = [];
				foreach($vec3Data as $key){
					$data = $array[$key];
					if(isset($data['x'], $data['y'], $data['z'])){
						$x = intval($data['x']);
						$y = intval($data['y']);
						$z = intval($data['z']);
						$vec3 = new Vector3($x, $y, $z);
						$vec3Result[$key] = $vec3;
					}
				}

				if(array_keys($vec3Result) === $vec3Data){
					$result = new EventArena($arenaName, $vec3Result['center'], $level, $kit, $vec3Result['p1'], $vec3Result['p2'], $vec3Result['spawn']);
				}
			}
		}elseif($type === self::TYPE_DUEL){

			if(isset($array['kit'], $array['center'], $array['p1'], $array['p2'], $array['level'])){

				$kit = $array['kit'];
				$level = strval($array['level']);

				$vec3Data = ['center', 'p1', 'p2'];
				$vec3Result = [];
				foreach($vec3Data as $key){
					$data = $array[$key];
					if(isset($data['x'], $data['y'], $data['z'])){
						$x = intval($data['x']);
						$y = intval($data['y']);
						$z = intval($data['z']);
						$vec3 = new Vector3($x, $y, $z);
						$vec3Result[$key] = $vec3;
					}
				}

				if(array_keys($vec3Result) === $vec3Data){
					$result = new DuelArena($arenaName, $vec3Result['center'], $level, $kit, $vec3Result['p1'], $vec3Result['p2']);
				}
			}
		}elseif($type === self::TYPE_GAMES){

			if(isset($array['kit'], $array['center'], $array['level'])){

				$kit = strval($array['kit']);
				$centerData = $array['center'];
				$center = null;
				$level = strval($array['level']);

				$spawnsData[1] = $centerData;
				if(isset($array['spawns'])){
					$spawnsData = $array['spawns'];
				}

				if(isset($centerData['x'], $centerData['y'], $centerData['z'])){
					$x = intval($centerData['x']);
					$y = intval($centerData['y']);
					$z = intval($centerData['z']);
					$center = new Vector3($x, $y, $z);
				}

				$spawns = [];
				foreach($spawnsData as $spawn => $pos){
					$x = intval($pos['x']);
					$y = intval($pos['y']);
					$z = intval($pos['z']);
					$spawns[$spawn] = new Vector3($x, $y, $z);
				}

				if($center !== null){
					$result = new GamesArena($arenaName, $center, $spawns, $level, $kit);
				}
			}
		}

		return $result;
	}

	abstract public function getName() : string;

	/**
	 * @return array
	 */
	abstract public function getData() : array;

	/**
	 * @param MineceitPlayer $player
	 * @param                $value
	 */
	abstract public function teleportPlayer(MineceitPlayer $player, $value = true) : void;
}
