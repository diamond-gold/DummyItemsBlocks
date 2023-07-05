<?php

namespace diamondgold\DummyItemsBlocks\block\enum;

use pocketmine\data\bedrock\block\BlockStateStringValues;
use pocketmine\utils\EnumTrait;

/**
 * @method static StructureVoidType AIR()
 * @method static StructureVoidType VOID()
 */
final class StructureVoidType implements DummyEnum
{
    use EnumTrait;


    protected static function setup(): void
    {
        self::registerAll(
            new self(BlockStateStringValues::STRUCTURE_VOID_TYPE_AIR),
            new self(BlockStateStringValues::STRUCTURE_VOID_TYPE_VOID)
        );
    }
}