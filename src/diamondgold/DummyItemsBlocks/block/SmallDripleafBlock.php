<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\block\trait\NoneSupportTrait;
use diamondgold\DummyItemsBlocks\block\trait\UpperTrait;
use pocketmine\block\Block;
use pocketmine\block\Transparent;
use pocketmine\block\utils\FacesOppositePlacingPlayerTrait;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;

class SmallDripleafBlock extends Transparent
{
    use FacesOppositePlacingPlayerTrait {
        FacesOppositePlacingPlayerTrait::describeBlockOnlyState as describeFacing;
    }
    use UpperTrait {
        UpperTrait::describeBlockOnlyState as describeUpper;
    }
    use NoneSupportTrait;

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $this->describeFacing($w);
        $this->describeUpper($w);
    }

    public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null): bool
    {
        $block = $blockReplace->getSide(Facing::DOWN);
        if ($player !== null) {
            $this->facing = Facing::opposite($player->getHorizontalFacing());
        }
        if ($block instanceof self && $block->hasSameTypeId($this)) {
            $this->facing = $block->getFacing();
            $tx->addBlock($block->getPosition(), (clone $block)->setUpper(false));
        }
        $this->upper = true;
        return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
    }
}