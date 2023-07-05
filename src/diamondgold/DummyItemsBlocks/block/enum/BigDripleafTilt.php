<?php

namespace diamondgold\DummyItemsBlocks\block\enum;

use pocketmine\data\bedrock\block\BlockStateStringValues;
use pocketmine\utils\EnumTrait;

/**
 * @method static self FULL_TILT()
 * @method static self NONE()
 * @method static self PARTIAL_TILT()
 * @method static self UNSTABLE()
 */
final class BigDripleafTilt implements DummyEnum
{
    use EnumTrait;

    protected static function setup(): void
    {
        self::registerAll(
            new self(BlockStateStringValues::BIG_DRIPLEAF_TILT_FULL_TILT),
            new self(BlockStateStringValues::BIG_DRIPLEAF_TILT_NONE),
            new self(BlockStateStringValues::BIG_DRIPLEAF_TILT_PARTIAL_TILT),
            new self(BlockStateStringValues::BIG_DRIPLEAF_TILT_UNSTABLE)
        );
    }
}