<?php

namespace xtcy\Necrotic\item;

use customiesdevs\customies\item\CustomiesItemFactory;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\utils\TextFormat as C;
use wockkinmycup\utilitycore\utils\Utils;

class CustomItems
{
    public static function createVouchers(string $type, int $amount = 1, int $time = 0): ?Item
    {
        $item = VanillaItems::AIR()->setCount($amount);

        switch (strtolower($type)) {
            case "fly":
                $item = VanillaItems::FEATHER()->setCount($amount);

                $item->setCustomName(C::colorize("&r&aFlight Time Voucher &7[" . Utils::translateTime($time) . "]"));
                $item->setLore([C::colorize("&r&7&oRight Click to Redeem.")]);

                $item->getNamedTag()->setString("vouchers", "flytime");
                $item->getNamedTag()->setInt("flytime", $time);
                break;
        }
        return $item;
    }


    public static function getBallotItem(int $amount = 1): ?Item {
        return CustomiesItemFactory::getInstance()->get("necrotic:ballot")->setCount($amount);
    }

    public static function getXItem(int $amount = 1): ?Item {
        return CustomiesItemFactory::getInstance()->get("necrotic:x")->setCount($amount);
    }

    public static function getOpenBookItem(int $amount = 1): ?Item {
        return CustomiesItemFactory::getInstance()->get("necrotic:open_book")->setCount($amount);
    }
}