<?php

namespace diamondgold\DummyItemsBlocks\block\trait;

use pocketmine\block\Block;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;

trait HangingTrait
{
    protected bool $hanging = false;

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $w->bool($this->hanging);
    }

    public function isHanging(): bool
    {
        return $this->hanging;
    }

    public function setHanging(bool $hanging): self
    {
        $this->hanging = $hanging;
        return $this;
    }

    public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null): bool
    {
        $this->hanging = $face === Facing::DOWN;
        return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
    }
}