<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\block\trait\NoneSupportTrait;
use pocketmine\block\Block;
use pocketmine\block\Transparent;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;

class BubbleColumn extends Transparent
{
    use NoneSupportTrait;

    protected bool $drag_down = false;

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $w->bool($this->drag_down);
    }

    public function getDragDown(): bool
    {
        return $this->drag_down;
    }

    public function setDragDown(bool $drag_down): self
    {
        $this->drag_down = $drag_down;
        return $this;
    }

    public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null): bool
    {
        if ($player?->getLocation()->getPitch() > 0) {
            $this->drag_down = true;
        }
        return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
    }

    public function canBeReplaced(): bool
    {
        return true;
    }
}