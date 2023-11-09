<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\block\trait\NoneSupportTrait;
use diamondgold\DummyItemsBlocks\Main;
use diamondgold\DummyItemsBlocks\util\Utils;
use pocketmine\block\Transparent;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class Scaffolding extends Transparent
{
    protected int $stability = 0;
    protected bool $stabilityCheck = false;
    use NoneSupportTrait;

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $w->boundedIntAuto(0, 7, $this->stability);
        $w->bool($this->stabilityCheck);
    }

    public function getStability(): int
    {
        return $this->stability;
    }

    public function setStability(int $stability): self
    {
        Utils::checkWithinBounds($stability, 0, 7);
        $this->stability = $stability;
        return $this;
    }

    public function isStabilityCheck(): bool
    {
        return $this->stabilityCheck;
    }

    public function setStabilityCheck(bool $stabilityCheck): self
    {
        $this->stabilityCheck = $stabilityCheck;
        return $this;
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!Main::canChangeBlockStates($this, $player)) return false;
        $this->position->getWorld()->setBlock($this->position, $this->setStability(($this->getStability() + 1) % 8));
        $player?->sendTip("Stability: " . $this->getStability());
        return true;
    }
}