<?php

namespace diamondgold\DummyItemsBlocks;

use Closure;
use InvalidArgumentException;
use pocketmine\block\Block;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\BlockTypeInfo;
use pocketmine\block\Opaque;
use pocketmine\block\RuntimeBlockStateRegistry;
use pocketmine\data\bedrock\block\BlockStateData;
use pocketmine\data\bedrock\block\BlockStateDeserializeException;
use pocketmine\data\bedrock\block\convert\UnsupportedBlockStateException;
use pocketmine\data\bedrock\item\ItemSerializerDeserializerRegistrar;
use pocketmine\data\bedrock\item\ItemTypeDeserializeException;
use pocketmine\data\bedrock\item\ItemTypeNames;
use pocketmine\data\bedrock\item\SavedItemData;
use pocketmine\data\bedrock\PotionTypeIdMap;
use pocketmine\inventory\CreativeInventory;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\item\ItemBlock;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemTypeIds;
use pocketmine\item\SplashPotion;
use pocketmine\item\StringToItemParser;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\plugin\DisablePluginException;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\AsyncTask;
use pocketmine\utils\AssumptionFailedError;
use pocketmine\world\format\io\GlobalBlockStateHandlers;
use pocketmine\world\format\io\GlobalItemDataHandlers;
use Throwable;

class Main extends PluginBase
{
    protected function onEnable(): void
    {
        $this->saveDefaultConfig();
        $config = $this->getConfig();
        //$config->set("blocks", (new \ReflectionClass(BlockTypeNames::class))->getConstants());
        $removedBlocks = [
            "minecraft:client_request_placeholder_block",
            "minecraft:moving_block",
        ];
        $blocks = $config->get("blocks", []);
        foreach ($blocks as $k => $id) {
            if (in_array($id, $removedBlocks, true)) {
                $this->getLogger()->warning("Block $id is intentionally removed!");
                unset($blocks[$k]);
            }
            try {
                if (GlobalBlockStateHandlers::getDeserializer()->deserializeBlock(BlockStateData::current($id, []))) {
                    $this->getLogger()->warning("Block $id is already registered!");
                    unset($blocks[$k]);
                }
            } catch (UnsupportedBlockStateException) {
                // not registered
            } catch (BlockStateDeserializeException $e) {
                // registered but missing properties when deserializing
                $this->getLogger()->debug($e->getMessage());
                $this->getLogger()->warning("Block $id is already registered!");
                unset($blocks[$k]);
            }
        }
        //$config->set("items", (new \ReflectionClass(ItemTypeNames::class))->getConstants());
        $items = $config->get("items", []);
        foreach ($items as $k => $id) {
            try {
                if (StringToItemParser::getInstance()->parse($id)) {
                    $this->getLogger()->warning("Item $id is in StringToItemParser!");
                    unset($items[$k]);
                    continue;
                }
                if (GlobalItemDataHandlers::getDeserializer()->deserializeType(new SavedItemData($id))) {
                    $this->getLogger()->warning("Item $id is already registered!");
                    unset($items[$k]);
                }
            } catch (Throwable) {
            }
        }
        $blocks = array_values($blocks);
        $items = array_values($items);
        if ($config->get("blocks") !== $blocks) {
            $config->set("blocks", $blocks);
        }
        if ($config->get("items") !== $items) {
            $config->set("items", $items);
        }
        if ($config->hasChanged()) {
            $config->save();
        }

        self::registerSpecialItems($items);

        self::registerBlocks($blocks);
        self::registerItems($items);

        // Server will crash if it tries to send these items to the client, blame PMMP
        $changed = false;
        foreach (CreativeInventory::getInstance()->getAll() as $item) {
            try {
                TypeConverter::getInstance()->coreItemStackToNet($item);
            } catch (AssumptionFailedError) {
                // Unmapped blockstate returned by blockstate serializer
                $alias = StringToItemParser::getInstance()->lookupAliases($item)[0];
                if ($item instanceof ItemBlock) {
                    $this->getLogger()->warning("Block $alias is not supported");
                    $key = array_search($alias, $blocks, true);
                    unset($blocks[$key]);
                } else {
                    $this->getLogger()->warning("Item $alias is not supported");
                    $key = array_search($alias, $items, true);
                    unset($items[$key]);
                }
                $changed = true;
            } catch (Throwable $e) {
                $alias = StringToItemParser::getInstance()->lookupAliases($item)[0];
                if ($item instanceof ItemBlock) {
                    $this->getLogger()->warning("Block $alias is not supported: " . $e->getMessage());
                    $key = array_search($alias, $blocks, true);
                    unset($blocks[$key]);
                } else {
                    $this->getLogger()->warning("Item $alias is not supported: " . $e->getMessage());
                    $key = array_search($alias, $items, true);
                    unset($items[$key]);
                }
                $changed = true;
            }
        }
        if ($changed) {
            $blocks = array_values($blocks);
            $items = array_values($items);
            $config->set("items", $items);
            $config->set("blocks", $blocks);
            $config->save();
            $this->getLogger()->emergency("Server restart required to remove unsupported items");
            throw new DisablePluginException();
        }
        /*
        // reload creative inventory from json file
        // pro: no need to add items manually
        // con: likely incompatible with other plugins that add to creative inventory

        CreativeInventory::getInstance()->clear();
        $creativeItems = CraftingManagerFromDataHelper::loadJsonArrayOfObjectsFile(
            BedrockDataFiles::CREATIVEITEMS_JSON,
            ItemStackData::class
        );
        foreach ($creativeItems as $data) {
            $item = CraftingManagerFromDataHelper::deserializeItemStack($data);
            if ($item === null) {
                $this->getLogger()->debug("Creative item $data->name");
                continue;
            }
            CreativeInventory::getInstance()->add($item);
        }
        */
        $this->getServer()->getAsyncPool()->addWorkerStartHook(function (int $worker) use ($blocks, $items): void {
            $this->getServer()->getAsyncPool()->submitTaskToWorker(new class(serialize($blocks), serialize($items)) extends AsyncTask {

                public function __construct(private string $blocksSerialized, private string $itemsSerialized)
                {

                }

                public function onRun(): void
                {
                    $items = unserialize($this->itemsSerialized);
                    Main::registerSpecialItems($items);
                    Main::registerBlocks(unserialize($this->blocksSerialized));
                    Main::registerItems($items);
                }
            }, $worker);
        });
    }

