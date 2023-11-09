<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\block\trait\NoneSupportTrait;
use diamondgold\DummyItemsBlocks\Main;
use diamondgold\DummyItemsBlocks\util\Utils;
use pocketmine\block\Opaque;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class Composter extends Opaque
{
    use NoneSupportTrait;

    protected int $fillLevel = 0;

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $w->boundedIntAuto(0, 8, $this->fillLevel);
    }

    public function getFillLevel(): int
    {
        return $this->fillLevel;
    }

    public function setFillLevel(int $fillLevel): self
    {
        Utils::checkWithinBounds($fillLevel, 0, 8);
        $this->fillLevel = $fillLevel;
        return $this;
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!Main::canChangeBlockStates($this, $player)) return false;
        $this->position->getWorld()->setBlock($this->position, $this->setFillLevel(($this->getFillLevel() + 1) % 9));
        return true;
    }
}