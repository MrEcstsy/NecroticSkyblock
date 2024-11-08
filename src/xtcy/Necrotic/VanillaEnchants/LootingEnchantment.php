<?php

namespace xtcy\Necrotic\VanillaEnchants;

use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\item\enchantment\Rarity;
use pocketmine\item\Item;
use pocketmine\item\Sword;
use pocketmine\lang\KnownTranslationFactory;
use xtcy\Necrotic\utils\EnchantmentTrait;

class LootingEnchantment extends Enchantment
{

    use EnchantmentTrait;

    /**
     * LootingEnchantment constructor.
     */
    public function __construct()
    {
        parent::__construct(
            KnownTranslationFactory::enchantment_lootBonus(),
            Rarity::RARE,
            ItemFlags::SWORD,
            ItemFlags::NONE,
            3
        );
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return "looting";
    }

    /**
     * @return int
     */
    public function getMcpeId(): int
    {
        return EnchantmentIds::LOOTING;
    }

    /**
     * @param Item $item
     * @return bool
     */
    public function isItemCompatible(Item $item): bool
    {
        return $item instanceof Sword;
    }
}