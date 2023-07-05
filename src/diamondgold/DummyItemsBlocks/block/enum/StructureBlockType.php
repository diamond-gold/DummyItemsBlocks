<?php

namespace diamondgold\DummyItemsBlocks\block\enum;

use pocketmine\data\bedrock\block\BlockStateStringValues;
use pocketmine\utils\EnumTrait;

/**
 * @method static StructureBlockType DATA()
 * @method static StructureBlockType SAVE()
 * @method static StructureBlockType LOAD()
 * @method static StructureBlockType CORNER()
 * @method static StructureBlockType EXPORT()
 * @method static StructureBlockType INVALID()
 */
final class StructureBlockType implements DummyEnum
{
    use EnumTrait;

    protected static function setup(): void
    {
        self::registerAll(
            new self(BlockStateStringValues::STRUCTURE_BLOCK_TYPE_DATA),
            new self(BlockStateStringValues::STRUCTURE_BLOCK_TYPE_SAVE),
            new self(BlockStateStringValues::STRUCTURE_BLOCK_TYPE_LOAD),
            new self(BlockStateStringValues::STRUCTURE_BLOCK_TYPE_CORNER),
            new self(BlockStateStringValues::STRUCTURE_BLOCK_TYPE_EXPORT),
            new self(BlockStateStringValues::STRUCTURE_BLOCK_TYPE_INVALID)
        );
    }
}