<?php

namespace xtcy\Necrotic\utils;

use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;
use wockkinmycup\utilitycore\utils\Utils;
use xtcy\Necrotic\Loader;

class NecroticUtils
{

    public static function toggleFlight(Player $player, bool $forceOff = false): void
    {
        $session = Loader::getPlayerManager()->getSession($player);

        if ($forceOff) {
            $player->setAllowFlight(false);
            $player->setFlying(false);
            $player->resetFallDistance();
            if ($session->getFlightTime() > 0) {
                $player->sendMessage(C::colorize("&r&l&c! &r&cYou have disabled flight. Remaining time: " . Utils::translateTime($session->getFlightTime())));
            } else {
                $player->sendMessage(C::colorize("&r&l&c! &r&cYou have disabled flight."));
            }
        } else {
            if (!$player->getAllowFlight()) {
                $player->setAllowFlight(true);
                if ($session->getFlightTime() > 0) {
                    $player->sendMessage(C::colorize("&r&l&a! &r&aYou have enabled flight. Remaining time: " . Utils::translateTime($session->getFlightTime())));
                } else {
                    $player->sendMessage(C::colorize("&r&l&a! &r&aYou have enabled flight."));
                }
            } else {
                $player->setAllowFlight(false);
                $player->setFlying(false);
                $player->resetFallDistance();
                if ($session->getFlightTime() > 0) {
                    $player->sendMessage(C::colorize("&r&l&c! &r&cYou have disabled flight. Remaining time: " . Utils::translateTime($session->getFlightTime())));
                } else {
                    $player->sendMessage(C::colorize("&r&l&c! &r&cYou have disabled flight."));
                }
            }
        }
    }

}