<?php

namespace diamondgold\DummyItemsBlocks\item;

use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;

class DummyItem extends Item
{
    public function __construct(ItemIdentifier $identifier, string $name)
    {
        parent::__construct($identifier, $name);
    }
}