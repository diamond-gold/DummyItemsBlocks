<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\Main;
use diamondgold\DummyItemsBlocks\tile\DummyTile;
use diamondgold\DummyItemsBlocks\tile\DummyTileTrait;
use diamondgold\DummyItemsBlocks\tile\LootTables;
use diamondgold\DummyItemsBlocks\tile\TileNames;
use diamondgold\DummyItemsBlocks\tile\TileNbtTagNames;
use diamondgold\DummyItemsBlocks\util\Utils;
use pocketmine\block\tile\Tile;
use pocketmine\block\Transparent;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\LongTag;
use pocketmine\player\Player;

final class TrialSpawner extends Transparent
{
    use DummyTileTrait;

    private int $trial_spawner_state = 0;

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $w->boundedIntAuto(0, 5, $this->trial_spawner_state);
    }

    public function getLightLevel(): int
    {
        return $this->trial_spawner_state === 0 ? 4 : 8;
    }

    public function getState(): int
    {
        return $this->trial_spawner_state;
    }

    public function setState(int $trial_spawner_state): self
    {
        Utils::checkWithinBounds($trial_spawner_state, 0, 5);
        $this->trial_spawner_state = $trial_spawner_state;
        return $this;
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!Main::canChangeBlockStates($this, $player)) return false;
        if ($player?->isSneaking()) {
            $this->position->getWorld()->setBlock($this->position, $this->setState(($this->getState() + 1) % 6));
            $player->sendTip("TrialSpawnerState: " . $this->getState());
        } else {
            foreach (StringToItemParser::getInstance()->lookupAliases($item) as $alias) {
                if (str_contains($alias, "spawn_egg")) {
                    $alias = "minecraft:" . str_replace(["minecraft:", "_spawn_egg"], "", $alias);
                    $this->setEntityId($alias);
                    $player?->sendTip("EntityId: " . $alias);
                    break;
                }
            }
        }
        return true;
    }

    public function setEntityId(string $entityId): void
    {
        $tile = $this->position->getWorld()->getTile($this->position);
        if ($tile instanceof DummyTile) {
            $nbt = $tile->saveNBT();
            $data = $nbt->getTag(TileNbtTagNames::spawn_data) ?? CompoundTag::create();
            if ($data instanceof CompoundTag) {
                $data->setString(TileNbtTagNames::spawn_data_TypeId, $entityId);
                $tile->readSaveData($nbt);
                $this->position->getWorld()->setBlock($this->position, $this);
            }
        }
    }

    protected function writeDefaultTileData(CompoundTag $tag): void
    {
        $tag->setString(Tile::TAG_ID, TileNames::TRIAL_SPAWNER);
        $this->setTagIfNotExist($tag, TileNbtTagNames::cooldown_ends_at, new LongTag(0));
        $this->setTagIfNotExist($tag, TileNbtTagNames::current_mobs, new ListTag());
        $this->setTagIfNotExist($tag, TileNbtTagNames::loot_tables_to_eject, new ListTag([
            CompoundTag::create()
                ->setString(TileNbtTagNames::data, LootTables::TRIAL_CHAMBER_KEY->value)
                ->setInt(TileNbtTagNames::weight, 1),
            CompoundTag::create()
                ->setString(TileNbtTagNames::data, LootTables::TRIAL_CHAMBER_CONSUMABLES->value)
                ->setInt(TileNbtTagNames::weight, 1),
        ]));
        $this->setTagIfNotExist($tag, TileNbtTagNames::next_mob_spawns_at, new LongTag(0));
        $this->setTagIfNotExist($tag, TileNbtTagNames::registered_players, new ListTag());
        $this->setTagIfNotExist($tag, TileNbtTagNames::required_player_range, new IntTag(14));
        $this->setTagIfNotExist($tag, TileNbtTagNames::simultaneous_mobs, new FloatTag(2)); // wtf?
        $this->setTagIfNotExist($tag, TileNbtTagNames::simultaneous_mobs_added_per_player, new FloatTag(1)); // wtf?
        $this->setTagIfNotExist($tag, TileNbtTagNames::spawn_range, new IntTag(4));
        $this->setTagIfNotExist($tag, TileNbtTagNames::target_cooldown_length, new IntTag(36000));
        $this->setTagIfNotExist($tag, TileNbtTagNames::ticks_between_spawn, new IntTag(20));
        $this->setTagIfNotExist($tag, TileNbtTagNames::total_mobs, new FloatTag(6)); // wtf?
        $this->setTagIfNotExist($tag, TileNbtTagNames::total_mobs_added_per_player, new FloatTag(2)); // wtf?
    }
}