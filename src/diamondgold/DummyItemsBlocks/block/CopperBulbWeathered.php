<?php

namespace diamondgold\DummyItemsBlocks\block;

final class CopperBulbWeathered extends CopperBulb
{
    public function getLightLevel(): int
    {
        return $this->lit ? 8 : 0;
    }
}