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
    // DecoratedPot
    public const sherds = "sherds";
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
}