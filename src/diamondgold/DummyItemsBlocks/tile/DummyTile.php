<?php

namespace diamondgold\DummyItemsBlocks\tile;

use pocketmine\block\tile\Spawnable;
use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\VersionInfo;

final class DummyTile extends Spawnable
{
    protected ?CompoundTag $nbt = null;

    protected function addAdditionalSpawnData(CompoundTag $nbt): void
    {
        $this->writeSaveData($nbt); // IDK which tags affect appearance, so just send the whole thing :P
        // can't override getSpawnCompound()... override id here instead
        $id = $this->nbt?->getString(self::TAG_ID, "");
        if (!empty($id)) {
            $nbt->setString(self::TAG_ID, $id); // TileFactory will write the wrong id, so we need to override it here
        }
    }

    /**
     * Note: This is different from server implementation, id tag should be included, if not TileFactory will write the wrong id!
     * When TileFactory call this, it provides full nbt including id, so ok.
     * When placed, copyDataFromItem() > onPostPlace() > saveNBT() > writeDefaultTileData() > readSaveData(), by the time this is called, the full nbt is already set, so ok.
     * If called from other places with the id tag excluded, then the wrong id will be saved!
     * @param CompoundTag $nbt
     * @return void
     */
    public function readSaveData(CompoundTag $nbt): void
    {
        $newId = $nbt->getString(self::TAG_ID, "");
        $oldId = $this->nbt?->getString(self::TAG_ID, "");
        if (empty($newId) && !empty($oldId)) { // id tag not included, perform best-effort attempt to restore from old nbt
            $nbt->setString(self::TAG_ID, $oldId);
        }
        $this->nbt = $nbt;
    }

    protected function writeSaveData(CompoundTag $nbt): void
    {
        if ($this->nbt === null) {
            return;
        }
        foreach ($this->nbt as $key => $value) {
            if (!in_array($key, [self::TAG_ID, self::TAG_X, self::TAG_Y, self::TAG_Z, VersionInfo::TAG_WORLD_DATA_VERSION], true)) { // other tiles don't write these tags here, so we don't either
                $nbt->setTag($key, $value);
            }
        }
    }

    public function saveNBT(): CompoundTag
    {
        $nbt = parent::saveNBT(); // TileFactory will return the wrong id
        $id = $this->nbt?->getString(self::TAG_ID, "");
        if (empty($id)) { // id not set yet... no choice but to return the default
            return $nbt;
        }
        $nbt->setString(self::TAG_ID, $id); // override with correct id
        return $nbt;
    }

    // sorta server bug fix, server only copy from customBlockData, but bedrock directly use the whole item nbt, so we need to copy from root too for vanilla item compatibility
    public function copyDataFromItem(Item $item): void
    {
        $blockNbt = $item->getCustomBlockData();
        if ($blockNbt === null) {
            $blockNbt = $item->getNamedTag();
            // remove item only tags, not sure if vanilla does this
            // $blockNbt = $item->getNamedTag()->safeClone();
            // $blockNbt->removeTag(Item::TAG_DISPLAY, Item::TAG_ENCH, Item::TAG_BLOCK_ENTITY_TAG, Item::TAG_KEEP_ON_DEATH, "CanPlaceOn", "CanDestroy");
        }
        $this->readSaveData($blockNbt); // copyDataFromItem() is called before onPostPlace(), so we can safely set the nbt even if it is without id tag
    }
}