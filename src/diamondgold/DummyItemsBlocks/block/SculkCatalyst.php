<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\block\trait\NoneSupportTrait;
use diamondgold\DummyItemsBlocks\Main;
use diamondgold\DummyItemsBlocks\tile\DummyTileTrait;
use diamondgold\DummyItemsBlocks\tile\TileNames;
use diamondgold\DummyItemsBlocks\tile\TileNbtTagNames;
use pocketmine\block\Opaque;
use pocketmine\block\tile\Tile;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;

class SculkCatalyst extends Opaque
{
    protected bool $bloom = false;

    use NoneSupportTrait;
    use DummyTileTrait;

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $w->bool($this->bloom);
    }

    public function isBloom(): bool
    {
        return $this->bloom;
    }

    public function setBloom(bool $bloom): self
    {
        $this->bloom = $bloom;
        return $this;
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!Main::canChangeBlockStates($this, $player)) return false;
        $this->position->getWorld()->setBlock($this->position, $this->setBloom(!$this->isBloom()));
        return true;
    }

    // why do you need a tile for this??? maybe have missing data?
    protected function writeDefaultTileData(CompoundTag $tag): void
    {
        $tag->setString(Tile::TAG_ID, TileNames::SCULK_CATALYST);
        $this->setTagIfNotExist($tag, TileNbtTagNames::isMovable, new ByteTag(1));
    }

    public function getLightLevel(): int
    {
        return 6;
    }
}