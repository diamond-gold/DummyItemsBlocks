<?php

namespace diamondgold\DummyItemsBlocks\block\type;

use diamondgold\DummyItemsBlocks\block\enum\BlockFace;
use diamondgold\DummyItemsBlocks\block\hack\HackStringProperty;
use diamondgold\DummyItemsBlocks\block\trait\BlockFaceTrait;
use pocketmine\block\Block;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockTypeInfo;
use pocketmine\block\Flowable;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\utils\AssumptionFailedError;
use pocketmine\world\BlockTransaction;

class AmethystAnyFacing extends Flowable
{
    use BlockFaceTrait;

    public function __construct(BlockIdentifier $idInfo, string $name, BlockTypeInfo $typeInfo)
    {
        $this->blockFace = BlockFace::DOWN();
        $this->blockFaceHack = new HackStringProperty(BlockFace::getAll());
        parent::__construct($idInfo, $name, $typeInfo);
    }

    public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null): bool
    {
        $this->setBlockFace(match ($face) {
            Facing::DOWN => BlockFace::DOWN(),
            Facing::UP => BlockFace::UP(),
            Facing::NORTH => BlockFace::NORTH(),
            Facing::SOUTH => BlockFace::SOUTH(),
            Facing::WEST => BlockFace::WEST(),
            Facing::EAST => BlockFace::EAST(),
            default => throw new AssumptionFailedError("Invalid facing direction $face"),
        });
        return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
    }
}