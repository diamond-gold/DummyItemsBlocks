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
            /** @var string[] $known */
            $known = array_keys((new ReflectionClass(TileFactory::class))->getProperty("knownTiles")->getValue(TileFactory::getInstance()));
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
                // remove these when updating to 5.2
                foreach (self::BlockTypeNames() as $name) {
                    if (str_contains($name, '_hanging_sign') && !in_array($name, $known, true)) {
                        $known[] = $name;
                    }
                }
                foreach ($known as $key => $name) {
                    if (str_contains($name, 'pottery_shard')) {
                        $known[$key] = str_replace('pottery_shard', 'pottery_sherd', $name);
                    }
                }
                foreach ([
                             "minecraft:cherry_boat",
                             "minecraft:cherry_chest_boat",
                             "minecraft:cherry_sign",
                             "minecraft:bamboo_sign",
                         ] as $name) {
                    if (!in_array($name, $known, true)) {
                        $known[] = $name;
                    }
                }
                foreach ([BlockTypeNames::BAMBOO_DOOR, BlockTypeNames::CHERRY_DOOR] as $name) {
                    if (!in_array($name, $known, true)) {
                        $known[] = $name;
                    }
                }
                $key = array_search("minecraft:debug_stick", $known, true);
                if ($key !== false) {
                    unset($known[$key]);
                }
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