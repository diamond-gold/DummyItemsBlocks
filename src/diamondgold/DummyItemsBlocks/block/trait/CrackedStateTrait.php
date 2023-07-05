<?php

namespace diamondgold\DummyItemsBlocks\block\trait;

use diamondgold\DummyItemsBlocks\block\enum\CrackedState;
use diamondgold\DummyItemsBlocks\block\hack\HackStringProperty;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\data\runtime\RuntimeDataReader;

trait CrackedStateTrait
{
    protected CrackedState $crackedState;
    protected HackStringProperty $crackedStateHack;


    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        if ($w instanceof RuntimeDataReader) {
            $this->crackedStateHack->read($this->crackedState, $w);
        } else {
            $this->crackedStateHack->write($this->crackedState, $w);
        }
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