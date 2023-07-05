<?php

namespace diamondgold\DummyItemsBlocks\item;

use pocketmine\block\Block;
use pocketmine\item\ItemIdentifier;

final class ItemPlacedAsBlock extends DummyItem
{
    public function __construct(ItemIdentifier $identifier, string $name, protected Block $block)
    {
        parent::__construct($identifier, $name);
    }

    public function getBlock(?int $clickedFace = null): Block
    {
        return clone $this->block;
    }
}