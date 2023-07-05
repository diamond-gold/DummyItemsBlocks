<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\block\trait\NoneSupportTrait;
use diamondgold\DummyItemsBlocks\Main;
use diamondgold\DummyItemsBlocks\tile\DummyTile;
use diamondgold\DummyItemsBlocks\tile\DummyTileTrait;
use diamondgold\DummyItemsBlocks\tile\TileNames;
use diamondgold\DummyItemsBlocks\tile\TileNbtTagNames;
use pocketmine\block\tile\Tile;
use pocketmine\block\Transparent;
use pocketmine\block\utils\FacesOppositePlacingPlayerTrait;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\player\Player;

class DecoratedPot extends Transparent
{
    use FacesOppositePlacingPlayerTrait;
    use NoneSupportTrait;
    use DummyTileTrait;

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!Main::canChangeBlockStates($this, $player)) return false;
        if ($face === Facing::UP || $face === Facing::DOWN) return false;
        $alias = (StringToItemParser::getInstance()->lookupAliases($item)[0] ?? "");
        if (str_contains($alias, "_pottery_sherd")) {
            $tile = $this->position->getWorld()->getTile($this->position);
            if ($tile instanceof DummyTile) {
                // currently only correct when block facing is north, HELP WANTED
                if ($this->facing !== Facing::NORTH) {
                    $player?->sendTip("I am aware that the face changed is incorrect. HELP WANTED. Place it while facing south for now.");
                }
                static $index = [
                    Facing::SOUTH => 0,
                    Facing::EAST => 1,
                    Facing::WEST => 2,
                    Facing::NORTH => 3,
                ];
                /*
                // if facing east, there gotta be a better way to do this
                static $index = [
                    Facing::SOUTH => 1,
                    Facing::EAST => 3,
                    Facing::WEST => 0,
                    Facing::NORTH => 2,
                ];
                */
                // $player->sendMessage(Facing::toString($this->facing) . " $this->facing " . Facing::toString($face) . " $index[$face]");
                $nbt = $tile->saveNBT();
                $nbt->getListTag(TileNbtTagNames::sherds)?->set(($index[$face]) % 4, new StringTag($alias));
                $tile->readSaveData($nbt);
                $this->position->getWorld()->setBlock($this->position, $this, false);
                return true;
            }
        }
        return false;
    }

    protected function writeDefaultTileData(CompoundTag $tag): void
    {
        $tag->setString(Tile::TAG_ID, TileNames::DECORATED_POT);
        $this->setTagIfNotExist($tag, TileNbtTagNames::isMovable, new ByteTag(1));
        $this->setTagIfNotExist($tag, TileNbtTagNames::sherds, new ListTag(
            [
                new StringTag(""),
                new StringTag(""),
                new StringTag(""),
                new StringTag(""),
            ]
        ));
    }
}