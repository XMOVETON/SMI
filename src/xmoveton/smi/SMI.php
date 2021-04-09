<?php

namespace xmoveton\smi;

use xmoveton\smi\event\SMIHandler;
use xmoveton\smi\cmd\GamemodeCommand;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use pocketmine\item\Item;

class SMI extends PluginBase {

    /**
     * @var SMI
     */
    private static $instance = null;

    public function onLoad () {
        self::$instance = & $this;
    }

    /**
     * @return SMI
     */
    public static function getInstance () : SMI {
        return self::$instance;
    }

    public function onEnable () {
        $f = $this->getDataFolder();
        if (!(is_dir($f . 'players/'))) {
            @mkdir($f . 'players/');
        }

        $cm = $this->getServer()->getCommandMap();
        $cm->register("gamemode", new GamemodeCommand($this, "gm"));

        $this->getServer()->getPluginManager()->registerEvents(new SMIHandler($this), $this);
    }

    public function onDisable () {
        // 
    }

    /**
     * @param Player|string $player
     * 
     * @return boolean
     */
    public function createAccount ($player) {
        if ($player instanceof Player) {
            $player = $player->getName();
        }
        $player = strtolower($player);

        $path = $this->getDataFolder() . 'players/' . $player;

        if (!(file_exists($path))) {
            new Config($path . '.json', Config::JSON, [
                "1" => [
                    "armor" => [],
                    "other" => []
                ],
                "0" => [
                    "armor" => [],
                    "other" => []
                ]
            ]);
            return true;
        }
        return false;
    }

    public function changeInventory ($player, $mode = 0, array $contents) {
        if ($player instanceof Player) {
            $player = $player->getName();
        }
        $f = $this->getDataFolder() . 'players/';

        $config = new Config($f . $player . '.json', Config::JSON);
        $config->set($mode, $contents);
        $config->save();
    }

    /**
     * @param Player|string $player
     * @param string $mode
     * @param string $section
     * 
     * @return array
     */
    public function getPlayerItems ($player, $mode = 0, $section = "other") {
        if ($player instanceof Player) {
            $player = $player->getName();
        }
        $f = $this->getDataFolder() . 'players/';

        $config = (new Config($f . $player . '.json', Config::JSON))->getAll();

        $items = [];
        foreach ($config[$mode][$section] as $item) {
            $items[] = Item::get($item['id'], $item['damage'], $item['count'], $item['nbt']);
        }
        return $items;
    }
}