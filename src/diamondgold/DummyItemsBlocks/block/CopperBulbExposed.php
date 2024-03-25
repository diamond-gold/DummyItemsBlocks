<?php

namespace diamondgold\DummyItemsBlocks\block;

final class CopperBulbExposed extends CopperBulb
{
    public function getLightLevel(): int
    {
        return $this->lit ? 12 : 0;
    }
}