<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\block\enum\Orientation;
use diamondgold\DummyItemsBlocks\Main;
use diamondgold\DummyItemsBlocks\tile\DummyTileTrait;
use diamondgold\DummyItemsBlocks\tile\TileNames;
use diamondgold\DummyItemsBlocks\tile\TileNbtTagNames;
use pocketmine\block\Block;
use pocketmine\block\Opaque;
use pocketmine\block\tile\Container;
use pocketmine\block\tile\Tile;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\player\Player;
use pocketmine\utils\AssumptionFailedError;
use pocketmine\world\BlockTransaction;

final class Crafter extends Opaque
{
    use DummyTileTrait;

    private bool $crafting = false;
    private bool $triggered = false;
    private Orientation $orientation = Orientation::DOWN_EAST;

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $w->bool($this->crafting);
        $w->bool($this->triggered);
        $w->enum($this->orientation);
    }

    public function isCrafting(): bool
    {
        return $this->crafting;
    }

    public function setCrafting(bool $crafting): self
    {
        $this->crafting = $crafting;
        return $this;
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

    public function getOrientation(): Orientation
    {
        return $this->orientation;
    }

    public function setOrientation(Orientation $orientation): self
    {
        $this->orientation = $orientation;
        return $this;
    }

    public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null): bool
    {
        $this->setOrientation(match (Facing::opposite($face)) {
            Facing::UP => match ($player?->getHorizontalFacing()) {
                Facing::NORTH, null => Orientation::DOWN_SOUTH,
                Facing::SOUTH => Orientation::DOWN_NORTH,
                Facing::WEST => Orientation::DOWN_EAST,
                Facing::EAST => Orientation::DOWN_WEST,
                default => throw new AssumptionFailedError("Invalid player facing direction " . $player?->getHorizontalFacing()),

            },
            Facing::DOWN => match ($player?->getHorizontalFacing()) {
                Facing::NORTH, null => Orientation::UP_SOUTH,
                Facing::SOUTH => Orientation::UP_NORTH,
                Facing::WEST => Orientation::UP_EAST,
                Facing::EAST => Orientation::UP_WEST,
                default => throw new AssumptionFailedError("Invalid player facing direction " . $player?->getHorizontalFacing()),

            },
            Facing::NORTH => Orientation::SOUTH_UP,
            Facing::SOUTH => Orientation::NORTH_UP,
            Facing::WEST => Orientation::EAST_UP,
            Facing::EAST => Orientation::WEST_UP,
            default => throw new AssumptionFailedError("Invalid facing direction " . Facing::opposite($face)),
        });
        return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!Main::canChangeBlockStates($this, $player)) return false;
        if ($player?->isSneaking()) {
            $this->position->getWorld()->setBlock($this->position, $this->setTriggered(!$this->isTriggered()));
            $player->sendTip("Triggered: " . ($this->isTriggered() ? "true" : "false"));
        } else {
            $this->position->getWorld()->setBlock($this->position, $this->setCrafting(!$this->isCrafting()));
            $player?->sendTip("Crafting: " . ($this->isCrafting() ? "true" : "false"));
        }
        return true;
    }

    protected function writeDefaultTileData(CompoundTag $tag): void
    {
        $tag->setString(Tile::TAG_ID, TileNames::CRAFTER);
        $this->setTagIfNotExist($tag, TileNbtTagNames::crafting_ticks_remaining, new IntTag(0));
        $this->setTagIfNotExist($tag, TileNbtTagNames::triggered, new IntTag(0));
        $this->setTagIfNotExist($tag, TileNbtTagNames::disabled_slots, new ShortTag(0));
        $this->setTagIfNotExist($tag, Container::TAG_ITEMS, new ListTag());
    }
}