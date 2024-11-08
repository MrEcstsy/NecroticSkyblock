<?php

namespace xtcy\Necrotic\commands;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use wockkinmycup\utilitycore\utils\Utils;
use xtcy\Necrotic\commands\flytime\Withdraw;
use xtcy\Necrotic\Loader;
use pocketmine\utils\TextFormat as C;

class FlightTime extends BaseCommand
{

    /**
     * @inheritDoc
     */
    protected function prepare(): void
    {
        $this->setPermission("command.default");
        $this->registerSubCommand(new Withdraw($this->plugin, "withdraw", "Withdraw fly time minutes"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) {
            return;
        }

        $session = Loader::getPlayerManager()->getSession($sender);

        $sender->sendMessage(C::colorize("&r&a✔ &r&3You have &a" . Utils::translateTime($session->getFlightTime()) . "&3 of flight remaining"));
        $sender->sendMessage(C::colorize("&r&7 ♦ &3Withdraw flight time via: &a/flytime withdraw <minutes>"));
    }
}