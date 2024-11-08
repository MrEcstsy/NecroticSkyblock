<?php

namespace xtcy\Necrotic\listeners;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\utils\TextFormat as C;
use wockkinmycup\utilitycore\utils\Utils;
use xtcy\Necrotic\Loader;

class ItemListener implements Listener
{

    public function onUse(PlayerItemUseEvent $event): void {
        $player = $event->getPlayer();
        $item = $event->getItem();
        $tag = $item->getNamedTag();
        $session = Loader::getPlayerManager()->getSession($player);

        if ($tag->getTag("vouchers")) {
            $value = $tag->getString("vouchers");
            if ($value === "flytime") {
                if ($session !== null) {
                    $session->addFlightTime($tag->getInt("flytime"));
                    $player->sendMessage(C::colorize("&r&l&a! &r&aYou have gained &e" . Utils::translateTime($tag->getInt("flytime")) . "&a of flight - total: &2" . Utils::translateTime($session->getFlightTime())));
                    $item->pop();
                    $player->getInventory()->setItemInHand($item);
                }
            }
        }
    }
}