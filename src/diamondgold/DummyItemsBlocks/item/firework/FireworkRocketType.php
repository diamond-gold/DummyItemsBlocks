<?php

namespace diamondgold\DummyItemsBlocks\item\firework;

use pocketmine\utils\EnumTrait;

/**
 * @method static FireworkRocketType SMALL_BALL()
 * @method static FireworkRocketType LARGE_BALL()
 * @method static FireworkRocketType STAR()
 * @method static FireworkRocketType CREEPER()
 * @method static FireworkRocketType BURST()
 */
final class FireworkRocketType
{
    use EnumTrait;

    protected static function setup(): void
    {
        self::registerAll(
            new self("small_ball"),
            new self("large_ball"),
            new self("star"),
            new self("creeper"),
            new self("burst"),
        );
    }
}