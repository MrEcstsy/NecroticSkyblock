<?php

namespace xtcy\Necrotic\player\managers;

use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;
use xtcy\Necrotic\player\PlayerManager;

class QuestManager {

    public function startQuest(Player $player, string $questName, int $goal, string $description): void {
        $playerManager = PlayerManager::getInstance();
        $playerManager->assignQuest($playerManager->getSession($player), $questName, $goal, $description);

        $player->sendMessage(C::GREEN . "You've started the quest: " . $questName);
    }

    public function updateQuestProgress(Player $player, string $questName, int $progress): void {
        $playerManager = PlayerManager::getInstance();
        $playerManager->updateQuestProgress($playerManager->getSession($player), $questName, $progress);
    }

    public function completeQuest(Player $player, string $questName): void {
        $playerManager = PlayerManager::getInstance();
        $playerManager->removeQuest($playerManager->getSession($player), $questName);

        $this->giveQuestRewards($player, $questName);

        $player->sendMessage(C::GREEN . "Congratulations! You've completed the quest: " . $questName);
    }

    private function giveQuestRewards(Player $player, string $questName): void {
        switch (strtolower($questName)) {
            case "example_hunter":
                $player->getInventory()->addItem(VanillaItems::BOW());
                break;
            case "example_miner":
                $player->getInventory()->addItem(VanillaItems::DIAMOND()->setCount(3));
                break;
            default:
                $player->sendMessage(C::YELLOW . "No rewards for completing this quest.");
                break;
        }
    }
}