    private static function generateNameFromId(string $id): string
    {
        $id = str_replace('minecraft:', '', $id);
        $words = explode('_', $id);
        $convertedText = '';
        foreach ($words as $word) {
            $convertedText .= ucfirst($word) . ' ';
        }
        return trim($convertedText);
    }

    /**
     * @param string[] $blocks
     * @return void
     */
    public static function registerBlocks(array $blocks): void
    {
        foreach ($blocks as $id) {
            self::registerSimpleBlock($id, new Opaque(new BlockIdentifier(BlockTypeIds::newId()), self::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant())), [$id]);
        }
    }

    /**
     * @param string[] $items
     * @return void
     */
    public static function registerItems(array $items): void
    {
        foreach ($items as $id) {
            self::registerSimpleItem($id, new Item(new ItemIdentifier(ItemTypeIds::newId()), self::generateNameFromId($id)), [$id]);
        }
    }

    public static function registerSpecialItems(array &$items): void
    {
        if (in_array(ItemTypeNames::LINGERING_POTION, $items, true)) {
            $key = array_search(ItemTypeNames::LINGERING_POTION, $items, true);
            unset($items[$key]);
            $item = new SplashPotion(new ItemIdentifier(ItemTypeIds::newId()), self::generateNameFromId(ItemTypeNames::LINGERING_POTION));
            self::map1to1ItemWithMeta(
                ItemTypeNames::LINGERING_POTION,
                $item,
                function (SplashPotion $item, int $meta): void {
                    $item->setType(PotionTypeIdMap::getInstance()->fromId($meta) ?? throw new ItemTypeDeserializeException("Unknown potion type ID $meta"));
                },
                fn(SplashPotion $item) => PotionTypeIdMap::getInstance()->toId($item->getType())
            );
            StringToItemParser::getInstance()->register(ItemTypeNames::LINGERING_POTION, fn() => clone $item);
            //Already added to creative inventory automagically
        }
    }

    /**
     * @param string[] $stringToItemParserNames
     */
    private static function registerSimpleBlock(string $id, Block $block, array $stringToItemParserNames): void
    {
        RuntimeBlockStateRegistry::getInstance()->register($block);

        GlobalBlockStateHandlers::getDeserializer()->mapSimple($id, fn() => clone $block);
        GlobalBlockStateHandlers::getSerializer()->mapSimple($block, $id);

        foreach ($stringToItemParserNames as $name) {
            StringToItemParser::getInstance()->registerBlock($name, fn() => clone $block);
        }
        CreativeInventory::getInstance()->add($block->asItem());
    }

    /**
     * @param string[] $stringToItemParserNames
     */
    private static function registerSimpleItem(string $id, Item $item, array $stringToItemParserNames): void
    {
        GlobalItemDataHandlers::getDeserializer()->map($id, fn() => clone $item);
        GlobalItemDataHandlers::getSerializer()->map($item, fn() => new SavedItemData($id));

        foreach ($stringToItemParserNames as $name) {
            try {
                StringToItemParser::getInstance()->register($name, fn() => clone $item);
            } catch (InvalidArgumentException) {
                //var_dump("Already registered: $id $name");
                return;
            }
        }
        if ($id === ItemTypeNames::ENCHANTED_BOOK) { // has to be added here else weird duplicates appear in creative inventory, client issue?
            foreach (VanillaEnchantments::getAll() as $enchantment) {
                for ($i = 1; $i <= $enchantment->getMaxLevel(); $i++) {
                    CreativeInventory::getInstance()->add((clone $item)->addEnchantment(new EnchantmentInstance($enchantment, $i)));
                }
            }
        } else {
            CreativeInventory::getInstance()->add($item);
        }
    }

    /**
     * @link ItemSerializerDeserializerRegistrar::map1to1ItemWithMeta()
     * @phpstan-template TBlock of Block
     * @phpstan-param TBlock $block
     * @phpstan-param Closure(TBlock, int) : void $deserializeMeta
     * @phpstan-param Closure(TBlock) : int $serializeMeta
     */
    private static function map1to1ItemWithMeta(string $id, Item $item, Closure $deserializeMeta, Closure $serializeMeta): void
    {
        GlobalItemDataHandlers::getDeserializer()->map($id, function (SavedItemData $data) use ($item, $deserializeMeta): Item {
            $result = clone $item;
            $deserializeMeta($result, $data->getMeta());
            return $result;
        });
        GlobalItemDataHandlers::getSerializer()->map($item, function (Item $item) use ($id, $serializeMeta): SavedItemData {
            $meta = $serializeMeta($item);
            return new SavedItemData($id, $meta);
        });
    }
}