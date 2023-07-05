<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\block\trait\NoneSupportTrait;
use diamondgold\DummyItemsBlocks\block\trait\UpperTrait;
use pocketmine\block\Transparent;

class PitcherPlant extends Transparent
{
    use UpperTrait;
    use NoneSupportTrait;
}