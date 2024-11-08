<?php

namespace xtcy\Necrotic\commands;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\TextFormat as C;
use xtcy\Necrotic\Loader;
use xtcy\Necrotic\utils\NecroticUtils;

class FlyCommand extends BaseCommand
{
    /**
     * @inheritDoc
     */
    protected function prepare(): void
    {
        $this->setPermission("command.default");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) {
            return;
        }

        $session = Loader::getPlayerManager()->getSession($sender);

        if ($sender->hasPermission("command.fly")) {
            if ($sender->isFlying()) {
                NecroticUtils::toggleFlight($sender, true); // Disable flight
            } else {
                NecroticUtils::toggleFlight($sender); // Enable flight
                $this->startFlightTimeTask($sender, $session); // Start task to subtract flight time
            }
        } else {
            $this->checkAndToggleTimeFlight($sender, $session);
        }
    }

    private function startFlightTimeTask(Player $player, $session): void
    {
        $scheduler = Loader::getInstance()->getScheduler();
        $task = new ClosureTask(function () use ($player, $session): void {
            if (!$player->isOnline() || !$player->isFlying()) {
                return;
            }
            $session->subtractFlightTime(1); // Subtract flight time every second while flying
        });
        $scheduler->scheduleRepeatingTask($task, 20); // 20 ticks = 1 second
    }

    private function checkAndToggleTimeFlight(Player $player, $session): void
    {
        if ($player->isFlying()) {
            NecroticUtils::toggleFlight($player, true); // Disable flight
        } else {
            $flightTime = $session->getFlightTime();
            if ($flightTime > 0) {
                NecroticUtils::toggleFlight($player); // Enable flight
                $this->startFlightTimeTask($player, $session); // Start task to subtract flight time
            } else {
                $player->sendMessage(C::RED . "You do not have permission to fly.");
            }
        }
    }
}
