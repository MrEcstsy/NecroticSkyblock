<?php

namespace xtcy\Necrotic;

use customiesdevs\customies\item\CustomiesItemFactory;
use IvanCraft623\RankSystem\RankSystem;
use IvanCraft623\RankSystem\session\Session;
use IvanCraft623\RankSystem\tag\Tag;
use muqsit\invmenu\InvMenuHandler;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\item\enchantment\StringToEnchantmentParser;
use pocketmine\plugin\PluginBase;
use pocketmine\resourcepacks\ZippedResourcePack;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;
use poggit\libasynql\DataConnector;
use poggit\libasynql\libasynql;
use Symfony\Component\Filesystem\Path;
use wockkinmycup\utilitycore\utils\Utils;
use xtcy\Necrotic\commands\DailiesCommand;
use xtcy\Necrotic\commands\FlightTime;
use xtcy\Necrotic\commands\FlyCommand;
use xtcy\Necrotic\commands\KitCommand;
use xtcy\Necrotic\commands\money\BalanceCommand;
use xtcy\Necrotic\commands\money\PayCommand;
use xtcy\Necrotic\commands\QuestCommand;
use xtcy\Necrotic\item\custom\BallotItem;
use xtcy\Necrotic\item\custom\GreenArrowItem;
use xtcy\Necrotic\item\custom\OpenBookItem;
use xtcy\Necrotic\item\custom\RedArrowItem;
use xtcy\Necrotic\item\custom\TagVoucherItem;
use xtcy\Necrotic\item\custom\XItem;
use xtcy\Necrotic\listeners\ItemListener;
use xtcy\Necrotic\listeners\MainListener;
use xtcy\Necrotic\player\homes\HomeManager;
use xtcy\Necrotic\player\PlayerManager;
use xtcy\Necrotic\utils\Menus\KitMenu;
use xtcy\Necrotic\utils\Queries;
use xtcy\Necrotic\VanillaEnchants\DepthStriderEnchantment;
use xtcy\Necrotic\VanillaEnchants\LootingEnchantment;

class Loader extends PluginBase {

    use SingletonTrait;

    private static DataConnector $connector;

    private static PlayerManager $playerManager;

    private static HomeManager $homeManager;

    public function onLoad(): void
    {
        self::setInstance($this);
        $enchants = [
            new LootingEnchantment(),
            new DepthStriderEnchantment(),
        ];
        foreach ($enchants as $enchant) {
            EnchantmentIdMap::getInstance()->register($enchant->getMcpeId(), $enchant);
            StringToEnchantmentParser::getInstance()->register($enchant->getId(), fn() => $enchant);
        }
    }

    public function onEnable(): void
    {
        $this->init();
    }

    public function onDisable(): void
    {
        if (isset($this->connector)) {
            $this->connector->close();
        }
    }

    /**
     * @throws \ReflectionException
     */
    public function init(): void
    {
        $settings = [
            "type" => "sqlite",
            "sqlite" => ["file" => "sqlite.sql"],
            "worker-limit" => 2
        ];

        self::$connector = libasynql::create($this, $settings, ["sqlite" => "sqlite.sql"]);
        self::$connector->executeGeneric(Queries::PLAYERS_INIT);
        self::$connector->executeGeneric(Queries::HOMES_INIT);

        self::$connector->waitAll();

        self::$playerManager = new PlayerManager($this);
        self::$homeManager = new HomeManager($this, 3);

        $this->getServer()->getCommandMap()->unregister($this->getServer()->getCommandMap()->getCommand("title"));
        //$this->getServer()->getCommandMap()->unregister($this->getServer()->getCommandMap()->getCommand("gamemode"));

        $this->saveDefaultConfig();
        if(!InvMenuHandler::isRegistered()){
            InvMenuHandler::register($this);
        }
        $this->saveDefaultConfig();
        $this->registerCommands();
        RankSystem::getInstance()->getTagManager()->registerTag(new Tag("title", static function(Session $session) : string {
            $title = Loader::getPlayerManager()->getSession($session->getPlayer());
            return $title->getTitle();
        }));

        $this->getServer()->getPluginManager()->registerEvents(new MainListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new ItemListener(), $this);
        $this->saveDefaultConfig();

        CustomiesItemFactory::getInstance()->registerItem(BallotItem::class, "necrotic:ballot", "Ballot");
        CustomiesItemFactory::getInstance()->registerItem(RedArrowItem::class, "necrotic:red_arrow", "Red Arrow");
        CustomiesItemFactory::getInstance()->registerItem(GreenArrowItem::class, "necrotic:green_arrow", "Green Arrow");
        CustomiesItemFactory::getInstance()->registerItem(XItem::class, "necrotic:x", "X");
        CustomiesItemFactory::getInstance()->registerItem(TagVoucherItem::class, "necrotic:tag_voucher", "Tag Voucher");
        CustomiesItemFactory::getInstance()->registerItem(OpenBookItem::class, "necrotic:open_book", "Open Book");


        $this->saveResource("NecroticSkyblock.mcpack");
        $rpManager = $this->getServer()->getResourcePackManager();
        $rpManager->setResourceStack(array_merge($rpManager->getResourceStack(), [new ZippedResourcePack(Path::join($this->getDataFolder(), "NecroticSkyblock.mcpack"))]));
        (new \ReflectionProperty($rpManager, "serverForceResources"))->setValue($rpManager, true);
    }

    public function registerCommands(): void
    {
        $this->getServer()->getCommandMap()->registerAll("necrotic", [
            new BalanceCommand($this, "balance", "View your balance", ["bal"]),
            new PayCommand($this, "pay", "Pay money to players"),
            new FlyCommand($this, "fly", "Enable flight"),
            new DailiesCommand($this, "dailies", "View your dailies"),
            new FlightTime($this, "flighttime", "View your flight time", ["flytime"]),
            new KitCommand($this, "kits", "View available kits", ["kit"]),
            new QuestCommand($this, 'quest', "Teleport to the quest man", ["quests"])
        ]);
    }

    public static function getDataBase(): DataConnector
    {
        return self::$connector;
    }

    public static function getPlayerManager(): PlayerManager {
        return self::$playerManager;
    }

    public static function getHomeManager(): HomeManager {
        return self::$homeManager;
    }
}