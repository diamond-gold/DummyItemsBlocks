<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\block\trait\NoneSupportTrait;
use diamondgold\DummyItemsBlocks\Main;
use diamondgold\DummyItemsBlocks\tile\DummyTile;
use diamondgold\DummyItemsBlocks\tile\DummyTileTrait;
use diamondgold\DummyItemsBlocks\tile\TileNames;
use diamondgold\DummyItemsBlocks\tile\TileNbtTagNames;
use diamondgold\DummyItemsBlocks\util\Utils;
use pocketmine\block\tile\Tile;
use pocketmine\block\Transparent;
use pocketmine\block\utils\FacesOppositePlacingPlayerTrait;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\player\Player;
use pocketmine\utils\AssumptionFailedError;

class Campfire extends Transparent
{
    use FacesOppositePlacingPlayerTrait {
        describeBlockOnlyState as describeFacing;
    }
    use NoneSupportTrait;
    use DummyTileTrait;

    protected bool $extinguished = false;

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $this->describeFacing($w);
        $w->bool($this->extinguished);
    }

    public function isExtinguished(): bool
    {
        return $this->extinguished;
    }

    public function setExtinguished(bool $extinguished): self
    {
        $this->extinguished = $extinguished;
        return $this;
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!Main::canChangeBlockStates($this, $player)) return false;
        if ($player?->isSneaking()) {
            $this->position->getWorld()->setBlock($this->position, $this->setExtinguished(!$this->extinguished));
            return true;
        } elseif ($face === Facing::UP) {
            // currently only correct when block facing is north, HELP WANTED
            if ($this->facing !== Facing::NORTH) {
                $player?->sendTip("I am aware that the slot is incorrect. HELP WANTED. Place it while facing south for now.");
            }
            $index = match (true) {
                $clickVector->x >= 0.5 && $clickVector->z >= 0.5 => 1,
                $clickVector->x >= 0.5 && $clickVector->z <= 0.5 => 2,
                $clickVector->x <= 0.5 && $clickVector->z <= 0.5 => 3,
                $clickVector->x <= 0.5 && $clickVector->z >= 0.5 => 4,
                default => throw new AssumptionFailedError("Unreachable $clickVector"),
            };
            if (!$item->isNull() && !$this->getItem($index)->isNull()) {
                for ($i = 1; $i <= 4; $i++) {
                    if ($i !== $index && $this->getItem($i)->isNull()) {
                        $index = $i;
                        break;
                    }
                }
            }
            if (!$item->isNull()) {
                $pop = $item->pop();
            }
            $this->setItem($index, $pop ?? $item);
            return true;
        }
        return false;
    }

    /**
     * @param int $index 1-4
     * @return Item
     */
    public function getItem(int $index): Item
    {
        Utils::checkWithinBounds($index, 1, 4);
        $tile = $this->position->getWorld()->getTile($this->position);
        if ($tile instanceof DummyTile) {
            $nbt = $tile->saveNBT();
            $tagName = sprintf(TileNbtTagNames::Items, $index);
            $itemTag = $nbt->getTag($tagName);
            if ($itemTag instanceof CompoundTag) {
                return Item::nbtDeserialize($itemTag);
            }
        }
        return VanillaItems::AIR();
    }

    /**
     * @param int $index 1-4
     * @param Item $item
     * @return void
     */
    public function setItem(int $index, Item $item): void
    {
        Utils::checkWithinBounds($index, 1, 4);
        $tile = $this->position->getWorld()->getTile($this->position);
        if ($tile instanceof DummyTile) {
            $nbt = $tile->saveNBT();
            $tagName = sprintf(TileNbtTagNames::Items, $index);
            if ($item->isNull()) {
                $nbt->removeTag($tagName);
            } else {
                $nbt->setTag($tagName, $item->nbtSerialize());
            }
            $tile->readSaveData($nbt);
            $this->position->getWorld()->setBlock($this->position, $this);
        }
    }

    protected function writeDefaultTileData(CompoundTag $tag): void
    {
        $tag->setString(Tile::TAG_ID, TileNames::CAMPFIRE);
        for ($i = 1; $i <= 4; $i++) {
            $this->setTagIfNotExist($tag, sprintf(TileNbtTagNames::ItemTime, $i), new IntTag(0));
        }
        $this->setTagIfNotExist($tag, TileNbtTagNames::isMovable, new ByteTag(1));
    }

    protected function recalculateCollisionBoxes(): array
    {
        return [AxisAlignedBB::one()->trim(Facing::UP, 0.5)];
    }

    public function getLightLevel(): int
    {
        return $this->extinguished ? 0 : 15;
    }
}