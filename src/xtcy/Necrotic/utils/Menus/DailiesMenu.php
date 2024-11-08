<?php

namespace xtcy\Necrotic\utils\Menus;

use muqsit\customsizedinvmenu\CustomSizedInvMenu;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\DeterministicInvMenuTransaction;
use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;
use wockkinmycup\LuckyPouches\utils\PouchItem;
use wockkinmycup\utilitycore\utils\Utils;
use xtcy\Necrotic\item\custom\BallotItem;
use xtcy\Necrotic\item\CustomItems;
use xtcy\Necrotic\Loader;

class DailiesMenu
{

    public static function getMenu(Player $player): InvMenu
    {
        $menu = CustomSizedInvMenu::create(36);
        $inv = $menu->getInventory();

        $menu->setName(C::colorize("&r&8Daily Rewards"));
        Utils::fillBorders($inv, VanillaBlocks::STAINED_GLASS_PANE()->setColor(DyeColor::BLACK)->asItem());

        $session = Loader::getPlayerManager()->getSession($player);

        if ($session !== null) {

            if ($session->getCooldown("daily_reward") === 0 || $session->getCooldown("daily_reward") === null) {
                $inv->setItem(11, CustomItems::getBallotItem()->setCustomName(C::colorize("&r&7&lDEFAULT REWARD"))->setLore([C::colorize("&r&7click to receive your daily reward:"), C::colorize("&r&7 ♦ &f1x Money Pouch (Tier 1)"), C::colorize("&r&7 ♦ &f1x Experience Pouch (Tier 1)"), C::colorize("&r&7 ♦ &f25 Mystery Dust"), C::colorize("&r&7 ♦ &fOne random furniture piece"), "", C::colorize("&r&f18 Hour Cooldown")]));
            } else {
                $inv->setItem(11, VanillaItems::DYE()->setColor(DyeColor::GRAY)->setCustomName(C::colorize("&r&7&lDEFAULT REWARD"))->setLore([C::colorize("&r&7Your daily reward is currently on cooldown."), "", C::colorize("&r&fAvailable in: &7" . Utils::translateTime($session->getCooldown("daily_reward")))]));
            }

            if ($player->hasPermission("scribe.daily_reward")) {
                if ($session->getCooldown("scribe_daily_reward") === 0 || $session->getCooldown("scribe_daily_reward") === null) {
                    $inv->setItem(13, CustomItems::getBallotItem()->setCustomName(C::colorize("&r&l&3SCRIBE REWARD"))->setLore([C::colorize("&r&7Only &fScribe+ &7can use this."), C::colorize("&r&fScribe &7can be obtained @ &bstore.etherealhub.tk"), "", C::colorize("&r&7 ♦ &f2x Money Pouch (Tier 1)"), C::colorize("&r&7 ♦ &f2x Experience Pouch (Tier 1)"), C::colorize("&r&7 ♦ &f35 Mystery Dust"), "", C::colorize("&r&f18 Hour Cooldown")]));
                } else {
                    $inv->setItem(13, VanillaItems::DYE()->setColor(DyeColor::GRAY)->setCustomName(C::colorize("&r&l&3SCRIBE REWARD"))->setLore([C::colorize("&r&7Your daily reward is currently on cooldown."), "", C::colorize("&r&fAvailable in: &7" . Utils::translateTime($session->getCooldown("scribe_daily_reward")))]));
                }
            } else {
                $inv->setItem(13, VanillaBlocks::BARRIER()->asItem()->setCustomName(C::colorize("&r&l&3SCRIBE REWARD"))->setLore([C::colorize("&r&7Only &fScribe+ &7can use this."), C::colorize("&r&fScribe &7can be obtained @ &bstore.etherealhub.tk"), "", C::colorize("&r&7 ♦ &f2x Money Pouch (Tier 1)"), C::colorize("&r&7 ♦ &f2x Experience Pouch (Tier 1)"), C::colorize("&r&7 ♦ &f35 Mystery Dust"), "", C::colorize("&r&f18 Hour Cooldown")]));
            }

            if ($player->hasPermission("mummy.daily_reward")) {
                if ($session->getCooldown("mummy_daily_reward") === 0 || $session->getCooldown("mummy_daily_reward") === null) {
                    $inv->setItem(15, CustomItems::getBallotItem()->setCustomName(C::colorize("&r&l&fMUMMY REWARD"))->setLore([C::colorize("&r&7Only &fMummy+ &7can use this."), C::colorize("&r&fMummy &7can be obtained @ &bstore.etherealhub.tk"), "", C::colorize("&r&7 ♦ &f3x Money Pouch (Tier 1)"), C::colorize("&r&7 ♦ &f3x Experience Pouch (Tier 1)"), C::colorize("&r&7 ♦ &f45 Mystery Dust"), "", C::colorize("&r&f18 Hour Cooldown")]));
                } else {
                    $inv->setItem(15, VanillaItems::DYE()->setColor(DyeColor::GRAY)->setCustomName(C::colorize("&r&l&fMUMMY REWARD"))->setLore([C::colorize("&r&7Your daily reward is currently on cooldown."), "", C::colorize("&r&fAvailable in: &7" . Utils::translateTime($session->getCooldown("mummy_daily_reward")))]));
                }
            } else {
                $inv->setItem(15, VanillaBlocks::BARRIER()->asItem()->setCustomName(C::colorize("&r&l&fMUMMY REWARD"))->setLore([C::colorize("&r&7Only &fMummy+ &7can use this."), C::colorize("&r&fMummy &7can be obtained @ &bstore.etherealhub.tk"), "", C::colorize("&r&7 ♦ &f3x Money Pouch (Tier 1)"), C::colorize("&r&7 ♦ &f3x Experience Pouch (Tier 1)"), C::colorize("&r&7 ♦ &f45 Mystery Dust"), "", C::colorize("&r&f18 Hour Cooldown")]));
            }

            if ($player->hasPermission("soldier.daily_reward")) {
                if ($session->getCooldown("soldier_daily_reward") === 0 || $session->getCooldown("soldier_daily_reward") === null) {
                    $inv->setItem(19, CustomItems::getBallotItem()->setCustomName(C::colorize("&r&l&4SOLDIER REWARD"))->setLore([C::colorize("&r&7Only &fSoldier+ &7can use this."), C::colorize("&r&fSoldier &7can be obtained @ &bstore.etherealhub.tk"), "", C::colorize("&r&7 ♦ &f2x Money Pouch (Tier 2)"), C::colorize("&r&7 ♦ &f2x Experience Pouch (Tier 2)"), C::colorize("&r&7 ♦ &f55 Mystery Dust"), "", C::colorize("&r&f18 Hour Cooldown")]));
                } else {
                    $inv->setItem(19, VanillaItems::DYE()->setColor(DyeColor::GRAY)->setCustomName(C::colorize("&r&l&4SOLDIER REWARD"))->setLore([C::colorize("&r&7Your daily reward is currently on cooldown."), "", C::colorize("&r&fAvailable in: &7" . Utils::translateTime($session->getCooldown("soldier_daily_reward")))]));
                }
            } else {
                    $inv->setItem(19, VanillaBlocks::BARRIER()->asItem()->setCustomName(C::colorize("&r&l&4SOLDIER REWARD"))->setLore([C::colorize("&r&7Only &fSoldier+ &7can use this."), C::colorize("&r&fSoldier &7can be obtained @ &bstore.etherealhub.tk"), "", C::colorize("&r&7 ♦ &f2x Money Pouch (Tier 2)"), C::colorize("&r&7 ♦ &f2x Experience Pouch (Tier 2)"), C::colorize("&r&7 ♦ &f55 Mystery Dust"), "", C::colorize("&r&f18 Hour Cooldown")]));
                }

                if ($player->hasPermission("noble.daily_reward")) {
                    if ($session->getCooldown("noble_daily_reward") === 0 || $session->getCooldown("noble_daily_reward") === null) {
                        $inv->setItem(21, CustomItems::getBallotItem()->setCustomName(C::colorize("&r&l&dNOBLE REWARD"))->setLore([C::colorize("&r&7Only &fNoble+ &7can use this."), C::colorize("&r&fNoble &7can be obtained @ &bstore.etherealhub.tk"), "", C::colorize("&r&7 ♦ &f1x Money Pouch (Tier 3)"), C::colorize("&r&7 ♦ &f1x Experience Pouch (Tier 3)"), C::colorize("&r&7 ♦ &f65 Mystery Dust"), "", C::colorize("&r&f18 Hour Cooldown")]));
                    } else {
                        $inv->setItem(21, VanillaItems::DYE()->setColor(DyeColor::GRAY)->setCustomName(C::colorize("&r&l&dNOBLE REWARD"))->setLore([C::colorize("&r&7Your daily reward is currently on cooldown."), "", C::colorize("&r&fAvailable in: &7" . Utils::translateTime($session->getCooldown("noble_daily_reward")))]));
                    }
                } else {
                    $inv->setItem(21, VanillaBlocks::BARRIER()->asItem()->setCustomName(C::colorize("&r&l&dNOBLE REWARD"))->setLore([C::colorize("&r&7Only &fNoble+ &7can use this."), C::colorize("&r&fNoble &7can be obtained @ &bstore.etherealhub.tk"), "", C::colorize("&r&7 ♦ &f1x Money Pouch (Tier 3)"), C::colorize("&r&7 ♦ &f1x Experience Pouch (Tier 3)"), C::colorize("&r&7 ♦ &f65 Mystery Dust"), "", C::colorize("&r&f18 Hour Cooldown")]));
                }

                if ($player->hasPermission("pharaoh.daily_reward")) {
                    if ($session->getCooldown("pharaoh_daily_reward") === 0 || $session->getCooldown("pharaoh_daily_reward") === null) {
                        $inv->setItem(23, CustomItems::getBallotItem()->setCustomName(C::colorize("&r&l&6PHARAOH REWARD"))->setLore([C::colorize("&r&7Only &fPharaoh+ &7can use this."), C::colorize("&r&fPharaoh &7can be obtained @ &bstore.etherealhub.tk"), "", C::colorize("&r&7 ♦ &f2x Money Pouch (Tier 3)"), C::colorize("&r&7 ♦ &f2x Experience Pouch (Tier 3)"), C::colorize("&r&7 ♦ &f1x Mob Tier Pouch (Tier 1)"), C::colorize("&r&7 ♦ &f100 Mystery Dust"), "", C::colorize("&r&f18 Hour Cooldown")]));
                    } else {
                        $inv->setItem(23, VanillaItems::DYE()->setColor(DyeColor::GRAY)->setCustomName(C::colorize("&r&l&6PHARAOH REWARD"))->setLore([C::colorize("&r&7Your daily reward is currently on cooldown."), "", C::colorize("&r&fAvailable in: &7" . Utils::translateTime($session->getCooldown("pharaoh_daily_reward")))]));
                    }
                } else {
                    $inv->setItem(23, VanillaBlocks::BARRIER()->asItem()->setCustomName(C::colorize("&r&l&6PHARAOH REWARD"))->setLore([C::colorize("&r&7Only &fPharaoh+ &7can use this."), C::colorize("&r&fPharaoh &7can be obtained @ &bstore.etherealhub.tk"), "", C::colorize("&r&7 ♦ &f2x Money Pouch (Tier 3)"), C::colorize("&r&7 ♦ &f2x Experience Pouch (Tier 3)"), C::colorize("&r&7 ♦ &f1x Mob Tier Pouch (Tier 1)"), C::colorize("&r&7 ♦ &f100 Mystery Dust"), "", C::colorize("&r&f18 Hour Cooldown")]));
                }
            }

            $menu->setListener(InvMenu::readonly(function (DeterministicInvMenuTransaction $transaction): void {
                $player = $transaction->getPlayer();
                $itemClicked = $transaction->getItemClicked();
                $slot = $transaction->getAction()->getSlot();

                if ($slot === 11 && $itemClicked instanceof BallotItem) {
                    $messages = [
                        "&r&l&a✔ &3You have received the following from your &r&7&lDEFAULT &r&3Daily:",
                        "&r&7 ♦ &f1x Money Pouch (Tier 1)",
                        "&r&7 ♦ &f1x Experience Pouch (Tier 1)",
                        "&r&7 ♦ &f25 Mystery Dust",
                        "&r&7 ♦ &fOne Random Furniture Piece"
                    ];

                    foreach ($messages as $message) {
                        $player->sendMessage(C::colorize($message));
                    }

                    $items = [PouchItem::getPouchType("I"), PouchItem::getPouchType('I_XP')];
                    foreach ($items as $item) {
                        $player->getInventory()->addItem($item);
                        Loader::getPlayerManager()->getSession($player)->addMysteryDust(25);
                        Loader::getPlayerManager()->getSession($player)->addCooldown("daily_reward", 64800);
                        $player->removeCurrentWindow();
                    }
                }

                if ($slot === 13 && $itemClicked instanceof BallotItem) {
                    $messages = [
                        "&r&l&a✔ &3You have received the following from your &r&3&lSCRIBE &r&3Daily:",
                        "&r&7 ♦ &f2x Money Pouch (Tier 1)",
                        "&r&7 ♦ &f2x Experience Pouch (Tier 1)",
                        "&r&7 ♦ &f35 Mystery Dust",
                    ];

                    foreach ($messages as $message) {
                        $player->sendMessage(C::colorize($message));
                    }

                    $items = [PouchItem::getPouchType("I", 2), PouchItem::getPouchType('I_XP', 2)];
                    foreach ($items as $item) {
                        $player->getInventory()->addItem($item);
                        Loader::getPlayerManager()->getSession($player)->addMysteryDust(35);
                        Loader::getPlayerManager()->getSession($player)->addCooldown("scribe_daily_reward", 64800);
                        $player->removeCurrentWindow();
                    }
                }

                if ($slot === 15 && $itemClicked instanceof BallotItem) {
                    $messages = [
                        "&r&l&a✔ &3You have received the following from your &r&f&lMUMMY &r&3Daily:",
                        "&r&7 ♦ &f3x Money Pouch (Tier 1)",
                        "&r&7 ♦ &f3x Experience Pouch (Tier 1)",
                        "&r&7 ♦ &f45 Mystery Dust",
                    ];

                    foreach ($messages as $message) {
                        $player->sendMessage(C::colorize($message));
                    }

                    $items = [PouchItem::getPouchType("I", 3), PouchItem::getPouchType('I_XP', 3)];
                    foreach ($items as $item) {
                        $player->getInventory()->addItem($item);
                        Loader::getPlayerManager()->getSession($player)->addMysteryDust(45);
                        Loader::getPlayerManager()->getSession($player)->addCooldown("mummy_daily_reward", 64800);
                        $player->removeCurrentWindow();
                    }
                }

                if ($slot === 19 && $itemClicked instanceof BallotItem) {
                    $messages = [
                        "&r&l&a✔ &3You have received the following from your &r&4&lSOLDIER &r&3Daily:",
                        "&r&7 ♦ &f2x Money Pouch (Tier 2)",
                        "&r&7 ♦ &f2x Experience Pouch (Tier 2)",
                        "&r&7 ♦ &f55 Mystery Dust",
                    ];

                    foreach ($messages as $message) {
                        $player->sendMessage(C::colorize($message));
                    }

                    $items = [PouchItem::getPouchType("II", 2), PouchItem::getPouchType('II_XP', 2)];
                    foreach ($items as $item) {
                        $player->getInventory()->addItem($item);
                        Loader::getPlayerManager()->getSession($player)->addMysteryDust(55);
                        Loader::getPlayerManager()->getSession($player)->addCooldown("soldier_daily_reward", 64800);
                        $player->removeCurrentWindow();
                    }
                }

                if ($slot === 21 && $itemClicked instanceof BallotItem) {
                    $messages = [
                        "&r&l&a✔ &3You have received the following from your &r&d&lNOBLE &r&3Daily:",
                        "&r&7 ♦ &f1x Money Pouch (Tier 3)",
                        "&r&7 ♦ &f1x Experience Pouch (Tier 3)",
                        "&r&7 ♦ &f65 Mystery Dust",
                    ];

                    foreach ($messages as $message) {
                        $player->sendMessage(C::colorize($message));
                    }

                    $items = [PouchItem::getPouchType("III"), PouchItem::getPouchType('III_XP')];
                    foreach ($items as $item) {
                        $player->getInventory()->addItem($item);
                        Loader::getPlayerManager()->getSession($player)->addMysteryDust(65);
                        Loader::getPlayerManager()->getSession($player)->addCooldown("noble_daily_reward", 64800);
                        $player->removeCurrentWindow();
                    }
                }

                if ($slot === 23 && $itemClicked instanceof BallotItem) {
                    $messages = [
                        "&r&l&a✔ &3You have received the following from your &r&6&lPHARAOH &r&3Daily:",
                        "&r&7 ♦ &f2x Money Pouch (Tier 3)",
                        "&r&7 ♦ &f2x Experience Pouch (Tier 3)",
                        "&r&7 ♦ &f100 Mystery Dust",
                    ];

                    foreach ($messages as $message) {
                        $player->sendMessage(C::colorize($message));
                    }

                    $items = [PouchItem::getPouchType("III", 2), PouchItem::getPouchType('III_XP', 2)];
                    foreach ($items as $item) {
                        $player->getInventory()->addItem($item);
                        Loader::getPlayerManager()->getSession($player)->addMysteryDust(100);
                        Loader::getPlayerManager()->getSession($player)->addCooldown("pharaoh_daily_reward", 64800);
                        $player->removeCurrentWindow();
                    }
                }
            }));
            return $menu;
        }
    }