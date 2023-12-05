<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\Main;
use diamondgold\DummyItemsBlocks\util\Utils;
use pocketmine\block\Opaque;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class RespawnAnchor extends Opaque
{
    protected int $charges = 0;

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $w->boundedIntAuto(0, 4, $this->charges);
    }

    public function getCharges(): int
    {
        return $this->charges;
    }

    public function setCharges(int $charges): self
    {
        Utils::checkWithinBounds($charges, 0, 4);
        $this->charges = $charges;
        return $this;
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!Main::canChangeBlockStates($this, $player)) return false;
        $this->position->getWorld()->setBlock($this->position, $this->setCharges(($this->getCharges() + 1) % 5));
        return true;
    }

    public function getLightLevel(): int
    {
        return $this->charges > 0 ? ($this->charges > 1 ? 3 + ($this->charges - 1) * 4 : 3) : 0;
    }
}