<?php

namespace diamondgold\DummyItemsBlocks\block\trait;

use diamondgold\DummyItemsBlocks\block\enum\CrackedState;
use pocketmine\data\runtime\RuntimeDataDescriber;

trait CrackedStateTrait
{
    protected CrackedState $crackedState = CrackedState::NO_CRACKS;


    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $w->enum($this->crackedState);
    }

    public function getCrackedState(): CrackedState
    {
        return $this->crackedState;
    }

    public function setCrackedState(CrackedState $crackedState): self
    {
        $this->crackedState = $crackedState;
        return $this;
    }
}