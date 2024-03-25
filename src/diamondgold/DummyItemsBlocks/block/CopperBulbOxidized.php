<?php

namespace diamondgold\DummyItemsBlocks\block;

final class CopperBulbOxidized extends CopperBulb
{
    public function getLightLevel(): int
    {
        return $this->lit ? 4 : 0;
    }
}