<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\block\enum\SeaGrassType;
use diamondgold\DummyItemsBlocks\block\hack\HackStringProperty;
use diamondgold\DummyItemsBlocks\block\trait\NoneSupportTrait;
use diamondgold\DummyItemsBlocks\Main;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockTypeInfo;
use pocketmine\block\Transparent;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\data\runtime\RuntimeDataReader;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\utils\AssumptionFailedError;

class SeaGrass extends Transparent
{
    use NoneSupportTrait;

    protected SeaGrassType $type;
    protected HackStringProperty $typeHack;

    public function __construct(BlockIdentifier $idInfo, string $name, BlockTypeInfo $typeInfo)
    {
        $this->type = SeaGrassType::DEFAULT();
        $this->typeHack = new HackStringProperty(SeaGrassType::getAll());
        parent::__construct($idInfo, $name, $typeInfo);
    }

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        if ($w instanceof RuntimeDataReader) {
            $this->typeHack->read($this->type, $w);
        } else {
            $this->typeHack->write($this->type, $w);
        }
    }

    public function getType(): SeaGrassType
    {
        return $this->type;
    }

    public function setType(SeaGrassType $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!Main::canChangeBlockStates($this, $player)) return false;
        $this->position->getWorld()->setBlock($this->position, $this->setType(match ($this->getType()) {
            SeaGrassType::DEFAULT() => SeaGrassType::DOUBLE_BOT(),
            SeaGrassType::DOUBLE_BOT() => SeaGrassType::DOUBLE_TOP(),
            SeaGrassType::DOUBLE_TOP() => SeaGrassType::DEFAULT(),
            default => throw new AssumptionFailedError("Unknown SeaGrassType: " . $this->getType()->name())
        }));
        $player?->sendTip("Type: " . $this->type->name());
        return true;
    }

    public function canBeReplaced(): bool
    {
        return true;
    }
}