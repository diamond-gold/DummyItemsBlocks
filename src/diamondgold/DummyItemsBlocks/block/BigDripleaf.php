<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\block\enum\BigDripleafTilt;
use diamondgold\DummyItemsBlocks\block\hack\HackStringProperty;
use diamondgold\DummyItemsBlocks\block\trait\NoneSupportTrait;
use diamondgold\DummyItemsBlocks\Main;
use pocketmine\block\Block;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockTypeInfo;
use pocketmine\block\Transparent;
use pocketmine\block\utils\FacesOppositePlacingPlayerTrait;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\data\runtime\RuntimeDataReader;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\utils\AssumptionFailedError;
use pocketmine\world\BlockTransaction;

class BigDripleaf extends Transparent
{
    use FacesOppositePlacingPlayerTrait {
        describeBlockOnlyState as describeFacing;
    }
    use NoneSupportTrait;

    protected BigDripleafTilt $tilt;
    protected HackStringProperty $tiltHack;

    protected bool $head = false;

    public function __construct(BlockIdentifier $idInfo, string $name, BlockTypeInfo $typeInfo)
    {
        $this->tilt = BigDripleafTilt::NONE();
        $this->tiltHack = new HackStringProperty(BigDripleafTilt::getAll());
        parent::__construct($idInfo, $name, $typeInfo);
    }

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $this->describeFacing($w);
        $w->bool($this->head);
        if ($w instanceof RuntimeDataReader) {
            $this->tiltHack->read($this->tilt, $w);
        } else {
            $this->tiltHack->write($this->tilt, $w);
        }
    }

    public function isHead(): bool
    {
        return $this->head;
    }

    public function setHead(bool $head): self
    {
        $this->head = $head;
        return $this;
    }

    public function getTilt(): BigDripleafTilt
    {
        return $this->tilt;
    }

    public function setTilt(BigDripleafTilt $tilt): self
    {
        $this->tilt = $tilt;
        return $this;
    }

    public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null): bool
    {
        $block = $blockReplace->getSide(Facing::DOWN);
        if ($player !== null) {
            $this->facing = Facing::opposite($player->getHorizontalFacing());
        }
        if ($block instanceof self && $block->hasSameTypeId($this)) {
            $this->facing = $block->getFacing();
            $tx->addBlock($block->getPosition(), (clone $block)->setHead(false));
        }
        $this->head = true;
        return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!Main::canChangeBlockStates($this, $player)) return false;
        $this->position->getWorld()->setBlock($this->position, $this->setTilt(match ($this->getTilt()) {
            BigDripleafTilt::NONE() => BigDripleafTilt::UNSTABLE(),
            BigDripleafTilt::UNSTABLE() => BigDripleafTilt::PARTIAL_TILT(),
            BigDripleafTilt::PARTIAL_TILT() => BigDripleafTilt::FULL_TILT(),
            BigDripleafTilt::FULL_TILT() => BigDripleafTilt::NONE(),
            default => throw new AssumptionFailedError("Unknown BigDripleafTilt enum value" . $this->getTilt()->name())
        }));
        $player?->sendTip("Tilt: " . $this->getTilt()->name());
        return true;
    }
}