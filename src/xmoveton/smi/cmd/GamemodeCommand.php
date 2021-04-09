<?php

namespace xmoveton\smi\cmd;

use xmoveton\smi\SMI;

use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\event\TranslationContainer;
use pocketmine\utils\TextFormat;

class GamemodeCommand extends Command {

    /**
     * @var SMI
     */
    private $plugin;

    public function __construct (SMI $plugin, $cmd) {
        parent::__construct($cmd);
        $this->plugin = $plugin;
    }

    public function execute (CommandSender $sender, $alias, array $params) {
        $inventory = $sender->getInventory();

        if (!($sender instanceof Player)) {
            $sender->sendMessage("Only for players");
            return true;
        }
        

        if (!($sender->hasPermission("smi.command.gm"))) {
            $sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.generic.permission"));
            return true;
        }

        if ($sender->isCreative()) {
            $this->getPlugin()->changeInventory($sender, 1, array(
                "armor" => $inventory->getArmorContents(),
                "other" => $inventory->getContents()
            ));

            $sender->setGamemode(Player::SURVIVAL);

            $inventory->setContents($this->getPlugin()->getPlayerItems($sender, 0, "other"));
            $inventory->setArmorContents($this->getPlugin()->getPlayerItems($sender, 0, "armor"));

            $sender->sendMessage("Ваш игровой режим - Выживание");
        }
        elseif ($sender->isSurvival()) {
            $this->getPlugin()->changeInventory($sender, 0, array(
                "armor" => $inventory->getArmorContents(),
                "other" => $inventory->getContents()
            ));

            $sender->setGamemode(Player::CREATIVE);

            $inventory->setContents($this->getPlugin()->getPlayerItems($sender, 1, "other"));
            $inventory->setArmorContents($this->getPlugin()->getPlayerItems($sender, 1, "armor"));

            $sender->sendMessage("Ваш игровой режим - Креатив");
        }

    }

    /**
     * @return SMI
     */
    private function getPlugin () {
        return $this->plugin;
    }
}