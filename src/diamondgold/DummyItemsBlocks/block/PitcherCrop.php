<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\block\trait\NoneSupportTrait;
use diamondgold\DummyItemsBlocks\block\trait\UpperTrait;
use diamondgold\DummyItemsBlocks\Main;
use diamondgold\DummyItemsBlocks\util\Utils;
use pocketmine\block\Transparent;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class PitcherCrop extends Transparent
{
    use UpperTrait {
        describeBlockOnlyState as describeUpperOnlyState;
    }
    use NoneSupportTrait;

    protected int $age = 0;

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $this->describeUpperOnlyState($w);
        $w->boundedInt(3, 0, 4, $this->age);
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        Utils::checkWithinBounds($age, 0, 4);
        $this->age = $age;
        return $this;
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!Main::canChangeBlockStates($this, $player)) return false;
        if ($player?->isSneaking()) {
            $this->position->getWorld()->setBlock($this->position, $this->setUpper(!$this->isUpper()));
            $player->sendTip("Upper: " . ($this->isUpper() ? "true" : "false"));
            return true;
        }
        $this->position->getWorld()->setBlock($this->position, $this->setAge((($this->age + 1) % 5)));
        $player?->sendTip("Age: " . $this->age);
        if (!$this->upper) {
            $up = $this->getSide(Facing::UP);
            if ($up instanceof PitcherCrop) {
                $up->setAge($this->age);
                $this->position->getWorld()->setBlock($up->position, $up);
            }
        } else {
            $down = $this->getSide(Facing::DOWN);
            if ($down instanceof PitcherCrop) {
                $down->setAge($this->age);
                $this->position->getWorld()->setBlock($down->position, $down);
            }
        }
        return true;
    }
}