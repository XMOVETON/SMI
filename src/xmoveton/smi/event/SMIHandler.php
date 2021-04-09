<?php

namespace xmoveton\smi\event;

use xmoveton\smi\SMI;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;

class SMIHandler implements Listener {

    /**
     * @var SMI
     */
    private $plugin = null;

    public function __construct (SMI $plugin) {
        $this->plugin = $plugin;
    }

    public function pple (PlayerPreLoginEvent $event) {
        $player = $event->getPlayer();

        $this->getPlugin()->createAccount($player);
    }

    /**
     * @return SMI
     */
    private function getPlugin () {
        return $this->plugin;
    }
}