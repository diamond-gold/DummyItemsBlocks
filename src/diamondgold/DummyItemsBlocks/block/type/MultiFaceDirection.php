<?php

namespace diamondgold\DummyItemsBlocks\block\type;

use diamondgold\DummyItemsBlocks\block\trait\MultiFaceDirectionTrait;
use diamondgold\DummyItemsBlocks\block\trait\NoneSupportTrait;
use pocketmine\block\Block;
use pocketmine\block\Transparent;
use pocketmine\block\utils\SupportType;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;

class MultiFaceDirection extends Transparent
{
    use MultiFaceDirectionTrait;
    use NoneSupportTrait;

    /*
    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!Main::canChangeBlockStates($this, $player)) return false;
        $direction = ($this->multiFaceDirection + 1) % 64;
        if ($direction === 0) $direction = 1; // 0 causes block to visually disappear
        $this->position->getWorld()->setBlock($this->position, $this->setMultiFaceDirection($direction));
        return true;
    }
    */

    public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null): bool
    {
        if ($blockReplace instanceof self) {
            $bits = $blockReplace->getMultiFaceDirection();
            if ($bits & self::$bits[$face]) {
                foreach ([Facing::DOWN, Facing::UP, Facing::SOUTH, Facing::NORTH, Facing::EAST, Facing::WEST] as $f) {
                    if ($bits & self::$bits[$f] || $blockReplace->getSide(Facing::opposite($f))->getSupportType($f) !== SupportType::FULL()) {
                        continue;
                    }
                    $bits |= self::$bits[$f];
                    break;
                }
            } else {
                $bits |= self::$bits[$face];
            }
            if ($bits === $this->multiFaceDirection) {
                return false;
            }
            $this->setMultiFaceDirection($bits);
        } else {
            $this->setMultiFaceDirection(self::$bits[$face]);
        }
        return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
    }

    public function canBeReplaced(): bool
    {
        return true;
    }
}