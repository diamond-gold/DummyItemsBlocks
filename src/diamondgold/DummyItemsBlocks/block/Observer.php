<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\block\trait\FacingDirectionTrait;
use diamondgold\DummyItemsBlocks\block\trait\PoweredTrait;
use pocketmine\block\Opaque;
use pocketmine\data\runtime\RuntimeDataDescriber;

class Observer extends Opaque
{
    use FacingDirectionTrait {
        FacingDirectionTrait::describeBlockOnlyState as describeFacingDirectionState;
    }
    use PoweredTrait {
        PoweredTrait::describeBlockOnlyState as describePoweredState;
    }

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $this->describeFacingDirectionState($w);
        $this->describePoweredState($w);
    }
}