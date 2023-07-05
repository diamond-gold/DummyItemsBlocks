<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\tile\DummyTileTrait;
use diamondgold\DummyItemsBlocks\tile\TileNames;
use diamondgold\DummyItemsBlocks\tile\TileNbtTagNames;
use pocketmine\block\Block;
use pocketmine\block\Opaque;
use pocketmine\block\tile\Container;
use pocketmine\block\tile\Tile;
use pocketmine\block\utils\AnyFacingTrait;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\player\Player;
use pocketmine\utils\AssumptionFailedError;
use pocketmine\world\BlockTransaction;

class Dispenser extends Opaque
{
    use AnyFacingTrait;
    use DummyTileTrait;

    protected bool $triggered = false;

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $w->facing($this->facing);
        $w->bool($this->triggered);
    }

    public function isTriggered(): bool
    {
        return $this->triggered;
    }

    public function setTriggered(bool $triggered): self
    {
        $this->triggered = $triggered;
        return $this;
    }

    public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null): bool
    {
        $this->setFacing($face);
        return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
    }

    protected function writeDefaultTileData(CompoundTag $tag): void
    {
        $tag->setString(Tile::TAG_ID, in_array($this->getName(), [TileNames::DISPENSER, TileNames::DROPPER]) ? $this->getName() : throw new AssumptionFailedError("Invalid dispenser name " . $this->getName())); //hack
        $this->setTagIfNotExist($tag, Container::TAG_ITEMS, new ListTag([]));
        $this->setTagIfNotExist($tag, TileNbtTagNames::isMovable, new ByteTag(1));
    }
}