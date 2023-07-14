<?php

namespace diamondgold\DummyItemsBlocks\util;

use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\BlockTypeInfo;
use pocketmine\block\FloorSign;
use pocketmine\block\tile\Sign;
use pocketmine\block\utils\WoodType;
use pocketmine\block\WallSign;
use pocketmine\data\bedrock\block\BlockTypeNames;
use pocketmine\utils\CloningRegistryTrait;

/**
 * This is the only way to do it since the item (if placeable) require the block, and the block require the item during registration...
 * DO NOT USE THIS CLASS, BLOCK MAY NOT BE REGISTERED
 * @internal
 * @method static FloorSign BAMBOO_STANDING_SIGN()
 * @method static WallSign BAMBOO_WALL_SIGN()
 */
final class DummyBlocks
{
    use CloningRegistryTrait;

    private function __construct()
    {
    }

    protected static function setup(): void
    {
        $clean = fn(string $name) => str_replace('minecraft:', '', $name);

        $fakeWoodType = WoodType::OAK();// will probably be obsolete when WoodType exists, it doesn't matter now since it's only for runtime

        $id = BlockTypeNames::BAMBOO_STANDING_SIGN;
        self::_registryRegister(
            $clean($id),
            new FloorSign(
                new BlockIdentifier(BlockTypeIds::newId(), Sign::class),
                Utils::generateNameFromId($id),
                new BlockTypeInfo(BlockBreakInfo::instant()),
                $fakeWoodType,
                fn() => DummyItems::BAMBOO_SIGN()
            )
        );
        $id = BlockTypeNames::BAMBOO_WALL_SIGN;
        self::_registryRegister(
            $clean($id),
            new WallSign(new BlockIdentifier(BlockTypeIds::newId(), Sign::class),
                Utils::generateNameFromId($id),
                new BlockTypeInfo(BlockBreakInfo::instant()),
                $fakeWoodType,
                fn() => DummyItems::BAMBOO_SIGN()
            )
        );
    }
}