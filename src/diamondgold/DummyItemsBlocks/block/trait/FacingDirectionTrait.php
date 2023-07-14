<?php

namespace diamondgold\DummyItemsBlocks\block\trait;

use diamondgold\DummyItemsBlocks\block\enum\FacingDirection;
use diamondgold\DummyItemsBlocks\block\hack\HackStringProperty;
use pocketmine\block\Block;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\data\runtime\RuntimeDataReader;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\utils\AssumptionFailedError;
use pocketmine\world\BlockTransaction;

trait FacingDirectionTrait
{
    protected FacingDirection $facingDirection;
    protected HackStringProperty $facingDirectionHack;


    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        if ($w instanceof RuntimeDataReader) {
            $this->facingDirectionHack->read($this->facingDirection, $w);
        } else {
            $this->facingDirectionHack->write($this->facingDirection, $w);
        }
    }

    public function getFacingDirection(): FacingDirection
    {
        return $this->facingDirection;
    }

    public function setFacingDirection(FacingDirection $facingDirection): self
    {
        $this->facingDirection = $facingDirection;
        return $this;
    }

    public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null): bool
    {
        $this->setFacingDirection(match (Facing::opposite($face)) {
            Facing::DOWN => FacingDirection::DOWN(),
            Facing::UP => FacingDirection::UP(),
            Facing::NORTH => FacingDirection::NORTH(),
            Facing::SOUTH => FacingDirection::SOUTH(),
            Facing::WEST => FacingDirection::WEST(),
            Facing::EAST => FacingDirection::EAST(),
            default => throw new AssumptionFailedError("Invalid facing direction " . Facing::opposite($face)),
        });
        return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
    }
}