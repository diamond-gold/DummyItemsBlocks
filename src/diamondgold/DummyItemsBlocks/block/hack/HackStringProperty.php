<?php

namespace diamondgold\DummyItemsBlocks\block\hack;

use diamondgold\DummyItemsBlocks\block\enum\DummyEnum;
use pocketmine\block\utils\LeverFacing;
use pocketmine\data\runtime\InvalidSerializedRuntimeDataException;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\utils\AssumptionFailedError;

/**
 * Needed until fixed https://github.com/pmmp/PocketMine-MP/issues/5877
 * @internal
 */
final class HackStringProperty
{
    /** @var LeverFacing[] $realToFakeMap */
    protected array $realToFakeMap = [];
    /** @var DummyEnum[] $fakeToRealMap */
    protected array $fakeToRealMap = [];
    protected LeverFacing $fakeProperty;

    /**
     * @param DummyEnum[] $realMap
     * @return void
     */
    public function __construct(array $realMap)
    {
        $realMap = array_values($realMap);
        $values = LeverFacing::getAll(); // Need at most 3 bits for now, min 2 states, max 6 states
        usort($values, fn(LeverFacing $a, LeverFacing $b) => $a->name() <=> $b->name()); // enum not sorted, but RuntimeDataDescriber::leverFacing() is sorted
        foreach (array_values($values) as $i => $type) {
            if (!isset($realMap[$i])) break;
            $this->realToFakeMap[$realMap[$i]->name()] = $type;
            $this->fakeToRealMap[$type->name()] = $realMap[$i];
        }
    }

    public function read(DummyEnum &$real, RuntimeDataDescriber $w): void
    {
        $w->leverFacing($this->fakeProperty);
        if (!isset($this->fakeToRealMap[$this->fakeProperty->name()])) {
            throw new InvalidSerializedRuntimeDataException;
        }
        $real = $this->fakeToRealMap[$this->fakeProperty->name()];
    }

    public function write(DummyEnum $real, RuntimeDataDescriber $w): void
    {
        if (!isset($this->realToFakeMap[$real->name()])) {
            throw new AssumptionFailedError("Unknown property value " . $real->name());
        }
        $this->fakeProperty = $this->realToFakeMap[$real->name()];
        $w->leverFacing($this->fakeProperty);
    }
}