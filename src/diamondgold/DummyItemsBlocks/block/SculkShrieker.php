<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\block\trait\NoneSupportTrait;
use diamondgold\DummyItemsBlocks\Main;
use diamondgold\DummyItemsBlocks\tile\DummyTileTrait;
use diamondgold\DummyItemsBlocks\tile\TileNames;
use diamondgold\DummyItemsBlocks\tile\TileNbtTagNames;
use pocketmine\block\tile\Tile;
use pocketmine\block\Transparent;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;

class SculkShrieker extends Transparent
{
    protected bool $active = false;
    protected bool $canSummon = false;

    use NoneSupportTrait;
    use DummyTileTrait;

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $w->bool($this->active);
        $w->bool($this->canSummon);
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;
        return $this;
    }

    public function canSummon(): bool
    {
        return $this->canSummon;
    }

    public function setCanSummon(bool $canSummon): self
    {
        $this->canSummon = $canSummon;
        return $this;
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!Main::canChangeBlockStates($this, $player)) return false;
        $this->position->getWorld()->setBlock($this->position, $this->setCanSummon(!$this->canSummon));
        // active state visually no change
        return true;
    }

    protected function writeDefaultTileData(CompoundTag $tag): void
    {
        $tag->setString(Tile::TAG_ID, TileNames::SCULK_SHRIEKER);
        $this->setTagIfNotExist($tag, TileNbtTagNames::VibrationListener, CompoundTag::create()
            ->setTag(TileNbtTagNames::VibrationListener_selector, CompoundTag::create())
        );
        $this->setTagIfNotExist($tag, TileNbtTagNames::isMovable, new ByteTag(1));
    }
}