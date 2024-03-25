<?php

namespace diamondgold\DummyItemsBlocks\tile;

/** @internal */
enum LootTables: string
{
    case EMPTY_BRUSHABLE_BLOCK = 'loot_tables/entities/empty_brushable_block.json';
    case TRIAL_CHAMBER_CONSUMABLES = 'loot_tables/spawners/trial_chamber/consumables.json';
    case TRIAL_CHAMBER_KEY = 'loot_tables/spawners/trial_chamber/key.json';
    case TRIAL_CHAMBER_REWARD = 'loot_tables/chests/trial_chambers/reward.json';
}