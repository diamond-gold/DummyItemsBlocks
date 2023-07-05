<?php

namespace diamondgold\DummyItemsBlocks\block\trait;

use diamondgold\DummyItemsBlocks\Main;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

trait PoweredTrait
{

    protected bool $powered = false;

    public function isPowered(): bool
    {
        return $this->powered;
    }

    public function setPowered(bool $powered): self
    {
        $this->powered = $powered;
        return $this;
    }

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $w->bool($this->powered);
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!Main::canChangeBlockStates($this, $player)) return false;
        $this->position->getWorld()->setBlock($this->position, $this->setPowered(!$this->isPowered()));
        $player?->sendTip("Powered: " . ($this->isPowered() ? "true" : "false"));
        return true;
    }
}