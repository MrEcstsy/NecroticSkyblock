<?php

namespace xtcy\Necrotic\utils\Menus;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\DeterministicInvMenuTransaction;
use pocketmine\block\utils\DyeColor;
use pocketmine\block\utils\MobHeadType;
use pocketmine\block\VanillaBlocks;
use pocketmine\utils\TextFormat as C;
use pocketmine\player\Player;
use sb\item\CustomItem;
use wockkinmycup\utilitycore\utils\Utils;
use xtcy\Necrotic\item\CustomItems;
use xtcy\Necrotic\Loader;

class QuestMenu
{

    public static function getMainMenu(Player $player): InvMenu {
        $menu = InvMenu::create(InvMenu::TYPE_CHEST);
        $inventory = $menu->getInventory();
        $session = Loader::getPlayerManager()->getSession($player);

        $menu->setName(C::colorize("&r&7Quests"));
        $inventory->setItem(0, CustomItems::getXItem()->setCustomName(C::colorize("&r&c&oClick to close this menu")));
        Utils::fillBorders($inventory, VanillaBlocks::STAINED_GLASS_PANE()->setColor(DyeColor::BLACK)->asItem(), [0]);

        $mainPage = [
            11 => VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::PLAYER)->asItem()->setCustomName(C::colorize("&r&fWho are you... ?"))->setLore([C::colorize("&r&7I am the &b&lQuest Giver&r&7! I've spent most of my time"), C::colorize("&r&7travelling, but here in my older days, I like to send"), C::colorize("&r&7young folks like yourself on legendary &equests&7!")]),
            13 => CustomItems::getOpenBookItem()->setCustomName(C::colorize("&r&fWhat is a quest?"))->setLore([C::colorize("&r&7There are several different types of &equests"), C::colorize("&r&7that will force you to explore different aspects of"), C::colorize("&r&7the realm. They might require you to kill certain"), C::colorize("&r&7monsters and critters, while other &equests &7might"), C::colorize("&r&7require you to gather resources. Complete a"), C::colorize("&r&equest &7and I will reward you handsomely!")]),
        ];

        $activeQuestPage = [
            0 => CustomItems::getXItem()->setCustomName(C::colorize("&r&c&oClick to go back")),
            16 => VanillaBlocks::BARRIER()->asItem()->setCustomName(C::colorize("&r&cClick to abandon this quest."))
        ];

        foreach ($mainPage as $value => $item) {
            $inventory->setItem($value, $item);
        }

        if ($session->getQuestData() !== null) {
            $inventory->setItem(15, CustomItems::getBallotItem()->setCustomName(C::colorize("&r&fWhat was my quest again?"))->setLore([C::colorize("&r&bClick &7to read up on your &equest&7.")]));
        } else {
            $inventory->setItem(15, CustomItems::getBallotItem()->setCustomName(C::colorize("&r&fI'd like to go on an adventure!"))->setLore([C::colorize("&r&bClick &7to get a &equest&7.")]));
        }

        $menu->setListener(InvMenu::readonly(function (DeterministicInvMenuTransaction $transaction) use ($session, $menu, $activeQuestPage): void {
                $player = $transaction->getPlayer();
                $slot = $transaction->getAction()->getSlot();

                if ($slot === 15) {
                    if ($session->getQuestData() !== null) {
                        $slots = [11, 13, 15, 0];

                        foreach ($slots as $i) {
                            $menu->getInventory()->clear($i);
                        }

                        foreach ($activeQuestPage as $value => $item) {
                            $menu->getInventory()->setItem($value, $item);
                        }

                        foreach ($session->getQuestData() as $quest => $questData) {
                            $progress = $questData["progress"];
                            $goal = $questData["goal"];
                            $menu->getInventory()->setItem(13, CustomItems::getBallotItem()->setCustomName(C::colorize("&r&e" . $quest))->setLore([C::colorize($questData["description"]), "", C::colorize("&r&b Progress:"), C::colorize("&r&8  ♦ &b" . $progress . "&7/&b" . $goal)]));
                        }
                    }
                }
        }));
        return $menu;
    }

}