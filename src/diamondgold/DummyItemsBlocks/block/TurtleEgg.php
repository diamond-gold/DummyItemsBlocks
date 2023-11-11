<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\block\enum\CrackedState;
use diamondgold\DummyItemsBlocks\block\enum\TurtleEggCount;
use diamondgold\DummyItemsBlocks\block\trait\CrackedStateTrait;
use diamondgold\DummyItemsBlocks\block\trait\NoneSupportTrait;
use diamondgold\DummyItemsBlocks\Main;
use pocketmine\block\Transparent;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class TurtleEgg extends Transparent
{
    use CrackedStateTrait {
        describeBlockOnlyState as describeCrackedState;
    }
    use NoneSupportTrait;

    protected TurtleEggCount $eggCount = TurtleEggCount::ONE_EGG;

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $this->describeCrackedState($w);
        $w->enum($this->eggCount);
    }

    public function getEggCount(): TurtleEggCount
    {
        return $this->eggCount;
    }

    public function setEggCount(TurtleEggCount $eggCount): self
    {
        $this->eggCount = $eggCount;
        return $this;
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!Main::canChangeBlockStates($this, $player)) return false;
        if ($player?->isSneaking()) {
            $this->position->getWorld()->setBlock($this->position, $this->setCrackedState(match ($this->getCrackedState()) {
                CrackedState::NO_CRACKS => CrackedState::CRACKED,
                CrackedState::CRACKED => CrackedState::MAX_CRACKED,
                CrackedState::MAX_CRACKED => CrackedState::NO_CRACKS,
            }));
            return true;
        }
        $this->position->getWorld()->setBlock($this->position, $this->setEggCount(match ($this->getEggCount()) {
            TurtleEggCount::ONE_EGG => TurtleEggCount::TWO_EGG,
            TurtleEggCount::TWO_EGG => TurtleEggCount::THREE_EGG,
            TurtleEggCount::THREE_EGG => TurtleEggCount::FOUR_EGG,
            TurtleEggCount::FOUR_EGG => TurtleEggCount::ONE_EGG,
        }));
        return true;
    }
}