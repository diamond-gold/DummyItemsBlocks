<?php

namespace diamondgold\DummyItemsBlocks\util;

use pocketmine\block\tile\TileFactory;
use pocketmine\data\bedrock\block\BlockTypeNames;
use pocketmine\data\bedrock\item\ItemTypeNames;
use pocketmine\utils\AssumptionFailedError;
use ReflectionClass;
use Throwable;

/* @internal */
final class ReflectionHelper
{
    private function __construct()
    {
    }

    /**
     * @return string[]
     */
    public static function TileFactoryRegisteredTileIds(): array
    {
        // don't cache this, it will change during runtime
        try {
            $value = (new ReflectionClass(TileFactory::class))->getProperty("knownTiles")->getValue(TileFactory::getInstance());
            assert(is_array($value));
            /** @var string[] $known */
            $known = array_keys($value);
        } catch (Throwable $e) {
            throw new AssumptionFailedError("TileFactory reflection failed", 0, $e);
        }
        return $known;
    }

    /**
     * @return string[]
     */
    public static function ItemTypeNames(): array
    {
        static $known;
        if (!isset($known)) {
            try {
                $known = array_values((new ReflectionClass(ItemTypeNames::class))->getConstants());
            } catch (Throwable $e) {
                throw new AssumptionFailedError("ItemTypeNames reflection failed", 0, $e);
            }
        }
        return $known;
    }

    /**
     * @return string[]
     */
    public static function BlockTypeNames(): array
    {
        static $known;
        if (!isset($known)) {
            try {
                $known = array_values((new ReflectionClass(BlockTypeNames::class))->getConstants());
            } catch (Throwable $e) {
                throw new AssumptionFailedError("BlockTypeNames reflection failed", 0, $e);
            }
        }
        return $known;
    }
}