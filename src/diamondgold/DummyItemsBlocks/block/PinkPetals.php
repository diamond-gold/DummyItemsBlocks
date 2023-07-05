<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\block\trait\NoneSupportTrait;
use diamondgold\DummyItemsBlocks\Main;
use diamondgold\DummyItemsBlocks\util\Utils;
use pocketmine\block\Transparent;
use pocketmine\block\utils\HorizontalFacingTrait;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class PinkPetals extends Transparent
{
    use HorizontalFacingTrait {
        describeBlockOnlyState as describeFacing;
    }
    use NoneSupportTrait;

    protected int $growth = 0;

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $this->describeFacing($w);
        $w->boundedInt(3, 0, 7, $this->growth);
    }

    public function getGrowth(): int
    {
        return $this->growth;
    }

    public function setGrowth(int $growth): self
    {
        Utils::checkWithinBounds($growth, 0, 7);
        $this->growth = $growth;
        return $this;
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!Main::canChangeBlockStates($this, $player)) return false;
        $this->position->getWorld()->setBlock($this->position, $this->setGrowth(($this->growth + 1) % 4));
        return true;
    }
}