<?php

namespace xtcy\Necrotic\item\custom;

use customiesdevs\customies\item\CreativeInventoryInfo;
use customiesdevs\customies\item\ItemComponents;
use customiesdevs\customies\item\ItemComponentsTrait;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;

class TagVoucherItem extends Item implements ItemComponents
{

    use ItemComponentsTrait;

    public function __construct(ItemIdentifier $identifier, string $name = "Tag Voucher") {
        parent::__construct($identifier, $name);
        $creativeInfo = new CreativeInventoryInfo(CreativeInventoryInfo::CATEGORY_ITEMS);
        $this->setupRenderOffsets(32, 32, false);


        $this->initComponent("tag_voucher", $creativeInfo);
    }
}