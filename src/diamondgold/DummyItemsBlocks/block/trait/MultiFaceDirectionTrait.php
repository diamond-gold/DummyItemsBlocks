<?php

namespace diamondgold\DummyItemsBlocks\block\trait;

use diamondgold\DummyItemsBlocks\util\Utils;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\math\Facing;

trait MultiFaceDirectionTrait
{
    protected int $multiFaceDirection = 1;
    /** @var int[] */
    protected static array $bits = [
        Facing::UP => 0x01,
        Facing::DOWN => 0x02,
        Facing::NORTH => 0x04,
        Facing::EAST => 0x08,
        Facing::SOUTH => 0x10,
        Facing::WEST => 0x20,
    ];

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $w->boundedIntAuto(0, 63, $this->multiFaceDirection);
    }

    public function getMultiFaceDirection(): int
    {
        return $this->multiFaceDirection;
    }

    public function setMultiFaceDirection(int $multiFaceDirection): self
    {
        Utils::checkWithinBounds($multiFaceDirection, 0, 63);
        $this->multiFaceDirection = $multiFaceDirection;
        return $this;
    }
}