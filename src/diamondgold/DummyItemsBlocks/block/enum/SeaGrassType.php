<?php

namespace diamondgold\DummyItemsBlocks\block\enum;

use pocketmine\data\bedrock\block\BlockStateStringValues;
use pocketmine\utils\EnumTrait;

/**
 * @method static self DEFAULT()
 * @method static self DOUBLE_BOT()
 * @method static self DOUBLE_TOP()
 */
final class SeaGrassType implements DummyEnum
{
    use EnumTrait;

    protected static function setup(): void
    {
        self::registerAll(
            new self(BlockStateStringValues::SEA_GRASS_TYPE_DEFAULT),
            new self(BlockStateStringValues::SEA_GRASS_TYPE_DOUBLE_BOT),
            new self(BlockStateStringValues::SEA_GRASS_TYPE_DOUBLE_TOP),
        );
    }
}