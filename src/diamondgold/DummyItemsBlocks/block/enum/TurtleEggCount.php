<?php

namespace diamondgold\DummyItemsBlocks\block\enum;

use pocketmine\data\bedrock\block\BlockStateStringValues;
use pocketmine\utils\EnumTrait;


/**
 * @method static TurtleEggCount ONE_EGG()
 * @method static TurtleEggCount TWO_EGG()
 * @method static TurtleEggCount THREE_EGG()
 * @method static TurtleEggCount FOUR_EGG()
 */
final class TurtleEggCount implements DummyEnum
{
    use EnumTrait;

    protected static function setup(): void
    {
        self::registerAll(
            new self(BlockStateStringValues::TURTLE_EGG_COUNT_ONE_EGG),
            new self(BlockStateStringValues::TURTLE_EGG_COUNT_TWO_EGG),
            new self(BlockStateStringValues::TURTLE_EGG_COUNT_THREE_EGG),
            new self(BlockStateStringValues::TURTLE_EGG_COUNT_FOUR_EGG)
        );
    }
}