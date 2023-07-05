<?php

namespace diamondgold\DummyItemsBlocks\util;

use pocketmine\item\ItemBlockWallOrFloor;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemTypeIds;
use pocketmine\utils\CloningRegistryTrait;

/**
 * This is the only way to do it since the item (if placeable) require the block, and the block require the item during registration...
 * DO NOT USE THIS CLASS, ITEM MAY NOT BE REGISTERED
 * @internal
 * @method static ItemBlockWallOrFloor BAMBOO_SIGN()
 * @method static ItemBlockWallOrFloor CHERRY_SIGN()
 */
final class DummyItems
{
    use CloningRegistryTrait;

    private function __construct()
    {
    }

    protected static function setup(): void
    {
        self::_registryRegister("bamboo_sign", new ItemBlockWallOrFloor(new ItemIdentifier(ItemTypeIds::newId()), DummyBlocks::BAMBOO_STANDING_SIGN(), DummyBlocks::BAMBOO_WALL_SIGN()));
        self::_registryRegister("cherry_sign", new ItemBlockWallOrFloor(new ItemIdentifier(ItemTypeIds::newId()), DummyBlocks::CHERRY_STANDING_SIGN(), DummyBlocks::CHERRY_WALL_SIGN()));
    }
}