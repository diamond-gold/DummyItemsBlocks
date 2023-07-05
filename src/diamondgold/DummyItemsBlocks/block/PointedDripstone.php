<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\block\enum\DripstoneThickness;
use diamondgold\DummyItemsBlocks\block\hack\HackStringProperty;
use diamondgold\DummyItemsBlocks\block\trait\HangingTrait;
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

class PointedDripstone extends Transparent
{
    use HangingTrait {
        describeBlockOnlyState as describeHangingState;
    }
    use NoneSupportTrait;

    protected DripstoneThickness $thickness;
    protected HackStringProperty $thicknessHack;

    public function __construct(BlockIdentifier $idInfo, string $name, BlockTypeInfo $typeInfo)
    {
        $this->thickness = DripstoneThickness::TIP();
        $this->thicknessHack = new HackStringProperty(DripstoneThickness::getAll());
        parent::__construct($idInfo, $name, $typeInfo);
    }

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $this->describeHangingState($w);
        if ($w instanceof RuntimeDataReader) {
            $this->thicknessHack->read($this->thickness, $w);
        } else {
            $this->thicknessHack->write($this->thickness, $w);
        }
    }

    public function getThickness(): DripstoneThickness
    {
        return $this->thickness;
    }

    public function setThickness(DripstoneThickness $thickness): self
    {
        $this->thickness = $thickness;
        return $this;
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!Main::canChangeBlockStates($this, $player)) return false;
        $this->position->getWorld()->setBlock($this->position, $this->setThickness(match ($this->getThickness()) {
            DripstoneThickness::TIP() => DripstoneThickness::MERGE(),
            DripstoneThickness::MERGE() => DripstoneThickness::FRUSTUM(),
            DripstoneThickness::FRUSTUM() => DripstoneThickness::MIDDLE(),
            DripstoneThickness::MIDDLE() => DripstoneThickness::BASE(),
            DripstoneThickness::BASE() => DripstoneThickness::TIP(),
            default => throw new AssumptionFailedError("Unknown thickness: " . $this->getThickness()->name())
        }));
        return true;
    }
}