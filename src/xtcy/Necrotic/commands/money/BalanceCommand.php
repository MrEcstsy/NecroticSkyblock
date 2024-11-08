<?php

namespace xtcy\Necrotic\commands\money;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;
use wockkinmycup\utilitycore\utils\Utils;
use xtcy\Necrotic\Loader;

class BalanceCommand extends BaseCommand
{

    /**
     * @throws ArgumentOrderException
     */
    public function prepare(): void
    {
        $this->setPermission("command.default");
        $this->registerArgument(0, new RawStringArgument("name", true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) {
            return;
        }

        $session = Loader::getPlayerManager()->getSession($sender);

        if ($session !== null) {
            $sender->sendMessage(C::colorize("&r&7(/bal) &a✔ " . $sender->getNameTag() . "' §sbalance: &a$" . number_format($session->getBalance()) . " &r&7(" . Utils::translateShorthand($session->getBalance()) . ")"));
        }

        if (isset($args["name"])) {
            $player = Utils::getPlayerByPrefix($args["name"]);
            if ($player !== null) {
                $playerSession = Loader::getPlayerManager()->getSession($player);
                $sender->sendMessage(C::colorize("&r&7(/bal) &a✔ " . $player->getNameTag() . "' §sbalance: &a$" . number_format($playerSession->getBalance()) . " &r&7(" . Utils::translateShorthand($playerSession->getBalance()) . ")"));
            }
        }
    }
}