<?php

namespace diamondgold\DummyItemsBlocks\block\trait;

use diamondgold\DummyItemsBlocks\block\enum\BlockFace;
use diamondgold\DummyItemsBlocks\block\hack\HackStringProperty;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\data\runtime\RuntimeDataReader;

trait BlockFaceTrait
{
    protected BlockFace $blockFace;

    protected HackStringProperty $blockFaceHack;

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        if ($w instanceof RuntimeDataReader) {
            $this->blockFaceHack->read($this->blockFace, $w);
        } else {
            $this->blockFaceHack->write($this->blockFace, $w);
        }
    }

    public function getBlockFace(): BlockFace
    {
        return $this->blockFace;
    }

    public function setBlockFace(BlockFace $blockFace): self
    {
        $this->blockFace = $blockFace;
        return $this;
    }
}