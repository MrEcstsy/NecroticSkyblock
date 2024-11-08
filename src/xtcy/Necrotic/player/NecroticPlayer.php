<?php

declare(strict_types=1);

namespace xtcy\Necrotic\player;

use pocketmine\player\Player;
use pocketmine\Server;
use Ramsey\Uuid\UuidInterface;
use wockkinmycup\utilitycore\utils\Utils;
use xtcy\Necrotic\Loader;
use xtcy\Necrotic\utils\Queries;

final class NecroticPlayer
{

    private bool $isConnected = false;

    public function __construct(
        private UuidInterface $uuid,
        private string        $username,
        private int           $balance,
        private int           $giftcards,
        private int           $kills,
        private int           $deaths,
        private int           $bounty,
        private string        $cooldowns,
        private string        $title,
        private int           $flighttime,
        private int           $mysterydust,
    )
    {
    }

    public function isConnected(): bool
    {
        return $this->isConnected;
    }

    public function setConnected(bool $connected): void
    {
        $this->isConnected = $connected;
    }

    /**
     * Get UUID of the player
     *
     * @return UuidInterface
     */
    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    /**
     * This function gets the PocketMine player
     *
     * @return Player|null
     */
    public function getPocketminePlayer(): ?Player
    {
        return Server::getInstance()->getPlayerByUUID($this->uuid);
    }

    /**
     * Get username of the session
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Set username of the session
     *
     * @param string $username
     * @return void
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
        $this->updateDb(); // Make sure to call updateDb function when you're making changes to the player data
    }

    /**
     * @return int
     */
    public function getBalance(): int
    {
        return $this->balance;
    }

    /**
     * @param int $amount
     * @return void
     */
    public function addBalance(int $amount, bool $force = false): void
    {
        $this->balance += $amount;
        //Utils::sendUpdate($this->getPocketminePlayer());
        $this->updateDb();
    }

    /**
     * @param int $amount
     * @return void
     */
    public function subtractBalance(int $amount, bool $force = false): void
    {
        $this->balance -= $amount;
        //Utils::sendUpdate($this->getPocketminePlayer());
        $this->updateDb();
    }

    /**
     * @param int $amount
     * @return void
     */
    public function setBalance(int $amount): void
    {
        $this->balance = $amount;
        //Utils::sendUpdate($this->getPocketminePlayer());
        $this->updateDb();
    }

    /**
     * @return int
     */
    public function getGC(): int
    {
        return $this->giftcards;
    }

    /**
     * @param int $amount
     * @return void
     */
    public function addGC(int $amount): void
    {
        $this->giftcards += $amount;
        //Utils::sendUpdate($this->getPocketminePlayer());
        $this->updateDb();
    }

    /**
     * @param int $amount
     * @return void
     */
    public function subtractGC(int $amount): void
    {
        $this->giftcards -= $amount;
        //Utils::sendUpdate($this->getPocketminePlayer());
        $this->updateDb();
    }

    /**
     * @param int $amount
     * @return void
     */
    public function setGC(int $amount): void
    {
        $this->giftcards = $amount;
       // Utils::sendUpdate($this->getPocketminePlayer());
        $this->updateDb();
    }

    /**
     * Get kills of the session
     *
     * @return int
     */
    public function getKills(): int
    {
        return $this->kills;
    }

    /**
     * Add kills to the session
     *
     * @param int $amount
     * @return void
     */
    public function addKills(int $amount = 1): void
    {
        $this->kills += $amount;
       // Utils::sendUpdate($this->getPocketminePlayer());
        $this->updateDb();
    }

    /**
     * @return int
     */
    public function getDeaths(): int {
        return $this->deaths;
    }

    /**
     * @param int $amount
     * @return void
     */
    public function addDeaths(int $amount = 1): void {
        $this->kills += $amount;
       // Utils::sendUpdate($this->getPocketminePlayer());
        $this->updateDb();
    }

    /**
     * @return int
     */
    public function getBounty(): int {
        return $this->bounty;
    }

    /**
     * @param int $amount
     * @return void
     */
    public function addBounty(int $amount = 1): void {
        $this->bounty += $amount;
        $this->updateDb();
    }

    /**
     * @return void
     */
    public function removeBounty(): void {
        $this->bounty -= 0;
        $this->updateDb();
    }

    public function addEXP(int $amount = 1): void
    {
        $this->getPocketminePlayer()->getXpManager()->addXp($amount);
       // Utils::sendUpdate($this->getPocketminePlayer());
    }

    public function setEXP(int $amount = 0): void
    {
        $this->getPocketminePlayer()->getXpManager()->setCurrentTotalXp($amount);
      //  Utils::sendUpdate($this->getPocketminePlayer());
    }

    public function subtractEXP(int $amount = 0): void
    {
        $this->getPocketminePlayer()->getXpManager()->subtractXp($amount);
      //  Utils::sendUpdate($this->getPocketminePlayer());
    }

    public function addCooldown(string $cooldownName, int $duration): void
    {
        $cooldowns = json_decode($this->cooldowns, true) ?? [];

        $cooldowns[$this->getUuid()->toString()][$cooldownName] = time() + $duration;

        $this->cooldowns = json_encode($cooldowns);

        $this->updateDb();
    }

    public function getCooldown(string $cooldownName): ?int
    {
        $cooldowns = json_decode($this->cooldowns, true);

        if ($cooldowns !== null && isset($cooldowns[$this->getUuid()->toString()][$cooldownName])) {
            $cooldownExpireTime = $cooldowns[$this->getUuid()->toString()][$cooldownName];
            $remainingCooldown = $cooldownExpireTime - time();
            return max(0, $remainingCooldown);
        }

        return null;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setTitle(string $title): void {
        $this->title = $title;
        $this->updateDb();
    }

    public function getFlightTime(): int {
        return $this->flighttime;
    }

    public function addFlightTime(int $time = 60): void {
        $this->flighttime += $time;
        $this->updateDb();
    }

    public function subtractFlightTime(int $time = 60): void {
        $this->flighttime -= $time;
        $this->updateDb();
    }

    public function getMysteryDust(): int {
        return $this->mysterydust;
    }

    public function addMysteryDust(int $amount = 1): void {
        $this->mysterydust += $amount;
        $this->updateDb();
    }

    public function subtractMysteryDust(int $amount = 1): void {
        $this->mysterydust -= $amount;
        $this->updateDb();
    }

    public function setMysteryDust(int $amount = 0): void {
        $this->mysterydust = $amount;
        $this->updateDb();
    }

    /**
     * Update player information in the database
     *
     * @return void
     */
    private function updateDb(): void
    {

        Loader::getDatabase()->executeChange(Queries::PLAYERS_UPDATE, [
            "uuid" => $this->uuid->toString(),
            "username" => $this->username,
            "balance" => $this->balance,
            "giftcards" => $this->giftcards,
            "kills" => $this->kills,
            "deaths" => $this->deaths,
            "bounty" => $this->bounty,
            "cooldowns" => $this->cooldowns,
            "title" => $this->title,
            "flighttime" => $this->flighttime,
            "mysterydust" => $this->mysterydust,
        ]);
    }

}