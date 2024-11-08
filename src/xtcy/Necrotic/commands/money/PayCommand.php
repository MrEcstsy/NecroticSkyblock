<?php

namespace xtcy\Necrotic\commands\money;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;
use wockkinmycup\utilitycore\utils\Utils;
use xtcy\Necrotic\Loader;

class PayCommand extends BaseCommand
{

    /**
     * @inheritDoc
     * @throws ArgumentOrderException
     */
    protected function prepare(): void
    {
        $this->setPermission("command.default");
        $this->registerArgument(0, new RawStringArgument("name"));
        $this->registerArgument(1, new IntegerArgument("amount"));
        $this->registerArgument(2, new RawStringArgument("reason", true));
        $this->setUsage(C::colorize("&r&7(/pay) &c✘ &s/pay <player> <amount> [reason]"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) {
            return;
        }

        $targetName = $args["name"] ?? null;
        $amount = $args["amount"] ?? null;

        if ($targetName === null || $amount === null) {
            $sender->sendMessage(C::RED . "Usage: " . $this->getUsage());
            return;
        }

        $target = Utils::getPlayerByPrefix($targetName);
        if ($target === null) {
            $sender->sendMessage(C::colorize("&r&7(/pay) &c✘ §sNo player named &a" . $args["name"] . " §shas played before."));
            return;
        }

        if (!is_numeric($amount) || $amount <= 0) {
            $sender->sendMessage(C::colorize("&r&7(/pay) &c✘ Invalid amount."));
            return;
        }

        $senderSession = Loader::getPlayerManager()->getSession($sender);
        $targetSession = Loader::getPlayerManager()->getSession($target);

        if ($senderSession === null || $targetSession === null) {
            $sender->sendMessage(C::colorize("&r&7(/pay) &c✘ Failed to process payment. Please try again later."));
            return;
        }

        if ($senderSession->getBalance() < $amount) {
            $sender->sendMessage(C::colorize("&r&7(/pay) &c✘ You do not have enough money to pay " . $target->getNameTag() . "&c $" . number_format($args["amount"])));
            return;
        }

        $oldSenderBalance = $senderSession->getBalance();
        $senderSession->subtractBalance($amount);
        $newSenderBalance = $senderSession->getBalance();

        $targetSession->addBalance($amount);

        $oldBalanceFormatted = '$' . number_format($oldSenderBalance);
        $newBalanceFormatted = '$' . number_format($newSenderBalance);
        $formattedAmount = number_format($amount);

        if (isset($args["reason"])) {
            $message = C::colorize("&r&7(/pay) &a✔ §sYou paid " . $target->getNameTag() . "§s: &a$" . number_format($args["amount"]) . " &r&7(" . $oldBalanceFormatted . " ➜ " . $newBalanceFormatted . ") §sfor '&f" . $args["reason"] . "§s'");
            $sender->sendMessage($message);
            $target->sendMessage(C::colorize("&r&7(/pay) &a✔ §sYou received §a$" . $formattedAmount . " §sfrom " . $sender->getNameTag() . " §sfor '&f" . $args["reason"] . "§s'"));
        } else {
            $message = C::colorize("&r&7(/pay) &a✔ §sYou paid " . $target->getNameTag() . "§s: &a$" . number_format($args["amount"]) . " &r&7(" . $oldBalanceFormatted . " ➜ " . $newBalanceFormatted . ")");
            $sender->sendMessage($message);
            $target->sendMessage(C::colorize("&r&7(/pay) &a✔ §sYou received §a$" . $formattedAmount . " §sfrom " . $sender->getNameTag()));
        }

    }
}
