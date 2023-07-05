<?php

namespace diamondgold\DummyItemsBlocks\block\trait;

use diamondgold\DummyItemsBlocks\Main;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

trait UpperTrait
{

    protected bool $upper = false;

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $w->bool($this->upper);
    }

    public function isUpper(): bool
    {
        return $this->upper;
    }

    public function setUpper(bool $upper): self
    {
        $this->upper = $upper;
        return $this;
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!Main::canChangeBlockStates($this, $player)) return false;
        $this->position->getWorld()->setBlock($this->position, $this->setUpper(!$this->upper));
        $player?->sendTip("Upper: " . ($this->isUpper() ? "true" : "false"));
        return true;
    }
}