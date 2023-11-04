<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\block\enum\FacingDirection;
use diamondgold\DummyItemsBlocks\block\trait\FacingDirectionTrait;
use diamondgold\DummyItemsBlocks\block\trait\PoweredTrait;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockTypeInfo;
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

    public function __construct(BlockIdentifier $idInfo, string $name, BlockTypeInfo $typeInfo)
    {
        $this->facingDirection = FacingDirection::DOWN;
        parent::__construct($idInfo, $name, $typeInfo);
    }

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $this->describeFacingDirectionState($w);
        $this->describePoweredState($w);
    }
}