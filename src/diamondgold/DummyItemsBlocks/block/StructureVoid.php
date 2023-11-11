<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\block\enum\StructureVoidType;
use diamondgold\DummyItemsBlocks\block\trait\NoneSupportTrait;
use diamondgold\DummyItemsBlocks\Main;
use pocketmine\block\Flowable;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class StructureVoid extends Flowable
{
    use NoneSupportTrait;

    protected StructureVoidType $type = StructureVoidType::VOID;

    public function describeBlockItemState(RuntimeDataDescriber $w): void
    {
        $w->enum($this->type);
    }

    public function getType(): StructureVoidType
    {
        return $this->type;
    }

    public function setType(StructureVoidType $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!Main::canChangeBlockStates($this, $player)) return false;
        $this->position->getWorld()->setBlock($this->position, $this->setType(match ($this->getType()) {
            StructureVoidType::VOID => StructureVoidType::AIR,
            StructureVoidType::AIR => StructureVoidType::VOID,
        }));
        $player?->sendTip("StructureVoidType: " . $this->getType()->name);
        return true;
    }
}