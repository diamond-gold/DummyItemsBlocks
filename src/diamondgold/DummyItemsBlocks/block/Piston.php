<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\Main;
use diamondgold\DummyItemsBlocks\tile\DummyTile;
use diamondgold\DummyItemsBlocks\tile\DummyTileTrait;
use diamondgold\DummyItemsBlocks\tile\TileNames;
use diamondgold\DummyItemsBlocks\tile\TileNbtTagNames;
use pocketmine\block\Block;
use pocketmine\block\Opaque;
use pocketmine\block\tile\Tile;
use pocketmine\block\utils\AnyFacingTrait;
use pocketmine\data\bedrock\block\BlockTypeNames;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;
use pocketmine\world\format\io\GlobalBlockStateHandlers;

class Piston extends Opaque
{
    use AnyFacingTrait;
    use DummyTileTrait;

    public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null): bool
    {
        $this->setFacing(Facing::opposite($face));
        return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!Main::canChangeBlockStates($this, $player)) return false;
        $tile = $this->position->getWorld()->getTile($this->position);
        if ($tile instanceof DummyTile) {
            $nbt = $tile->saveNBT();
            $nbt->setByte(TileNbtTagNames::State, $nbt->getByte(TileNbtTagNames::State) === 0 ? 2 : 0); // 0 = retracted, 2 = extended
            $tile->readSaveData($nbt);
            $this->position->getWorld()->setBlock($this->position, $this, false);
            return true;
        }
        return false;
    }

    protected function writeDefaultTileData(CompoundTag $tag): void
    {
        $tag->setString(Tile::TAG_ID, TileNames::PISTON_ARM);
        $this->setTagIfNotExist($tag, TileNbtTagNames::AttachedBlocks, new ListTag([]));
        $this->setTagIfNotExist($tag, TileNbtTagNames::BreakBlocks, new ListTag([]));
        $this->setTagIfNotExist($tag, TileNbtTagNames::LastProgress, new FloatTag(0));
        $this->setTagIfNotExist($tag, TileNbtTagNames::NewState, new ByteTag(0));
        $this->setTagIfNotExist($tag, TileNbtTagNames::Progress, new FloatTag(0));
        $this->setTagIfNotExist($tag, TileNbtTagNames::State, new ByteTag(0)); // appear as extended if 1 or 2, in vanilla value of 2 is seen in fully extended piston
        $this->setTagIfNotExist($tag, TileNbtTagNames::Sticky, new ByteTag((int)(GlobalBlockStateHandlers::getSerializer()->serializeBlock($this)->getName() === BlockTypeNames::STICKY_PISTON))); // appear as sticky if 1, doesn't matter if block is not sticky
        $this->setTagIfNotExist($tag, TileNbtTagNames::isMovable, new ByteTag(1));
    }
}