<?php

namespace diamondgold\DummyItemsBlocks\item\horn;

use pocketmine\utils\EnumTrait;

/**
 * @method static GoatHornType PONDER()
 * @method static GoatHornType SING()
 * @method static GoatHornType SEEK()
 * @method static GoatHornType FEEL()
 * @method static GoatHornType ADMIRE()
 * @method static GoatHornType CALL()
 * @method static GoatHornType YEARN()
 * @method static GoatHornType DREAM()
 */
final class GoatHornType
{
    use EnumTrait;

    protected static function setup(): void
    {
        self::registerAll(
            new self("ponder"),
            new self("sing"),
            new self("seek"),
            new self("feel"),
            new self("admire"),
            new self("call"),
            new self("yearn"),
            new self("dream")
        );
    }
}