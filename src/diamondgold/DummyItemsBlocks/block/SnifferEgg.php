<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\block\enum\CrackedState;
use diamondgold\DummyItemsBlocks\block\hack\HackStringProperty;
use diamondgold\DummyItemsBlocks\block\trait\CrackedStateTrait;
use diamondgold\DummyItemsBlocks\block\trait\NoneSupportTrait;
use diamondgold\DummyItemsBlocks\Main;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockTypeInfo;
use pocketmine\block\Transparent;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\utils\AssumptionFailedError;

class SnifferEgg extends Transparent
{
    use CrackedStateTrait;
    use NoneSupportTrait;

    public function __construct(BlockIdentifier $idInfo, string $name, BlockTypeInfo $typeInfo)
    {
        $this->crackedState = CrackedState::NO_CRACKS();
        $this->crackedStateHack = new HackStringProperty(CrackedState::getAll());
        parent::__construct($idInfo, $name, $typeInfo);
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!Main::canChangeBlockStates($this, $player)) return false;
        $this->position->getWorld()->setBlock($this->position, $this->setCrackedState(match ($this->getCrackedState()) {
            CrackedState::NO_CRACKS() => CrackedState::CRACKED(),
            CrackedState::CRACKED() => CrackedState::MAX_CRACKED(),
            CrackedState::MAX_CRACKED() => CrackedState::NO_CRACKS(),
            default => throw new AssumptionFailedError("Invalid cracked state: " . $this->getCrackedState()->name())
        }));
        return true;
    }
}