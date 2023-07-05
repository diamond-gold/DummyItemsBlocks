<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\block\enum\StructureVoidType;
use diamondgold\DummyItemsBlocks\block\hack\HackStringProperty;
use diamondgold\DummyItemsBlocks\block\trait\NoneSupportTrait;
use diamondgold\DummyItemsBlocks\Main;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockTypeInfo;
use pocketmine\block\Flowable;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\data\runtime\RuntimeDataReader;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\utils\AssumptionFailedError;

class StructureVoid extends Flowable
{
    use NoneSupportTrait;

    protected StructureVoidType $type;
    protected HackStringProperty $typeHack;

    public function __construct(BlockIdentifier $idInfo, string $name, BlockTypeInfo $typeInfo)
    {
        $this->type = StructureVoidType::VOID();
        $this->typeHack = new HackStringProperty(StructureVoidType::getAll());
        parent::__construct($idInfo, $name, $typeInfo);
    }

    public function describeBlockItemState(RuntimeDataDescriber $w): void
    {
        if ($w instanceof RuntimeDataReader) {
            $this->typeHack->read($this->type, $w);
        } else {
            $this->typeHack->write($this->type, $w);
        }
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
            StructureVoidType::VOID() => StructureVoidType::AIR(),
            StructureVoidType::AIR() => StructureVoidType::VOID(),
            default => throw new AssumptionFailedError("Invalid structure void type: " . $this->getType()->name())
        }));
        $player?->sendTip("StructureVoidType: " . $this->getType()->name());
        return true;
    }
}