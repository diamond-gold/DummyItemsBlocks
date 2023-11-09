<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\block\trait\HangingTrait;
use diamondgold\DummyItemsBlocks\block\trait\NoneSupportTrait;
use diamondgold\DummyItemsBlocks\Main;
use diamondgold\DummyItemsBlocks\util\Utils;
use pocketmine\block\Transparent;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class MangrovePropagule extends Transparent
{
    use HangingTrait {
        describeBlockOnlyState as describeHangingState;
    }
    use NoneSupportTrait;

    protected int $stage = 0;

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $this->describeHangingState($w);
        $w->boundedIntAuto(0, 4, $this->stage);
    }

    public function getStage(): int
    {
        return $this->stage;
    }

    public function setStage(int $stage): self
    {
        Utils::checkWithinBounds($stage, 0, 4);
        $this->stage = $stage;
        return $this;
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!$this->hanging || !Main::canChangeBlockStates($this, $player)) return false;
        $this->position->getWorld()->setBlock($this->position, $this->setStage(($this->stage + 1) % 5));
        $player?->sendTip("Stage: " . $this->stage);
        return true;
    }
}