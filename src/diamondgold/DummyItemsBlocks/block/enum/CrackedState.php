<?php

namespace diamondgold\DummyItemsBlocks\block\enum;

use pocketmine\data\bedrock\block\BlockStateStringValues;
use pocketmine\utils\EnumTrait;

/**
 * @method static CrackedState NO_CRACKS()
 * @method static CrackedState CRACKED()
 * @method static CrackedState MAX_CRACKED()
 */
final class CrackedState implements DummyEnum
{
    use EnumTrait;

    protected static function setup(): void
    {
        self::registerAll(
            new self(BlockStateStringValues::CRACKED_STATE_NO_CRACKS),
            new self(BlockStateStringValues::CRACKED_STATE_CRACKED),
            new self(BlockStateStringValues::CRACKED_STATE_MAX_CRACKED),
        );
    }
}