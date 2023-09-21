<?php

namespace diamondgold\DummyItemsBlocks\block\enum;

use pocketmine\data\bedrock\block\BlockStateStringValues;
use pocketmine\utils\EnumTrait;

/**
 * @method static self DOWN()
 * @method static self EAST()
 * @method static self NORTH()
 * @method static self SOUTH()
 * @method static self UP()
 * @method static self WEST()
 */
final class BlockFace implements DummyEnum
{
    use EnumTrait;

    protected static function setup(): void
    {
        self::registerAll(
            new self(BlockStateStringValues::MC_BLOCK_FACE_DOWN),
            new self(BlockStateStringValues::MC_BLOCK_FACE_EAST),
            new self(BlockStateStringValues::MC_BLOCK_FACE_NORTH),
            new self(BlockStateStringValues::MC_BLOCK_FACE_SOUTH),
            new self(BlockStateStringValues::MC_BLOCK_FACE_UP),
            new self(BlockStateStringValues::MC_BLOCK_FACE_WEST)
        );
    }
}