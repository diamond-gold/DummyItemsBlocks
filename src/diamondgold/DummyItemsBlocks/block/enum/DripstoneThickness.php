<?php

namespace diamondgold\DummyItemsBlocks\block\enum;

use pocketmine\data\bedrock\block\BlockStateStringValues;
use pocketmine\utils\EnumTrait;

/**
 * @method static self BASE()
 * @method static self FRUSTUM()
 * @method static self MERGE()
 * @method static self MIDDLE()
 * @method static self TIP()
 */
final class DripstoneThickness implements DummyEnum
{
    use EnumTrait;

    protected static function setup(): void
    {
        self::registerAll(
            new self(BlockStateStringValues::DRIPSTONE_THICKNESS_BASE),
            new self(BlockStateStringValues::DRIPSTONE_THICKNESS_FRUSTUM),
            new self(BlockStateStringValues::DRIPSTONE_THICKNESS_MERGE),
            new self(BlockStateStringValues::DRIPSTONE_THICKNESS_MIDDLE),
            new self(BlockStateStringValues::DRIPSTONE_THICKNESS_TIP)
        );
    }
}