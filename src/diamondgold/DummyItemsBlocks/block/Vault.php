<?php

namespace diamondgold\DummyItemsBlocks\block;

use diamondgold\DummyItemsBlocks\block\enum\VaultState;
use diamondgold\DummyItemsBlocks\Main;
use diamondgold\DummyItemsBlocks\tile\DummyTileTrait;
use diamondgold\DummyItemsBlocks\tile\LootTables;
use diamondgold\DummyItemsBlocks\tile\TileNames;
use diamondgold\DummyItemsBlocks\tile\TileNbtTagNames;
use pocketmine\block\tile\Tile;
use pocketmine\block\Transparent;
use pocketmine\block\utils\HorizontalFacingTrait;
use pocketmine\data\bedrock\item\ItemTypeNames;
use pocketmine\data\bedrock\item\SavedItemData;
use pocketmine\data\bedrock\item\SavedItemStackData;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\player\Player;

final class Vault extends Transparent
{
    use HorizontalFacingTrait {
        describeBlockOnlyState as describeFacing;
    }
    use DummyTileTrait;

    private VaultState $state = VaultState::INACTIVE;

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $this->describeFacing($w);
        $w->enum($this->state);
    }

    public function getLightLevel(): int
    {
        return $this->state === VaultState::INACTIVE ? 6 : 12;
    }

    public function getState(): VaultState
    {
        return $this->state;
    }

    public function setState(VaultState $state): self
    {
        $this->state = $state;
        return $this;
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!Main::canChangeBlockStates($this, $player)) return false;
        $this->position->getWorld()->setBlock($this->position, $this->setState(match ($this->state) {
            VaultState::INACTIVE => VaultState::ACTIVE,
            VaultState::ACTIVE => VaultState::UNLOCKING,
            VaultState::UNLOCKING => VaultState::EJECTING,
            VaultState::EJECTING => VaultState::INACTIVE,
        }));
        $player?->sendTip("VaultState: " . $this->state->name);
        return true;
    }

    protected function writeDefaultTileData(CompoundTag $tag): void
    {
        $tag->setString(Tile::TAG_ID, TileNames::VAULT);
        $this->setTagIfNotExist($tag, TileNbtTagNames::config, CompoundTag::create()
            ->setDouble(TileNbtTagNames::activation_range, 4)
            ->setDouble(TileNbtTagNames::deactivation_range, 4.5)
            ->setTag(TileNbtTagNames::key_item, CompoundTag::create()
                ->setByte(SavedItemStackData::TAG_COUNT, 1)
                ->setShort(SavedItemData::TAG_DAMAGE, 0)
                ->setString(SavedItemData::TAG_NAME, ItemTypeNames::TRIAL_KEY)
                ->setByte(SavedItemStackData::TAG_WAS_PICKED_UP, 0)
            )
            ->setString(TileNbtTagNames::loot_table, LootTables::TRIAL_CHAMBER_REWARD->value)
            ->setString(TileNbtTagNames::override_loot_table_to_display, ''));
        $this->setTagIfNotExist($tag, TileNbtTagNames::data, CompoundTag::create()
            ->setTag(TileNbtTagNames::items_to_eject, new ListTag())
            ->setTag(TileNbtTagNames::rewarded_players, new ListTag())
            ->setLong(TileNbtTagNames::state_updating_resumes_at, 0)
            ->setInt(TileNbtTagNames::total_ejections_needed, 0));
    }
}