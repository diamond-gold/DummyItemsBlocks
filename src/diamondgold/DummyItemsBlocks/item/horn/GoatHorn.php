<?php

namespace diamondgold\DummyItemsBlocks\item\horn;

use pocketmine\item\Item;

final class GoatHorn extends Item
{
    private GoatHornType $type = GoatHornType::PONDER;

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