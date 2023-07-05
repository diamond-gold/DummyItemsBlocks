<?php

namespace diamondgold\DummyItemsBlocks\block\type;

use pocketmine\block\Block;
use pocketmine\block\Transparent;
use pocketmine\block\utils\AnyFacingTrait;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;

class AnyFacingTransparent extends Transparent
{
    use AnyFacingTrait;

    public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null): bool
    {
        $this->setFacing($face);
        return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
    }

    public function canBeReplaced(): bool
    {
        return true;
    }
}