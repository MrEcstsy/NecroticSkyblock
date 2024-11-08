<?php

namespace xtcy\Necrotic\utils\Menus;

use muqsit\customsizedinvmenu\CustomSizedInvMenu;
use muqsit\invmenu\transaction\DeterministicInvMenuTransaction;
use pocketmine\utils\TextFormat as C;
use muqsit\invmenu\InvMenu;

class ShopMenu
{

    public function getCategories(): InvMenu {
        $categories = CustomSizedInvMenu::create(17);

        $categories->setName(C::colorize("&r&8Shop"));

        $categories->setListener(InvMenu::readonly(function (DeterministicInvMenuTransaction $transaction): void {

        }));
        return $categories;
    }

}