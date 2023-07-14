<?php

namespace diamondgold\DummyItemsBlocks\block\enum;

use pocketmine\data\bedrock\block\BlockStateStringValues;
use pocketmine\utils\EnumTrait;

/**
 * @method static FacingDirection DOWN()
 * @method static FacingDirection UP()
 * @method static FacingDirection NORTH()
 * @method static FacingDirection SOUTH()
 * @method static FacingDirection WEST()
 * @method static FacingDirection EAST()
 */
final class FacingDirection implements DummyEnum
{
    use EnumTrait;

    protected static function setup(): void
    {
        self::registerAll(
            new self(BlockStateStringValues::MC_FACING_DIRECTION_DOWN),
            new self(BlockStateStringValues::MC_FACING_DIRECTION_UP),
            new self(BlockStateStringValues::MC_FACING_DIRECTION_NORTH),
            new self(BlockStateStringValues::MC_FACING_DIRECTION_SOUTH),
            new self(BlockStateStringValues::MC_FACING_DIRECTION_WEST),
            new self(BlockStateStringValues::MC_FACING_DIRECTION_EAST)
        );
    }
}