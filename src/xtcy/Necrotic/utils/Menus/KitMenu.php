<?php

namespace xtcy\Necrotic\utils\Menus;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\DeterministicInvMenuTransaction;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Armor;
use pocketmine\item\Axe;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\StringToEnchantmentParser;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemTypeIds;
use pocketmine\item\Pickaxe;
use pocketmine\item\Shovel;
use pocketmine\item\Sword;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;
use wockkinmycup\utilitycore\utils\Utils;
use xtcy\Necrotic\Loader;
use xtcy\Necrotic\tasks\KitCooldownUpdateTask;

class KitMenu
{

    public static function getMenu(Player $player): ?InvMenu {
        $menu = InvMenu::create(InvMenuTypeIds::TYPE_CHEST);
        $inventory = $menu->getInventory();
        $session = Loader::getPlayerManager()->getSession($player);

        $menu->setName(C::colorize("&r&8Kits"));
        Utils::fillBorders($inventory, VanillaBlocks::STAINED_GLASS_PANE()->setColor(DyeColor::BLACK)->asItem());

        if ($session->getCooldown("starter_kit") === 0 || $session->getCooldown("starter_kit") === null) {
            $inventory->setItem(10, VanillaItems::LEATHER_CAP()->setCustomName(C::colorize("&r&aStarter"))->setLore([C::colorize("&r&b ♦ &7Cooldown: &f6 Minutes"), "", C::colorize("&r&e&l[LEFT-CLICK]&r&e: &fGet Kit")]));
        } else {
            $inventory->setItem(11, VanillaItems::LEATHER_CAP()->setCustomName(C::colorize("&r&aStarter"))->setLore([C::colorize("&r&b ♦ &7On Cooldown For: &f" . Utils::translateTime($session->getCooldown("starter_kit"))), "", C::colorize("&r&e&l[LEFT-CLICK]&r&e: &fGet Kit")]));
            $task = new KitCooldownUpdateTask($player, $menu);
            Loader::getInstance()->getScheduler()->scheduleRepeatingTask($task, 20);
        }

        if ($session->getCooldown("scribe_kit") === 0 || $session->getCooldown("scribe_kit") === null) {
            $inventory->setItem(11, VanillaItems::CHAINMAIL_HELMET()->setCustomName(C::colorize("&r&b&lScribe"))->setLore([C::colorize("&r&b ♦ &7Cooldown: &f2 days"), "", C::colorize("&r&e&l[LEFT-CLICK]&r&e: &fGet Kit")]));
        } else {
            $inventory->setItem(11, VanillaItems::CHAINMAIL_HELMET()->setCustomName(C::colorize("&r&b&lScribe"))->setLore([C::colorize("&r&b ♦ &7On Cooldown For: &f" . Utils::translateTime($session->getCooldown("scribe_kit"))), "", C::colorize("&r&e&l[LEFT-CLICK]&r&e: &fGet Kit")]));
            $task = new KitCooldownUpdateTask($player, $menu);
            Loader::getInstance()->getScheduler()->scheduleRepeatingTask($task, 20);
        }

        if ($session->getCooldown("mummy_kit") === 0 || $session->getCooldown("mummy_kit") === null) {
            $inventory->setItem(12, VanillaItems::IRON_HELMET()->setCustomName(C::colorize("&r&b&lMummy"))->setLore([C::colorize("&r&b ♦ &7Cooldown: &f2 days"), "", C::colorize("&r&e&l[LEFT-CLICK]&r&e: &fGet Kit")]));
        } else {
            $inventory->setItem(12, VanillaItems::IRON_HELMET()->setCustomName(C::colorize("&r&b&lMummy"))->setLore([C::colorize("&r&b ♦ &7On Cooldown For: &f" . Utils::translateTime($session->getCooldown("mummy_kit"))), "", C::colorize("&r&e&l[LEFT-CLICK]&r&e: &fGet Kit")]));
            $task = new KitCooldownUpdateTask($player, $menu);
            Loader::getInstance()->getScheduler()->scheduleRepeatingTask($task, 20);
        }

        if ($session->getCooldown("solder_kit") === 0 || $session->getCooldown("solder_kit") === null) {
            $inventory->setItem(13, VanillaItems::GOLDEN_HELMET()->setCustomName(C::colorize("&r&b&lSolder"))->setLore([C::colorize("&r&b ♦ &7Cooldown: &f2 days"), "", C::colorize("&r&e&l[LEFT-CLICK]&r&e: &fGet Kit")]));
        } else {
            $inventory->setItem(13, VanillaItems::GOLDEN_HELMET()->setCustomName(C::colorize("&r&b&lSolder"))->setLore([C::colorize("&r&b ♦ &7On Cooldown For: &f" . Utils::translateTime($session->getCooldown("solder_kit"))), "", C::colorize("&r&e&l[LEFT-CLICK]&r&e: &fGet Kit")]));
            $task = new KitCooldownUpdateTask($player, $menu);
            Loader::getInstance()->getScheduler()->scheduleRepeatingTask($task, 20);
        }

        if ($session->getCooldown("noble_kit") === 0 || $session->getCooldown("noble_kit") === null) {
            $inventory->setItem(14, VanillaItems::DIAMOND_HELMET()->setCustomName(C::colorize("&r&b&lNoble"))->setLore([C::colorize("&r&b ♦ &7Cooldown: &f2 days"), "", C::colorize("&r&e&l[LEFT-CLICK]&r&e: &fGet Kit")]));
        } else {
            $inventory->setItem(14, VanillaItems::DIAMOND_HELMET()->setCustomName(C::colorize("&r&b&lNoble"))->setLore([C::colorize("&r&b ♦ &7On Cooldown For: &f" . Utils::translateTime($session->getCooldown("noble_kit"))), "", C::colorize("&r&e&l[LEFT-CLICK]&r&e: &fGet Kit")]));
            $task = new KitCooldownUpdateTask($player, $menu);
            Loader::getInstance()->getScheduler()->scheduleRepeatingTask($task, 20);
        }

        if ($session->getCooldown("pharaoh_kit") === 0 || $session->getCooldown("pharaoh_kit") === null) {
            $inventory->setItem(15, VanillaItems::NETHERITE_HELMET()->setCustomName(C::colorize("&r&b&lPharaoh"))->setLore([C::colorize("&r&b ♦ &7Cooldown: &f2 days"), "", C::colorize("&r&e&l[LEFT-CLICK]&r&e: &fGet Kit")]));
        } else {
            $inventory->setItem(15, VanillaItems::NETHERITE_HELMET()->setCustomName(C::colorize("&r&b&lPharaoh"))->setLore([C::colorize("&r&b ♦ &7On Cooldown For: &f" . Utils::translateTime($session->getCooldown("pharaoh_kit"))), "", C::colorize("&r&e&l[LEFT-CLICK]&r&e: &fGet Kit")]));
            $task = new KitCooldownUpdateTask($player, $menu);
            Loader::getInstance()->getScheduler()->scheduleRepeatingTask($task, 20);
        }

        $starterKit = [
            VanillaItems::STONE_PICKAXE()->setCustomName(C::colorize("&r&l&bStarter Pickaxe")),
            VanillaItems::STONE_AXE()->setCustomName(C::colorize("&r&l&bStarter Axe")),
            VanillaItems::STONE_SWORD()->setCustomName(C::colorize("&r&l&bStarter Sword")),
            VanillaItems::STONE_SHOVEL()->setCustomName(C::colorize("&r&l&bStarter Shovel")),
            VanillaItems::LEATHER_CAP()->setCustomName(C::colorize("&r&l&bStarter Helmet")),
            VanillaItems::LEATHER_TUNIC()->setCustomName(C::colorize("&r&l&bStarter Chestplate")),
            VanillaItems::LEATHER_PANTS()->setCustomName(C::colorize("&r&l&bStarter Leggings")),
            VanillaItems::LEATHER_BOOTS()->setCustomName(C::colorize("&r&l&bStarter Boots")),
            VanillaItems::WATER_BUCKET()->setCustomName(C::colorize("&r&l&bStarter Bucket")),
            VanillaItems::STEAK()->setCount(16)
        ];

        $scribeKit = [
            VanillaItems::DIAMOND_PICKAXE()->setCustomName(C::colorize("&r&l&bScribe Pickaxe")),
            VanillaItems::DIAMOND_AXE()->setCustomName(C::colorize("&r&l&bScribe Axe")),
            VanillaItems::DIAMOND_SHOVEL()->setCustomName(C::colorize("&r&l&bScribe Shovel")),
            VanillaItems::DIAMOND_SWORD()->setCustomName(C::colorize("&r&l&bScribe Sword")),
            VanillaItems::DIAMOND_HELMET()->setCustomName(C::colorize("&r&l&bScribe Helmet")),
            VanillaItems::DIAMOND_CHESTPLATE()->setCustomName(C::colorize("&r&l&bScribe Chestplate")),
            VanillaItems::DIAMOND_LEGGINGS()->setCustomName(C::colorize("&r&l&bScribe Leggings")),
            VanillaItems::DIAMOND_BOOTS()->setCustomName(C::colorize("&r&l&bScribe Boots")),
            VanillaBlocks::IRON()->asItem()->setCount(128)
        ];

        $mummyKit = [
            VanillaItems::DIAMOND_PICKAXE()->setCustomName(C::colorize("&r&l&bMummy Pickaxe")),
            VanillaItems::DIAMOND_AXE()->setCustomName(C::colorize("&r&l&bMummy Axe")),
            VanillaItems::DIAMOND_SHOVEL()->setCustomName(C::colorize("&r&l&bMummy Shovel")),
            VanillaItems::DIAMOND_SWORD()->setCustomName(C::colorize("&r&l&bMummy Sword")),
            VanillaItems::DIAMOND_HELMET()->setCustomName(C::colorize("&r&l&bMummy Helmet")),
            VanillaItems::DIAMOND_CHESTPLATE()->setCustomName(C::colorize("&r&l&bMummy Chestplate")),
            VanillaItems::DIAMOND_LEGGINGS()->setCustomName(C::colorize("&r&l&bMummy Leggings")),
            VanillaItems::DIAMOND_BOOTS()->setCustomName(C::colorize("&r&l&bMummy Boots")),
            VanillaBlocks::GOLD()->asItem()->setCount(192)
        ];

        $soldierKit = [
            VanillaItems::DIAMOND_PICKAXE()->setCustomName(C::colorize("&r&l&bSolider Pickaxe")),
            VanillaItems::DIAMOND_AXE()->setCustomName(C::colorize("&r&l&bSolider Axe")),
            VanillaItems::DIAMOND_SHOVEL()->setCustomName(C::colorize("&r&l&bSolider Shovel")),
            VanillaItems::DIAMOND_SWORD()->setCustomName(C::colorize("&r&l&bSolider Sword")),
            VanillaItems::DIAMOND_HELMET()->setCustomName(C::colorize("&r&l&bSolider Helmet")),
            VanillaItems::DIAMOND_CHESTPLATE()->setCustomName(C::colorize("&r&l&bSolider Chestplate")),
            VanillaItems::DIAMOND_LEGGINGS()->setCustomName(C::colorize("&r&l&bSolider Leggings")),
            VanillaItems::DIAMOND_BOOTS()->setCustomName(C::colorize("&r&l&bSolider Boots")),
            VanillaBlocks::DIAMOND()->asItem()->setCount(256)
        ];

        $nobleKit = [
            VanillaItems::DIAMOND_PICKAXE()->setCustomName(C::colorize("&r&l&bNoble Pickaxe")),
            VanillaItems::DIAMOND_AXE()->setCustomName(C::colorize("&r&l&bNoble Axe")),
            VanillaItems::DIAMOND_SHOVEL()->setCustomName(C::colorize("&r&l&bNoble Shovel")),
            VanillaItems::DIAMOND_SWORD()->setCustomName(C::colorize("&r&l&bNoble Sword")),
            VanillaItems::DIAMOND_HELMET()->setCustomName(C::colorize("&r&l&bNoble Helmet")),
            VanillaItems::DIAMOND_CHESTPLATE()->setCustomName(C::colorize("&r&l&bNoble Chestplate")),
            VanillaItems::DIAMOND_LEGGINGS()->setCustomName(C::colorize("&r&l&bNoble Leggings")),
            VanillaItems::DIAMOND_BOOTS()->setCustomName(C::colorize("&r&l&bNoble Boots")),
            VanillaBlocks::EMERALD()->asItem()->setCount(256)
        ];

        $pharaohKit = [
            VanillaItems::NETHERITE_PICKAXE()->setCustomName(C::colorize("&r&l&bPharaoh Pickaxe")),
            VanillaItems::NETHERITE_AXE()->setCustomName(C::colorize("&r&l&bPharaoh Axe")),
            VanillaItems::NETHERITE_SHOVEL()->setCustomName(C::colorize("&r&l&bPharaoh Shovel")),
            VanillaItems::NETHERITE_SWORD()->setCustomName(C::colorize("&r&l&bPharaoh Sword")),
            VanillaItems::NETHERITE_HELMET()->setCustomName(C::colorize("&r&l&bPharaoh Helmet")),
            VanillaItems::NETHERITE_CHESTPLATE()->setCustomName(C::colorize("&r&l&bPharaoh Chestplate")),
            VanillaItems::NETHERITE_LEGGINGS()->setCustomName(C::colorize("&r&l&bPharaoh Leggings")),
            VanillaItems::NETHERITE_BOOTS()->setCustomName(C::colorize("&r&l&bPharaoh Boots")),
            VanillaBlocks::NETHERITE()->asItem()->setCount(196)
        ];

        $menu->setListener(InvMenu::readonly(function (DeterministicInvMenuTransaction $transaction) use($starterKit, $scribeKit, $mummyKit, $soldierKit, $nobleKit, $pharaohKit): void {
            $player = $transaction->getPlayer();
            $slot = $transaction->getAction()->getSlot();
            $session = Loader::getPlayerManager()->getSession($player);

            if ($slot === 10 && ($session->getCooldown("starter_kit") === 0 || $session->getCooldown("starter_kit") === null)) {
                foreach ($starterKit as $item) {
                    $player->getInventory()->addItem($item);
                    $session->addCooldown("starter_kit", 360);
                    $player->removeCurrentWindow();
                }
            }

            if ($slot === 11 && ($session->getCooldown("scribe_kit") === 0 || $session->getCooldown("scribe_kit") === null)) {
                if ($player->hasPermission("kit.scribe")) {
                    foreach ($scribeKit as $item) {
                        if ($item instanceof Pickaxe) {
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::EFFICIENCY(), 2));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::FORTUNE(), 2));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 2));
                        } elseif ($item instanceof Axe || $item instanceof Shovel) {
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::EFFICIENCY(), 2));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 1));
                        } elseif ($item instanceof Sword) {
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 1));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 1));
                        } elseif ($item instanceof Armor) {
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 3));
                        }

                        $session->addMysteryDust(100);
                        $player->getInventory()->addItem($item);
                        $session->addCooldown("scribe_kit", 172800);
                        $player->removeCurrentWindow();
                    }
                }
            }

            if ($slot === 12 && ($session->getCooldown("mummy_kit") === 0 || $session->getCooldown("mummy_kit") === null)) {
                if ($player->hasPermission("kit.mummy")) {
                    foreach ($mummyKit as $item) {
                        if ($item instanceof Pickaxe) {
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::EFFICIENCY(), 3));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::FORTUNE(), 3));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::MENDING(), 1));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 2));
                        } elseif ($item instanceof Axe || $item instanceof Shovel) {
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::EFFICIENCY(), 3));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 2));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::MENDING(), 1));
                        } elseif ($item instanceof Sword) {
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 5));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::MENDING(), 1));
                            $item->addEnchantment(new EnchantmentInstance(StringToEnchantmentParser::getInstance()->parse("looting"), 2));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 4));
                        } elseif ($item instanceof Armor) {
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 5));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::MENDING(), 1));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
                        }

                        $session->addMysteryDust(150);
                        $player->getInventory()->addItem($item);
                        $session->addCooldown("mummy_kit", 172800);
                        $player->removeCurrentWindow();
                    }
                }
            }

            if ($slot === 13 && ($session->getCooldown("soldier_kit") === 0 || $session->getCooldown("soldier_kit") === null)) {
                if ($player->hasPermission("kit.soldier")) {
                    foreach ($soldierKit as $item) {
                        if ($item instanceof Pickaxe) {
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::EFFICIENCY(), 4));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::FORTUNE(), 5));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::MENDING(), 1));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
                        } elseif ($item instanceof Axe || $item instanceof Shovel) {
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::EFFICIENCY(), 3));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::MENDING(), 1));
                        } elseif ($item instanceof Sword) {
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::MENDING(), 1));
                            $item->addEnchantment(new EnchantmentInstance(StringToEnchantmentParser::getInstance()->parse("looting"), 2));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 4));
                        } elseif ($item instanceof Armor) {
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 7));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::MENDING(), 1));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 5));
                        }

                        $session->addMysteryDust(250);
                        $player->getInventory()->addItem($item);
                        $session->addCooldown("soldier_kit", 172800);
                        $player->removeCurrentWindow();
                    }
                }
            }

            if ($slot === 14 && ($session->getCooldown("noble_kit") === 0 || $session->getCooldown("noble_kit") === null)) {
                if ($player->hasPermission("kit.noble")) {
                    foreach ($nobleKit as $item) {
                        if ($item instanceof Pickaxe) {
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::EFFICIENCY(), 5));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::FORTUNE(), 6));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::MENDING(), 1));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 4));
                        } elseif ($item instanceof Axe || $item instanceof Shovel) {
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::EFFICIENCY(), 5));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 4));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::MENDING(), 1));
                        } elseif ($item instanceof Sword) {
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 4));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::MENDING(), 1));
                            $item->addEnchantment(new EnchantmentInstance(StringToEnchantmentParser::getInstance()->parse("looting"), 2));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 4));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::FIRE_ASPECT(), 1));
                        } elseif ($item instanceof Armor) {
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 7));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::MENDING(), 1));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
                            if ($item->getTypeId() === ItemTypeIds::DIAMOND_HELMET) {
                                $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::RESPIRATION(), 2));
                            } elseif ($item->getTypeId() === ItemTypeIds::DIAMOND_BOOTS) {
                                $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::RESPIRATION(), 2));
                                $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::FEATHER_FALLING(), 3));
                                $item->addEnchantment(new EnchantmentInstance(StringToEnchantmentParser::getInstance()->parse("depth_strider"), 1));

                            }
                        }

                        $session->addMysteryDust(350);
                        $player->getInventory()->addItem($item);
                        $session->addCooldown("noble_kit", 172800);
                        $player->removeCurrentWindow();
                    }
                }
            }

            if ($slot === 15 && ($session->getCooldown("pharaoh_kit") === 0 || $session->getCooldown("pharaoh_kit") === null)) {
                if ($player->hasPermission("kit.pharaoh")) {
                    foreach ($pharaohKit as $item) {
                        if ($item instanceof Pickaxe) {
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::EFFICIENCY(), 6));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::FORTUNE(), 7));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::MENDING(), 1));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 5));
                        } elseif ($item instanceof Axe || $item instanceof Shovel) {
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::EFFICIENCY(), 6));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 5));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::MENDING(), 1));
                        } elseif ($item instanceof Sword) {
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 5));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::MENDING(), 1));
                            $item->addEnchantment(new EnchantmentInstance(StringToEnchantmentParser::getInstance()->parse("looting"), 3));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 5));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::FIRE_ASPECT(), 2));
                        } elseif ($item instanceof Armor) {
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 10));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::MENDING(), 1));
                            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 6));
                            if ($item->getTypeId() === ItemTypeIds::NETHERITE_HELMET) {
                                $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::RESPIRATION(), 2));
                            } elseif ($item->getTypeId() === ItemTypeIds::NETHERITE_BOOTS) {
                                $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::RESPIRATION(), 2));
                                $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::FEATHER_FALLING(), 3));
                                $item->addEnchantment(new EnchantmentInstance(StringToEnchantmentParser::getInstance()->parse("depth_strider"), 1));

                            }
                        }

                        $session->addMysteryDust(500);
                        $player->getInventory()->addItem($item);
                        $session->addCooldown("pharaoh_kit", 172800);
                        $player->removeCurrentWindow();
                    }
                }
            }

        }));
        return $menu;
    }
}