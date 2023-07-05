<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\Main;
use diamondgold\DummyItemsBlocks\tile\DummyTileTrait;
use diamondgold\DummyItemsBlocks\tile\TileNames;
use diamondgold\DummyItemsBlocks\tile\TileNbtTagNames;
use pocketmine\block\Block;
use pocketmine\block\Opaque;
use pocketmine\block\tile\Nameable;
use pocketmine\block\tile\Tile;
use pocketmine\block\utils\AnyFacingTrait;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\LongTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;

class CommandBlock extends Opaque
{
    use AnyFacingTrait {
        describeBlockOnlyState as describeAnyFacingBlockOnlyState;
    }
    use DummyTileTrait;

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $this->describeAnyFacingBlockOnlyState($w);
        $w->bool($this->conditional);
    }

    protected bool $conditional = false;

    public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null): bool
    {
        $this->setFacing(Facing::opposite($face));
        return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
    }

    public function isConditional(): bool
    {
        return $this->conditional;
    }

    public function setConditional(bool $conditional): self
    {
        $this->conditional = $conditional;
        return $this;
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!Main::canChangeBlockStates($this, $player)) return false;
        $this->position->getWorld()->setBlock($this->position, $this->setConditional(!$this->isConditional()));
        return true;
    }

    protected function writeDefaultTileData(CompoundTag $tag): void
    {
        $tag->setString(Tile::TAG_ID, TileNames::COMMAND_BLOCK);
        $this->setTagIfNotExist($tag, TileNbtTagNames::Command, new StringTag(""));
        $this->setTagIfNotExist($tag, Nameable::TAG_CUSTOM_NAME, new StringTag(""));
        $this->setTagIfNotExist($tag, TileNbtTagNames::ExecuteOnFirstTick, new ByteTag(0));
        $this->setTagIfNotExist($tag, TileNbtTagNames::LPCommandMode, new IntTag(0));
        $this->setTagIfNotExist($tag, TileNbtTagNames::LPConditionalMode, new ByteTag(0));
        $this->setTagIfNotExist($tag, TileNbtTagNames::LPRedstoneMode, new ByteTag(0));
        $this->setTagIfNotExist($tag, TileNbtTagNames::LastExecution, new LongTag(0));
        $this->setTagIfNotExist($tag, TileNbtTagNames::LastOutput, new StringTag(""));
        $this->setTagIfNotExist($tag, TileNbtTagNames::SuccessCount, new IntTag(0));
        $this->setTagIfNotExist($tag, TileNbtTagNames::TickDelay, new IntTag(0));
        $this->setTagIfNotExist($tag, TileNbtTagNames::TrackOutput, new ByteTag(1));
        $this->setTagIfNotExist($tag, TileNbtTagNames::Version, new IntTag(34));
        $this->setTagIfNotExist($tag, TileNbtTagNames::auto, new ByteTag(0));
        $this->setTagIfNotExist($tag, TileNbtTagNames::conditionMet, new ByteTag(0));
        $this->setTagIfNotExist($tag, TileNbtTagNames::isMovable, new ByteTag(1));
        $this->setTagIfNotExist($tag, TileNbtTagNames::powered, new ByteTag(0));
    }
}