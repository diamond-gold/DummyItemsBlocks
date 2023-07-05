<?php

namespace diamondgold\DummyItemsBlocks\tile;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\Tag;
use pocketmine\utils\AssumptionFailedError;

trait DummyTileTrait
{
    /**
     * Writes default tile data as seen in vanilla, important to write id tag!
     * @param CompoundTag $tag
     * @return void
     */
    abstract protected function writeDefaultTileData(CompoundTag $tag): void;

    public function onPostPlace(): void
    {
        $tile = $this->position->getWorld()->getTile($this->position);
        if ($tile instanceof DummyTile) {
            $nbt = $tile->saveNBT();
            $this->writeDefaultTileData($nbt);
            $tile->readSaveData($nbt);
            $tile->clearSpawnCompoundCache();
            //$this->position->getWorld()->setBlock($this->position, $this);
        } elseif ($tile !== null) { // this should never happen
            throw new AssumptionFailedError("Expected DummyTile, got " . get_class($tile));
        }
    }

    /**
     * Helper function to ensure I don't overwrite existing tags
     * @param CompoundTag $compoundTag
     * @param string $name
     * @param Tag $tag
     * @return void
     */
    protected function setTagIfNotExist(CompoundTag $compoundTag, string $name, Tag $tag): void
    {
        if ($compoundTag->getTag($name)) {
            return;
        }
        $compoundTag->setTag($name, $tag);
    }
}