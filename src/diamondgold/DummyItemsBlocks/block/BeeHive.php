<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\Main;
use diamondgold\DummyItemsBlocks\tile\DummyTileTrait;
use diamondgold\DummyItemsBlocks\tile\TileNames;
use diamondgold\DummyItemsBlocks\tile\TileNbtTagNames;
use diamondgold\DummyItemsBlocks\util\Utils;
use pocketmine\block\Opaque;
use pocketmine\block\tile\Tile;
use pocketmine\block\utils\FacesOppositePlacingPlayerTrait;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\player\Player;

class BeeHive extends Opaque
{
    use FacesOppositePlacingPlayerTrait {
        describeBlockOnlyState as describeFacingState;
    }
    use DummyTileTrait;

    protected int $honeyLevel = 0;

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $this->describeFacingState($w);
        $w->boundedIntAuto(0, 5, $this->honeyLevel);
    }

    public function getHoneyLevel(): int
    {
        return $this->honeyLevel;
    }

    public function setHoneyLevel(int $honeyLevel): self
    {
        Utils::checkWithinBounds($honeyLevel, 0, 5);
        $this->honeyLevel = $honeyLevel;
        return $this;
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!Main::canChangeBlockStates($this, $player)) return false;
        $this->position->getWorld()->setBlock($this->position, $this->setHoneyLevel(($this->getHoneyLevel() + 1) % 6));// only 0,5 have visible change
        $player?->sendTip("Honey Level: " . $this->getHoneyLevel());
        return true;
    }

    protected function writeDefaultTileData(CompoundTag $tag): void
    {
        $tag->setString(Tile::TAG_ID, TileNames::BEEHIVE);
        $this->setTagIfNotExist($tag, TileNbtTagNames::Occupants, CompoundTag::create()
            ->setTag(TileNbtTagNames::Occupants, new ListTag([
                CompoundTag::create()
                    ->setTag(TileNbtTagNames::Occupants_ActorIdentifier, new StringTag("minecraft:bee<>")) // erm Mojang???
                    ->setTag(TileNbtTagNames::Occupants_SaveData, CompoundTag::create())
                    ->setInt(TileNbtTagNames::Occupants_TicksLeftToStay, 0)
            ]))
        );
        $this->setTagIfNotExist($tag, TileNbtTagNames::ShouldSpawnBees, new ByteTag(0));
    }
}