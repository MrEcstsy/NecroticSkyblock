<?php

namespace xtcy\Necrotic\tasks;

use muqsit\invmenu\InvMenu;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as C;
use wockkinmycup\utilitycore\utils\Utils;
use xtcy\Necrotic\Loader;

class KitCooldownUpdateTask extends Task
{

    private Player $player;
    private InvMenu $menu;

    public function __construct(Player $player, InvMenu $menu) {
        $this->player = $player;
        $this->menu = $menu;
    }

    public function onRun() : void {
        $session = Loader::getPlayerManager()->getSession($this->player);
        $starterCooldown = $session->getCooldown("starter_kit");
        $scribeCooldown = $session->getCooldown("scribe_kit");
        $mummyCooldown = $session->getCooldown("mummy_kit");
        $soliderCooldown = $session->getCooldown("soldier_kit");
        $nobleCooldown = $session->getCooldown("noble_kit");
        $pharaohCooldown = $session->getCooldown("pharaoh_kit");


        if ($starterCooldown > 0) {
            $helmetItem = $this->menu->getInventory()->getItem(10);
            $helmetItem->setLore([C::colorize("&r&b ♦ &7On Cooldown For: &f" . Utils::translateTime($starterCooldown)), "", C::colorize("&r&e&l[LEFT-CLICK]&r&e: &fGet Kit")]);
            $this->menu->getInventory()->setItem(10, $helmetItem);
        } else {
            $helmetItem = VanillaItems::LEATHER_CAP()->setCustomName(C::colorize("&r&aStarter"))->setLore([C::colorize("&r&b ♦ &7Cooldown: &f6 Minutes"), "", C::colorize("&r&e&l[LEFT-CLICK]&r&e: &fGet Kit")]);
            $this->menu->getInventory()->setItem(10, $helmetItem);
        }

        if ($scribeCooldown > 0) {
            $helmetItem = $this->menu->getInventory()->getItem(11);
            $helmetItem->setLore([C::colorize("&r&b ♦ &7On Cooldown For: &f" . Utils::translateTime($scribeCooldown)), "", C::colorize("&r&e&l[LEFT-CLICK]&r&e: &fGet Kit")]);
            $this->menu->getInventory()->setItem(11, $helmetItem);
        } else {
            $helmetItem = VanillaItems::CHAINMAIL_HELMET()->setCustomName(C::colorize("&r&b&lScribe"))->setLore([C::colorize("&r&b ♦ &7Cooldown: &f2 days"), "", C::colorize("&r&e&l[LEFT-CLICK]&r&e: &fGet Kit")]);
            $this->menu->getInventory()->setItem(11, $helmetItem);
        }

        if ($mummyCooldown > 0) {
            $helmetItem = $this->menu->getInventory()->getItem(12);
            $helmetItem->setLore([C::colorize("&r&b ♦ &7On Cooldown For: &f" . Utils::translateTime($scribeCooldown)), "", C::colorize("&r&e&l[LEFT-CLICK]&r&e: &fGet Kit")]);
            $this->menu->getInventory()->setItem(12, $helmetItem);
        } else {
            $helmetItem = VanillaItems::IRON_HELMET()->setCustomName(C::colorize("&r&b&lMummy"))->setLore([C::colorize("&r&b ♦ &7Cooldown: &f2 days"), "", C::colorize("&r&e&l[LEFT-CLICK]&r&e: &fGet Kit")]);
            $this->menu->getInventory()->setItem(12, $helmetItem);
        }

        if ($soliderCooldown > 0) {
            $helmetItem = $this->menu->getInventory()->getItem(13);
            $helmetItem->setLore([C::colorize("&r&b ♦ &7On Cooldown For: &f" . Utils::translateTime($scribeCooldown)), "", C::colorize("&r&e&l[LEFT-CLICK]&r&e: &fGet Kit")]);
            $this->menu->getInventory()->setItem(13, $helmetItem);
        } else {
            $helmetItem = VanillaItems::GOLDEN_HELMET()->setCustomName(C::colorize("&r&b&lSoldier"))->setLore([C::colorize("&r&b ♦ &7Cooldown: &f2 days"), "", C::colorize("&r&e&l[LEFT-CLICK]&r&e: &fGet Kit")]);
            $this->menu->getInventory()->setItem(13, $helmetItem);
        }

        if ($nobleCooldown > 0) {
            $helmetItem = $this->menu->getInventory()->getItem(14);
            $helmetItem->setLore([C::colorize("&r&b ♦ &7On Cooldown For: &f" . Utils::translateTime($scribeCooldown)), "", C::colorize("&r&e&l[LEFT-CLICK]&r&e: &fGet Kit")]);
            $this->menu->getInventory()->setItem(14, $helmetItem);
        } else {
            $helmetItem = VanillaItems::DIAMOND_HELMET()->setCustomName(C::colorize("&r&b&lNoble"))->setLore([C::colorize("&r&b ♦ &7Cooldown: &f2 days"), "", C::colorize("&r&e&l[LEFT-CLICK]&r&e: &fGet Kit")]);
            $this->menu->getInventory()->setItem(14, $helmetItem);
        }

        if ($pharaohCooldown > 0) {
            $helmetItem = $this->menu->getInventory()->getItem(15);
            $helmetItem->setLore([C::colorize("&r&b ♦ &7On Cooldown For: &f" . Utils::translateTime($scribeCooldown)), "", C::colorize("&r&e&l[LEFT-CLICK]&r&e: &fGet Kit")]);
            $this->menu->getInventory()->setItem(15, $helmetItem);
        } else {
            $helmetItem = VanillaItems::NETHERITE_HELMET()->setCustomName(C::colorize("&r&b&lPharaoh"))->setLore([C::colorize("&r&b ♦ &7Cooldown: &f2 days"), "", C::colorize("&r&e&l[LEFT-CLICK]&r&e: &fGet Kit")]);
            $this->menu->getInventory()->setItem(15, $helmetItem);
        }

        if ($starterCooldown <= 0 && $scribeCooldown <= 0 && $pharaohCooldown <= 0 && $nobleCooldown <= 0 && $soliderCooldown <= 0 && $mummyCooldown <= 0/* Add other kit cooldown checks here */) {
            Loader::getInstance()->getScheduler()->cancelAllTasks();
        }
    }
}
