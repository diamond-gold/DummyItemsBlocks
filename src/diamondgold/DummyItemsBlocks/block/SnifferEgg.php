<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\block\enum\CrackedState;
use diamondgold\DummyItemsBlocks\block\trait\CrackedStateTrait;
use diamondgold\DummyItemsBlocks\block\trait\NoneSupportTrait;
use diamondgold\DummyItemsBlocks\Main;
use pocketmine\block\Transparent;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class SnifferEgg extends Transparent
{
    use CrackedStateTrait;
    use NoneSupportTrait;

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!Main::canChangeBlockStates($this, $player)) return false;
        $this->position->getWorld()->setBlock($this->position, $this->setCrackedState(match ($this->getCrackedState()) {
            CrackedState::NO_CRACKS => CrackedState::CRACKED,
            CrackedState::CRACKED => CrackedState::MAX_CRACKED,
            CrackedState::MAX_CRACKED => CrackedState::NO_CRACKS,
        }));
        return true;
    }
}