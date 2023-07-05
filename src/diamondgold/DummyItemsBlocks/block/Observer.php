<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\block\trait\PoweredTrait;
use pocketmine\block\Block;
use pocketmine\block\Opaque;
use pocketmine\block\utils\AnyFacingTrait;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;

class Observer extends Opaque
{
    use AnyFacingTrait {
        AnyFacingTrait::describeBlockOnlyState as describeFacingState;
    }
    use PoweredTrait {
        PoweredTrait::describeBlockOnlyState as describePoweredState;
    }

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $this->describeFacingState($w);
        $this->describePoweredState($w);
    }

    public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null): bool
    {
        $this->setFacing(Facing::opposite($face));
        return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
    }
}