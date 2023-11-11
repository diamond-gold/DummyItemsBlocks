<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\block\enum\DripstoneThickness;
use diamondgold\DummyItemsBlocks\block\trait\HangingTrait;
use diamondgold\DummyItemsBlocks\block\trait\NoneSupportTrait;
use diamondgold\DummyItemsBlocks\Main;
use pocketmine\block\Transparent;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class PointedDripstone extends Transparent
{
    use HangingTrait {
        describeBlockOnlyState as describeHangingState;
    }
    use NoneSupportTrait;

    protected DripstoneThickness $thickness = DripstoneThickness::TIP;

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $this->describeHangingState($w);
        $w->enum($this->thickness);
    }

    public function getThickness(): DripstoneThickness
    {
        return $this->thickness;
    }

    public function setThickness(DripstoneThickness $thickness): self
    {
        $this->thickness = $thickness;
        return $this;
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!Main::canChangeBlockStates($this, $player)) return false;
        $this->position->getWorld()->setBlock($this->position, $this->setThickness(match ($this->getThickness()) {
            DripstoneThickness::TIP => DripstoneThickness::MERGE,
            DripstoneThickness::MERGE => DripstoneThickness::FRUSTUM,
            DripstoneThickness::FRUSTUM => DripstoneThickness::MIDDLE,
            DripstoneThickness::MIDDLE => DripstoneThickness::BASE,
            DripstoneThickness::BASE => DripstoneThickness::TIP,
        }));
        return true;
    }
}