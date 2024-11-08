<?php

declare(strict_types=1);

namespace xtcy\Necrotic\player;

use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use xtcy\Necrotic\Loader;
use xtcy\Necrotic\utils\Queries;

final class PlayerManager
{

    use SingletonTrait;

    /** @var NecroticPlayer[] */
    private array $sessions; // array to fetch player data

    public function __construct(
        public Loader $plugin
    ){
        self::setInstance($this);

        $this->loadSessions();
    }

    /**
     * Store all player data in $sessions property
     *
     * @return void
     */
    private function loadSessions(): void
    {
        Loader::getDatabase()->executeSelect(Queries::PLAYERS_SELECT, [], function (array $rows): void {
            foreach ($rows as $row) {
                $this->sessions[$row["uuid"]] = new NecroticPlayer(
                    Uuid::fromString($row["uuid"]),
                    $row["username"],
                    $row["balance"],
                    $row["giftcards"],
                    $row["kills"],
                    $row["deaths"],
                    $row["bounty"],
                    $row["cooldowns"],
                    $row["title"],
                    $row["flighttime"],
                    $row["mysterydust"]
                );
            }
        });
    }

    /**
     * Create a session
     *
     * @param Player $player
     * @return NecroticPlayer
     * @throws \JsonException
     */
    public function createSession(Player $player): NecroticPlayer
    {
        $args = [
            "uuid" => $player->getUniqueId()->toString(),
            "username" => $player->getName(),
            "balance" => 10000,
            "giftcards" => 0,
            "kills" => 0,
            "deaths" => 0,
            "bounty" => 0,
            "cooldowns" => "{}",
            "title" => "",
            "flighttime" => 0,
            "mysterydust" => 0
        ];

        Loader::getDatabase()->executeInsert(Queries::PLAYERS_CREATE, $args);

        $this->sessions[$player->getUniqueId()->toString()] = new NecroticPlayer(
            $player->getUniqueId(),
            $args["username"],
            $args["balance"],
            $args["giftcards"],
            $args["kills"],
            $args["deaths"],
            $args["bounty"],
            $args["cooldowns"],
            $args["title"],
            $args["flighttime"],
            $args["mysterydust"]
        );
        return $this->sessions[$player->getUniqueId()->toString()];
    }

    /**
     * Get session by player object
     *
     * @param Player $player
     * @return NecroticPlayer|null
     */
    public function getSession(Player $player) : ?NecroticPlayer
    {
        return $this->getSessionByUuid($player->getUniqueId());
    }

    /**
     * Get session by player name
     *
     * @param string $name
     * @return NecroticPlayer|null
     */
    public function getSessionByName(string $name) : ?NecroticPlayer
    {
        foreach ($this->sessions as $session) {
            if (strtolower($session->getUsername()) === strtolower($name)) {
                return $session;
            }
        }
        return null;
    }

    /**
     * Get session by UuidInterface
     *
     * @param UuidInterface $uuid
     * @return NecroticPlayer|null
     */
    public function getSessionByUuid(UuidInterface $uuid) : ?NecroticPlayer
    {
        return $this->sessions[$uuid->toString()] ?? null;
    }

    public function destroySession(NecroticPlayer $session) : void
    {
        Loader::getDatabase()->executeChange(Queries::PLAYERS_DELETE, ["uuid", $session->getUuid()->toString()]);

        # Remove session from the array
        unset($this->sessions[$session->getUuid()->toString()]);
    }

    public function getSessions() : array
    {
        return $this->sessions;
    }

}