<?php

namespace diamondgold\DummyItemsBlocks\block;

use pocketmine\block\Block;
use pocketmine\block\Transparent;
use pocketmine\block\utils\BellAttachmentType;
use pocketmine\block\utils\HorizontalFacingTrait;
use pocketmine\block\utils\SupportType;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;

class Grindstone extends Transparent
{
    // copied from Bell
    use HorizontalFacingTrait;

    private BellAttachmentType $attachmentType = BellAttachmentType::FLOOR;

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $w->bellAttachmentType($this->attachmentType);
        $w->horizontalFacing($this->facing);
    }

    protected function recalculateCollisionBoxes(): array
    {
        if ($this->attachmentType === BellAttachmentType::FLOOR) {
            return [
                AxisAlignedBB::one()->squash(Facing::axis($this->facing), 1 / 4)->trim(Facing::UP, 3 / 16)
            ];
        }
        if ($this->attachmentType === BellAttachmentType::CEILING) {
            return [
                AxisAlignedBB::one()->contract(1 / 4, 0, 1 / 4)->trim(Facing::DOWN, 1 / 4)
            ];
        }

        $box = AxisAlignedBB::one()
            ->squash(Facing::axis(Facing::rotateY($this->facing, true)), 1 / 4)
            ->trim(Facing::UP, 1 / 16)
            ->trim(Facing::DOWN, 1 / 4);

        return [
            $this->attachmentType === BellAttachmentType::ONE_WALL ? $box->trim($this->facing, 3 / 16) : $box
        ];
    }

    public function getSupportType(int $facing): SupportType
    {
        return SupportType::NONE();
    }

    public function getAttachmentType(): BellAttachmentType
    {
        return $this->attachmentType;
    }

    /** @return $this */
    public function setAttachmentType(BellAttachmentType $attachmentType): self
    {
        $this->attachmentType = $attachmentType;
        return $this;
    }

    private function canBeSupportedBy(Block $block, int $face): bool
    {
        return $block->getSupportType($face) !== SupportType::NONE;
    }

    public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null): bool
    {
        if ($face === Facing::UP) {
            if (!$this->canBeSupportedBy($tx->fetchBlock($this->position->down()), Facing::UP)) {
                return false;
            }
            if ($player !== null) {
                $this->setFacing(Facing::opposite($player->getHorizontalFacing()));
            }
            $this->setAttachmentType(BellAttachmentType::FLOOR);
        } elseif ($face === Facing::DOWN) {
            if (!$this->canBeSupportedBy($tx->fetchBlock($this->position->up()), Facing::DOWN)) {
                return false;
            }
            $this->setAttachmentType(BellAttachmentType::CEILING);
        } else {
            $this->setFacing($face);
            if ($this->canBeSupportedBy($tx->fetchBlock($this->position->getSide(Facing::opposite($face))), $face)) {
                $this->setAttachmentType(BellAttachmentType::ONE_WALL);
            } else {
                return false;
            }
        }
        return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
    }
}