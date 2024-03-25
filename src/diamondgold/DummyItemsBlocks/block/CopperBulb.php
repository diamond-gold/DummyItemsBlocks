<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\block\trait\PoweredTrait;
use diamondgold\DummyItemsBlocks\Main;
use pocketmine\block\Opaque;
use pocketmine\block\utils\LightableTrait;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class CopperBulb extends Opaque
{
    use PoweredTrait {
        PoweredTrait::describeBlockOnlyState as describePoweredState;
    }
    use LightableTrait {
        LightableTrait::describeBlockOnlyState as describeLitState;
    }

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $this->describePoweredState($w);
        $this->describeLitState($w);
    }

    public function getLightLevel(): int
    {
        return $this->lit ? 15 : 0;
        /* match (GlobalBlockStateHandlers::getSerializer()->serializeBlock($this)->getName()) { // can't do this :/
                BlockTypeNames::COPPER_BULB => 15,
                BlockTypeNames::EXPOSED_COPPER_BULB => 12,
                BlockTypeNames::WEATHERED_COPPER_BULB => 8,
                BlockTypeNames::OXIDIZED_COPPER_BULB => 4,
                default => 0
            }
        */
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!Main::canChangeBlockStates($this, $player)) return false;
        $this->position->getWorld()->setBlock($this->position, $this->setLit(!$this->isLit()));
        return true;
    }
}