<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\block\trait\HangingTrait;
use diamondgold\DummyItemsBlocks\Main;
use diamondgold\DummyItemsBlocks\tile\DummyTileTrait;
use diamondgold\DummyItemsBlocks\tile\LootTables;
use diamondgold\DummyItemsBlocks\tile\TileNames;
use diamondgold\DummyItemsBlocks\tile\TileNbtTagNames;
use diamondgold\DummyItemsBlocks\util\Utils;
use pocketmine\block\Opaque;
use pocketmine\block\tile\Tile;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\player\Player;
use pocketmine\world\format\io\GlobalBlockStateHandlers;

class SuspiciousFallable extends Opaque
{
    use HangingTrait {
        describeBlockOnlyState as describeHangingState;
    }
    use DummyTileTrait;

    protected int $brushedProgress = 0;

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $this->describeHangingState($w);
        $w->boundedIntAuto(0, 3, $this->brushedProgress);
    }

    public function getBrushedProgress(): int
    {
        return $this->brushedProgress;
    }

    public function setBrushedProgress(int $brushedProgress): self
    {
        Utils::checkWithinBounds($brushedProgress, 0, 3);
        $this->brushedProgress = $brushedProgress;
        return $this;
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!Main::canChangeBlockStates($this, $player)) return false;
        $this->position->getWorld()->setBlock($this->position, $this->setBrushedProgress((($this->brushedProgress + 1) % 4)));
        return true;
    }

    protected function writeDefaultTileData(CompoundTag $tag): void
    {
        $tag->setString(Tile::TAG_ID, TileNames::BRUSHABLE_BLOCK);
        $this->setTagIfNotExist($tag, TileNbtTagNames::LootTable, new StringTag(LootTables::EMPTY_BRUSHABLE_BLOCK->value));
        $this->setTagIfNotExist($tag, TileNbtTagNames::LootTableSeed, new IntTag(0));
        $this->setTagIfNotExist($tag, TileNbtTagNames::brush_count, new IntTag(0));
        $this->setTagIfNotExist($tag, TileNbtTagNames::brush_direction, new ByteTag(6));
        $this->setTagIfNotExist($tag, TileNbtTagNames::isMovable, new ByteTag(1));
        $this->setTagIfNotExist($tag, TileNbtTagNames::type, new StringTag(GlobalBlockStateHandlers::getSerializer()->serializeBlock($this)->getName()));
    }
}