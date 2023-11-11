<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\block\enum\StructureBlockType;
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
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\LongTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\player\Player;

class StructureBlock extends Opaque
{
    use DummyTileTrait;

    protected StructureBlockType $type = StructureBlockType::DATA;

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $w->enum($this->type);
    }

    public function getType(): StructureBlockType
    {
        return $this->type;
    }

    public function setType(StructureBlockType $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!Main::canChangeBlockStates($this, $player)) return false;
        $this->position->getWorld()->setBlock($this->position, $this->setType(match ($this->getType()) {
            StructureBlockType::DATA => StructureBlockType::SAVE,
            StructureBlockType::SAVE => StructureBlockType::LOAD,
            StructureBlockType::LOAD => StructureBlockType::CORNER,
            StructureBlockType::CORNER => StructureBlockType::INVALID,
            StructureBlockType::INVALID => StructureBlockType::EXPORT,
            StructureBlockType::EXPORT => StructureBlockType::DATA,
        }));
        $player?->sendTip("StructureBlockType: " . $this->getType()->name);
        return true;
    }

    protected function writeDefaultTileData(CompoundTag $tag): void
    {
        $tag->setString(Tile::TAG_ID, TileNames::STRUCTURE_BLOCK);
        $this->setTagIfNotExist($tag, TileNbtTagNames::animationMode, new ByteTag(0));
        $this->setTagIfNotExist($tag, TileNbtTagNames::animationSeconds, new FloatTag(0));
        $this->setTagIfNotExist($tag, TileNbtTagNames::data, new IntTag(1));
        $this->setTagIfNotExist($tag, TileNbtTagNames::dataField, new StringTag(""));
        $this->setTagIfNotExist($tag, TileNbtTagNames::ignoreEntities, new ByteTag(0));
        $this->setTagIfNotExist($tag, TileNbtTagNames::includePlayers, new ByteTag(0));
        $this->setTagIfNotExist($tag, TileNbtTagNames::integrity, new FloatTag(100));
        $this->setTagIfNotExist($tag, TileNbtTagNames::isMovable, new ByteTag(1));
        $this->setTagIfNotExist($tag, TileNbtTagNames::isPowered, new ByteTag(0));
        $this->setTagIfNotExist($tag, TileNbtTagNames::mirror, new ByteTag(0));
        $this->setTagIfNotExist($tag, TileNbtTagNames::redstoneSaveMode, new IntTag(0));
        $this->setTagIfNotExist($tag, TileNbtTagNames::removeBlocks, new ByteTag(0));
        $this->setTagIfNotExist($tag, TileNbtTagNames::rotation, new ByteTag(0));
        $this->setTagIfNotExist($tag, TileNbtTagNames::seed, new LongTag(0));
        $this->setTagIfNotExist($tag, TileNbtTagNames::showBoundingBox, new ByteTag(1));
        $this->setTagIfNotExist($tag, TileNbtTagNames::structureName, new StringTag(""));
        $this->setTagIfNotExist($tag, TileNbtTagNames::xStructureOffset, new IntTag(0));
        $this->setTagIfNotExist($tag, TileNbtTagNames::xStructureSize, new IntTag(5));
        $this->setTagIfNotExist($tag, TileNbtTagNames::yStructureOffset, new IntTag(-1));
        $this->setTagIfNotExist($tag, TileNbtTagNames::yStructureSize, new IntTag(5));
        $this->setTagIfNotExist($tag, TileNbtTagNames::zStructureOffset, new IntTag(0));
        $this->setTagIfNotExist($tag, TileNbtTagNames::zStructureSize, new IntTag(5));
    }
}