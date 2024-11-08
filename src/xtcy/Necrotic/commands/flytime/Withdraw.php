<?php

namespace xtcy\Necrotic\commands\flytime;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;
use wockkinmycup\utilitycore\utils\Utils;
use xtcy\Necrotic\item\CustomItems;
use xtcy\Necrotic\Loader;

class Withdraw extends BaseSubCommand
{

    /**
     * @inheritDoc
     * @throws ArgumentOrderException
     */
    protected function prepare(): void
    {
        $this->setPermission("command.default");
        $this->registerArgument(0, new IntegerArgument("minutes", false));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) {
            return;
        }

        $session = Loader::getPlayerManager()->getSession($sender);

        if ($session !== null) {
            $minutes = $args["minutes"];
            $seconds = $minutes * 60;

            if ($session->getFlightTime() >= $seconds) {
                if ($minutes > 0) {
                    $session->subtractFlightTime($seconds);
                    $sender->getInventory()->addItem(CustomItems::createVouchers("fly", 1, $seconds));
                    $sender->sendMessage(C::colorize("&r&a✔ &3You have withdrawn &a" . $minutes . " minutes of flight time. &r&7(" . Utils::translateTime($session->getFlightTime()) . ")"));
                } else {
                    $sender->sendMessage(C::colorize("&r&c✘ &3Please enter a valid duration in minutes."));
                }
            } else {
                $sender->sendMessage(C::colorize("&r&c✘ &3You don't have enough flight time to withdraw &c" . $minutes . " minutes"));
            }
        }
    }
}
