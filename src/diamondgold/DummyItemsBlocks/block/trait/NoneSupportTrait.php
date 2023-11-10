<?php

namespace diamondgold\DummyItemsBlocks\block\trait;

use pocketmine\block\utils\SupportType;

trait NoneSupportTrait
{
    public function getSupportType(int $facing): SupportType
    {
        return SupportType::NONE;
    }
}