<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\block\trait\NoneSupportTrait;
use pocketmine\block\Transparent;
use pocketmine\data\runtime\RuntimeDataDescriber;

class CherrySapling extends Transparent
{
    use NoneSupportTrait;

    protected bool $ageBit = false;

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $w->bool($this->ageBit);
    }

    public function isAgeBit(): bool
    {
        return $this->ageBit;
    }

    public function setAgeBit(bool $ageBit): self
    {
        $this->ageBit = $ageBit;
        return $this;
    }
}