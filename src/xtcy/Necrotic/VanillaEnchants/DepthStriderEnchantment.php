<?php

namespace xtcy\Necrotic\VanillaEnchants;

use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\item\Armor;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\item\enchantment\Rarity;
use pocketmine\item\Item;
use pocketmine\lang\KnownTranslationFactory;
use xtcy\Necrotic\utils\EnchantmentTrait;

class DepthStriderEnchantment extends Enchantment
{

    use EnchantmentTrait;

    public function __construct()
    {
        parent::__construct(
            KnownTranslationFactory::enchantment_waterWalker(),
            Rarity::UNCOMMON,
            ItemFlags::FEET,
            ItemFlags::NONE,
            3
        );
    }

    public function getId(): string
    {
        return "depth_strider";
    }

    public function getMcpeId(): int
    {
        return EnchantmentIds::DEPTH_STRIDER;
    }

    public function getIncompatibles(): array
    {
        return [];
    }

    public function isItemCompatible(Item $item): bool
    {
        return $item instanceof Armor && $item->getArmorSlot() === ItemFlags::FEET;
    }
}