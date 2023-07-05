<?php

namespace diamondgold\DummyItemsBlocks\item\horn;

use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;

final class GoatHorn extends Item
{
    private GoatHornType $type;

    public function __construct(ItemIdentifier $identifier, string $name)
    {
        $this->type = GoatHornType::PONDER();
        parent::__construct($identifier, $name);
    }

    public function getType(): GoatHornType
    {
        return $this->type;
    }

    public function setType(GoatHornType $type): self
    {
        $this->type = $type;
        return $this;
    }
}