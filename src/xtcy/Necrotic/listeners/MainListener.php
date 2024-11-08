<?php

namespace xtcy\Necrotic\listeners;

use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\entity\Living;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerToggleFlightEvent;
use pocketmine\item\Item;
use pocketmine\item\LegacyStringToItemParser;
use pocketmine\item\StringToItemParser;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;
use wockkinmycup\utilitycore\utils\Utils;
use xtcy\Necrotic\commands\FlyCommand;
use xtcy\Necrotic\Loader;
use xtcy\Necrotic\player\PlayerManager;
use pocketmine\utils\TextFormat as C;
use xtcy\Necrotic\utils\NecroticUtils;
use xtcy\Necrotic\VanillaEnchants\DepthStriderEnchantment;
use xtcy\Necrotic\VanillaEnchants\LootingEnchantment;

class MainListener implements Listener
{
    /**
     * @throws \JsonException
     */
    public function onLogin(PlayerLoginEvent $event): void {
        $player = $event->getPlayer();
        if (PlayerManager::getInstance()->getSession($player) === null) {
            PlayerManager::getInstance()->createSession($player);
        }
    }

    public function onJoin(PlayerJoinEvent $e): void
    {
        $player = $e->getPlayer();
        $session = Loader::getPlayerManager()->getSession($player);
        $messages = [
            "&r&l&b NECROTIC SKYBLOCK &r&7" . Server::getInstance()->getVersion(),
            "&r&8    ᛫ &bWebsite: &fetherealhub.tk",
            "&r&8    ᛫ &bStore: &fstor.eetherealhub.tk",
            "&r&8    ᛫ &bDiscord: &fdiscord.gg/etherealhub"
        ];

        foreach ($messages as $message) {
            $player->sendMessage(C::colorize($message));
        }
        PlayerManager::getInstance()->getSession($player)->setConnected(true);
        $e->setJoinMessage("");

        $dailies = [
            "daily_reward",
            "scribe_daily_reward",
            "mummy_daily_reward",
            "soldier_daily_reward",
            "noble_daily_reward",
            "pharaoh_daily_reward"
        ];

        $availableCount = 0;

        foreach ($dailies as $daily) {
            $permission = "dailies.$daily";
            $cooldown = $session->getCooldown($daily);
            if ($player->hasPermission($permission) && ($cooldown === null || $cooldown === 0)) {
                $availableCount++;
            }
        }

        if ($availableCount > 0) {
            $player->sendMessage(C::colorize("&r&7(/dailies) &r&e⭐ &3You have &a$availableCount&f Daily Reward(s) &3available!"));
        }

    }

    public function onPlayerDeath(PlayerDeathEvent $event): void
    {
        $player = $event->getPlayer();
        $cause = $player->getLastDamageCause();
        if ($cause instanceof EntityDamageByEntityEvent) {
            $damager = $cause->getDamager();
            if ($damager instanceof Player) {
                $damagerSession = Loader::getPlayerManager()->getSession($damager);
                $damagerSession->addKills();
            }
        }
        $victimSession = Loader::getPlayerManager()->getSession($player);
        $victimSession->addDeaths();
    }

    public function onQuit(PlayerQuitEvent $event): void
    {
        $player = $event->getPlayer();

        PlayerManager::getInstance()->getSession($player)->setConnected(false);
    }

    public function onEntityDamage(EntityDamageByEntityEvent $event): void
    {
        $damager = $event->getDamager();
        $target = $event->getEntity();

        if ($damager instanceof Player) {
            if ($damager->isFlying()) {
                NecroticUtils::toggleFlight($damager, true);
            }
        }
        if ($target instanceof Player) {
            if ($target->isFlying()) {
                NecroticUtils::toggleFlight($target, true);
            }
        }
    }

    /**
     * @param EntityDamageByEntityEvent $event
     */
    public function onDamage(EntityDamageByEntityEvent $event): void
    {
        $player = $event->getEntity();
        $killer = $event->getDamager();
        if ($killer instanceof Player) {
            $item = $killer->getInventory()->getItemInHand();
            $lootingEnchantment = new LootingEnchantment();
            if (($level = $killer->getInventory()->getItemInHand()->getEnchantmentLevel(EnchantmentIdMap::getInstance()->fromId($lootingEnchantment->getMcpeId()))) > 0) {
                if (
                    !$player instanceof Player and
                    $player instanceof Living and
                    $event->getFinalDamage() >= $player->getHealth()
                ) {
                    $add = mt_rand(0, $level + 1);
                    if (is_bool(Utils::getConfiguration(Loader::getInstance(), "config.yml")->get("looting.entities"))) {
                        Server::getInstance()->getLogger()->debug("There is an error (looting) in the config of vanillaEC");
                        return;
                    }
                    $lootingMultiplier = Utils::getConfiguration(Loader::getInstance(), "config.yml")->get("looting.drop_multiplier", 1); // Drop multiplier from config

                    foreach (Utils::getConfiguration(Loader::getInstance(), "config.yml")->get("looting.entities", []) as $items) {
                        $items = [];

                        $drops = $this->getLootingDrops($player->getDrops(), $items, $add, $lootingMultiplier);
                        foreach ($drops as $drop) {
                            $killer->getWorld()->dropItem($player->getPosition()->asVector3(), $drop);
                        }
                        $player->flagForDespawn();
                    }
                }
            }
        }
    }

    /**
     * @param array $drops
     * @param array $items
     * @param int   $add
     * @param int   $multiplier
     * @return array
     */
    public function getLootingDrops(array $drops, array $items, int $add, int $multiplier): array
    {
        $lootingDrops = [];

        foreach ($items as $item2) {
            $item = StringToItemParser::getInstance()->parse($item2);
            /** @var Item $drop */
            foreach ($drops as $drop) {
                if ($drop->equals($item)) {
                    $drop->setCount($drop->getCount() + ($add * $multiplier));
                }
                $lootingDrops[] = $drop;
                break;
            }
        }

        return $lootingDrops;
    }

    /**
     * @param PlayerMoveEvent $event
     */
    public function onPlayerMove(PlayerMoveEvent $event): void
    {
        $player = $event->getPlayer();
        $item = $player->getArmorInventory()->getBoots();
        $depthStriderEnchantment = new DepthStriderEnchantment();

        if ($item !== null && $item->hasEnchantment(EnchantmentIdMap::getInstance()->fromId($depthStriderEnchantment->getMcpeId()))) {
            $level = $item->getEnchantmentLevel(EnchantmentIdMap::getInstance()->fromId($depthStriderEnchantment->getMcpeId()));

            if ($player->isSwimming()) {
                $speed = 0.1 + ($level * 0.07);
                $player->setMovementSpeed($speed);
            }
            if ($player->isUnderwater()) {
                $speed = 0.05 + ($level * 0.03);
                $player->setMovementSpeed($speed);
            } else {
                $player->setMovementSpeed(0.1);
            }
        }
    }

}
