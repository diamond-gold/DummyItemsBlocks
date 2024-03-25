<?php

namespace diamondgold\DummyItemsBlocks\tile;

final class TileNbtTagNames
{
    // all tiles
    public const isMovable = "isMovable";
    // "Beehive"
    public const Occupants = "Occupants";
    public const Occupants_ActorIdentifier = "ActorIdentifier";
    public const Occupants_SaveData = "SaveData";
    public const Occupants_TicksLeftToStay = "TicksLeftToStay";
    public const ShouldSpawnBees = "ShouldSpawnBees";
    // BrushableBlock
    public const LootTable = "LootTable";
    public const LootTableSeed = "LootTableSeed";
    public const brush_count = "brush_count";
    public const brush_direction = "brush_direction";
    public const type = "type";
    // CalibratedSculkSensor SculkSensor SculkShrieker
    public const VibrationListener = "VibrationListener";
    public const VibrationListener_event = "event";
    public const VibrationListener_selector = "selector";
    // Campfire
    public const Items = "Item%d"; // sprintf
    public const ItemTime = "ItemTime%d"; // sprintf
    // Conduit
    public const Active = "Active";
    public const Target = "Target";
    // CommandBlock
    public const Command = "Command";
    public const ExecuteOnFirstTick = "ExecuteOnFirstTick";
    public const LPCommandMode = "LPCommandMode";
    public const LPConditionalMode = "LPConditionalMode";
    public const LPRedstoneMode = "LPRedstoneMode";
    public const LastExecution = "LastExecution";
    public const LastOutput = "LastOutput";
    public const SuccessCount = "SuccessCount";
    public const TickDelay = "TickDelay";
    public const TrackOutput = "TrackOutput";
    public const Version = "Version";
    public const auto = "auto";
    public const conditionMet = "conditionMet";
    public const powered = "powered";
    // Crafter
    public const crafting_ticks_remaining = "crafting_ticks_remaining";
    public const triggered = "triggered";
    public const disabled_slots = "disabled_slots";
    // DecoratedPot
    public const sherds = "sherds";
    public const animation = "animation";
    public const item = "item";
    // HangingSign
    public const HideGlowOutline = "HideGlowOutline";
    public const TextOwner = "TextOwner";
    // PistonArm
    public const AttachedBlocks = "AttachedBlocks";
    public const BreakBlocks = "BreakBlocks";
    public const LastProgress = "LastProgress";
    public const NewState = "NewState";
    public const Progress = "Progress";
    public const State = "State";
    public const Sticky = "Sticky";
    // structure block
    public const animationMode = "animationMode";
    public const animationSeconds = "animationSeconds";
    public const data = "data";
    public const dataField = "dataField";
    public const ignoreEntities = "ignoreEntities";
    public const includePlayers = "includePlayers";
    public const integrity = "integrity";
    public const isPowered = "isPowered";
    public const mirror = "mirror";
    public const redstoneSaveMode = "redstoneSaveMode";
    public const removeBlocks = "removeBlocks";
    public const rotation = "rotation";
    public const seed = "seed";
    public const showBoundingBox = "showBoundingBox";
    public const structureName = "structureName";
    public const xStructureOffset = "xStructureOffset";
    public const xStructureSize = "xStructureSize";
    public const yStructureOffset = "yStructureOffset";
    public const yStructureSize = "yStructureSize";
    public const zStructureOffset = "zStructureOffset";
    public const zStructureSize = "zStructureSize";
    // TrialSpawner
    public const cooldown_ends_at = "cooldown_ends_at";
    public const current_mobs = "current_mobs";
    public const loot_tables_to_eject = "loot_tables_to_eject";
    public const weight = "weight";
    public const next_mob_spawns_at = "next_mob_spawns_at";
    public const registered_players = "registered_players";
    public const required_player_range = "required_player_range";
    public const simultaneous_mobs = "simultaneous_mobs";
    public const simultaneous_mobs_added_per_player = "simultaneous_mobs_added_per_player";
    public const target_cooldown_length = "target_cooldown_length";
    public const spawn_range = "spawn_range";
    public const ticks_between_spawn = "ticks_between_spawn";
    public const total_mobs = "total_mobs";
    public const total_mobs_added_per_player = "total_mobs_added_per_player";
    public const spawn_data = "spawn_data";
    public const spawn_data_TypeId = "TypeId";
    // Vault
    public const config = "config";
    public const loot_table = "loot_table";
    public const override_loot_table_to_display = "override_loot_table_to_display";
    public const activation_range = "activation_range";
    public const deactivation_range = "deactivation_range";
    public const key_item = "key_item";
    public const rewarded_players = "rewarded_players";
    public const state_updating_resumes_at = "state_updating_resumes_at";
    public const items_to_eject = "items_to_eject";
    public const total_ejections_needed = "total_ejections_needed";
}