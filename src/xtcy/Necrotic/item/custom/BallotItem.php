<?php

namespace xtcy\Necrotic\item\custom;

use customiesdevs\customies\item\CreativeInventoryInfo;
use customiesdevs\customies\item\ItemComponents;
use customiesdevs\customies\item\ItemComponentsTrait;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemTypeIds;

class BallotItem extends Item implements ItemComponents
{

    use ItemComponentsTrait;

    public function __construct() {
        parent::__construct(new ItemIdentifier(ItemTypeIds::newId()), "Ballot");
        $creativeInfo = new CreativeInventoryInfo(CreativeInventoryInfo::CATEGORY_ITEMS);
        $this->setupRenderOffsets(32, 32);

        $this->initComponent($this->getTexture(), $creativeInfo);
    }

    public function getTexture(): string {
        return "ballot";
    }

}