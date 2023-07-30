<?php

namespace diamondgold\DummyItemsBlocks\util;

use diamondgold\DummyItemsBlocks\block\BeeHive;
use diamondgold\DummyItemsBlocks\block\BigDripleaf;
use diamondgold\DummyItemsBlocks\block\BubbleColumn;
use diamondgold\DummyItemsBlocks\block\CalibratedSculkSensor;
use diamondgold\DummyItemsBlocks\block\Campfire;
use diamondgold\DummyItemsBlocks\block\CherrySapling;
use diamondgold\DummyItemsBlocks\block\ChiseledBookshelf;
use diamondgold\DummyItemsBlocks\block\CommandBlock;
use diamondgold\DummyItemsBlocks\block\Composter;
use diamondgold\DummyItemsBlocks\block\DecoratedPot;
use diamondgold\DummyItemsBlocks\block\Dispenser;
use diamondgold\DummyItemsBlocks\block\enum\BigDripleafTilt;
use diamondgold\DummyItemsBlocks\block\enum\CrackedState;
use diamondgold\DummyItemsBlocks\block\enum\DripstoneThickness;
use diamondgold\DummyItemsBlocks\block\enum\FacingDirection;
use diamondgold\DummyItemsBlocks\block\enum\SeaGrassType;
use diamondgold\DummyItemsBlocks\block\enum\StructureBlockType;
use diamondgold\DummyItemsBlocks\block\enum\StructureVoidType;
use diamondgold\DummyItemsBlocks\block\enum\TurtleEggCount;
use diamondgold\DummyItemsBlocks\block\Grindstone;
use diamondgold\DummyItemsBlocks\block\HangingSign;
use diamondgold\DummyItemsBlocks\block\Jigsaw;
use diamondgold\DummyItemsBlocks\block\Kelp;
use diamondgold\DummyItemsBlocks\block\MangrovePropagule;
use diamondgold\DummyItemsBlocks\block\Observer;
use diamondgold\DummyItemsBlocks\block\PinkPetals;
use diamondgold\DummyItemsBlocks\block\Piston;
use diamondgold\DummyItemsBlocks\block\PitcherCrop;
use diamondgold\DummyItemsBlocks\block\PitcherPlant;
use diamondgold\DummyItemsBlocks\block\PointedDripstone;
use diamondgold\DummyItemsBlocks\block\RespawnAnchor;
use diamondgold\DummyItemsBlocks\block\Scaffolding;
use diamondgold\DummyItemsBlocks\block\SculkCatalyst;
use diamondgold\DummyItemsBlocks\block\SculkSensor;
use diamondgold\DummyItemsBlocks\block\SculkShrieker;
use diamondgold\DummyItemsBlocks\block\SeaGrass;
use diamondgold\DummyItemsBlocks\block\SmallDripleafBlock;
use diamondgold\DummyItemsBlocks\block\SnifferEgg;
use diamondgold\DummyItemsBlocks\block\StructureBlock;
use diamondgold\DummyItemsBlocks\block\StructureVoid;
use diamondgold\DummyItemsBlocks\block\SuspiciousFallable;
use diamondgold\DummyItemsBlocks\block\TorchflowerCrop;
use diamondgold\DummyItemsBlocks\block\TurtleEgg;
use diamondgold\DummyItemsBlocks\block\type\AnyFacingTransparent;
use diamondgold\DummyItemsBlocks\block\type\MultiFaceDirection;
use diamondgold\DummyItemsBlocks\tile\DummyTile;
use pocketmine\block\Block;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\BlockTypeInfo;
use pocketmine\block\BlockTypeTags;
use pocketmine\block\Button;
use pocketmine\block\Door;
use pocketmine\block\FenceGate;
use pocketmine\block\FloorSign;
use pocketmine\block\Leaves;
use pocketmine\block\RuntimeBlockStateRegistry;
use pocketmine\block\SimplePillar;
use pocketmine\block\SimplePressurePlate;
use pocketmine\block\Slab;
use pocketmine\block\Stair;
use pocketmine\block\StoneButton;
use pocketmine\block\StonePressurePlate;
use pocketmine\block\Trapdoor;
use pocketmine\block\utils\LeavesType;
use pocketmine\block\utils\WoodType;
use pocketmine\block\Wall;
use pocketmine\block\WallSign;
use pocketmine\block\Wood;
use pocketmine\data\bedrock\block\BlockStateNames;
use pocketmine\data\bedrock\block\BlockStateStringValues;
use pocketmine\data\bedrock\block\BlockTypeNames;
use pocketmine\data\bedrock\block\convert\BlockStateDeserializerHelper;
use pocketmine\data\bedrock\block\convert\BlockStateReader as Reader;
use pocketmine\data\bedrock\block\convert\BlockStateSerializerHelper;
use pocketmine\data\bedrock\block\convert\BlockStateWriter as Writer;
use pocketmine\inventory\CreativeInventory;
use pocketmine\item\StringToItemParser;
use pocketmine\world\format\io\GlobalBlockStateHandlers;

/* @internal */
final class BlockStateRegistration
{
    private function __construct()
    {
    }

    /**
     * @param Block $block
     * @param string[] $stringToItemParserNames
     * @param bool $addToCreative
     * @return void
     */
    private static function register(Block $block, array $stringToItemParserNames, bool $addToCreative = true): void
    {
        RuntimeBlockStateRegistry::getInstance()->register($block);
        foreach ($stringToItemParserNames as $name) {
            StringToItemParser::getInstance()->registerBlock($name, fn() => clone $block);
        }
        if ($addToCreative) {
            CreativeInventory::getInstance()->add($block->asItem());
        }
    }

    public static function anyFacingTransparent(string $id): void
    {
        $block = new AnyFacingTransparent(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): AnyFacingTransparent => (clone $block)
                ->setFacing($reader->readFacingDirection())
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(AnyFacingTransparent $block) => Writer::create($id)
                ->writeFacingDirection($block->getFacing())
        );
    }

    public static function button(string $id): void
    {
        $block = new StoneButton(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): Button => BlockStateDeserializerHelper::decodeButton(clone $block, $reader)
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(Button $block) => BlockStateSerializerHelper::encodeButton($block, Writer::create($id))
        );
    }

    public static function door(string $id): void
    {
        $block = new Door(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id . '_block'], false);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): Door => BlockStateDeserializerHelper::decodeDoor(clone $block, $reader)
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(Door $block) => BlockStateSerializerHelper::encodeDoor($block, Writer::create($id))
        );
    }

    public static function fenceGate(string $id, WoodType $woodType): void
    {
        $block = new FenceGate(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()), $woodType);
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): FenceGate => BlockStateDeserializerHelper::decodeFenceGate(clone $block, $reader)
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(FenceGate $block) => BlockStateSerializerHelper::encodeFenceGate($block, Writer::create($id))
        );
    }

    public static function log(string $id, string $strippedId, WoodType $woodType): void
    {
        $block = new Wood(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()), $woodType);
        self::register($block, [$id, $strippedId]);

        GlobalBlockStateHandlers::getDeserializer()->mapLog($id, $strippedId,
            fn(): Wood => clone $block
        );
        GlobalBlockStateHandlers::getSerializer()->mapLog($block, $id, $strippedId);
    }

    public static function leaves(string $id, LeavesType $leavesType): void
    {
        $block = new Leaves(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()), $leavesType);
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): Leaves => BlockStateDeserializerHelper::decodeLeaves(clone $block, $reader)
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(Leaves $block) => BlockStateSerializerHelper::encodeLeaves($block, Writer::create($id))
        );
    }

    public static function multiFaceDirection(string $id): void
    {
        $block = new MultiFaceDirection(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): MultiFaceDirection => (clone $block)
                ->setMultiFaceDirection($reader->readBoundedInt(BlockStateNames::MULTI_FACE_DIRECTION_BITS, 0, 63))
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(MultiFaceDirection $block) => Writer::create($id)
                ->writeInt(BlockStateNames::MULTI_FACE_DIRECTION_BITS, $block->getMultiFaceDirection())
        );
    }

    public static function pillar(string $id): void
    {
        $block = new SimplePillar(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): SimplePillar => (clone $block)
                ->setAxis($reader->readPillarAxis())
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(SimplePillar $block) => Writer::create($id)
                ->writePillarAxis($block->getAxis())
        );
    }

    public static function sign(string $standingId, string $wallId, FloorSign $floor, WallSign $wall): void
    {
        self::register($floor, [$standingId], false);

        GlobalBlockStateHandlers::getDeserializer()->map($standingId,
            fn(Reader $reader): FloorSign => BlockStateDeserializerHelper::decodeFloorSign(clone $floor, $reader)
        );
        GlobalBlockStateHandlers::getSerializer()->map($floor,
            fn(FloorSign $block) => BlockStateSerializerHelper::encodeFloorSign($block, Writer::create($standingId))
        );

        self::register($wall, [$wallId], false);

        GlobalBlockStateHandlers::getDeserializer()->map($wallId,
            fn(Reader $reader): WallSign => BlockStateDeserializerHelper::decodeWallSign(clone $wall, $reader)
        );
        GlobalBlockStateHandlers::getSerializer()->map($wall,
            fn(WallSign $block) => BlockStateSerializerHelper::encodeWallSign($block, Writer::create($wallId))
        );
    }

    public static function simplePressurePlate(string $id): void
    {
        $block = new StonePressurePlate(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): SimplePressurePlate => BlockStateDeserializerHelper::decodeSimplePressurePlate(clone $block, $reader)
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(SimplePressurePlate $block) => BlockStateSerializerHelper::encodeSimplePressurePlate($block, Writer::create($id))
        );
    }

    public static function slab(string $singleId, string $doubleId): void
    {
        $block = new Slab(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($singleId), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$singleId, $doubleId]);

        GlobalBlockStateHandlers::getDeserializer()->mapSlab($singleId, $doubleId,
            fn(): Slab => clone $block
        );
        GlobalBlockStateHandlers::getSerializer()->mapSlab($block, $singleId, $doubleId);
    }

    public static function stairs(string $id): void
    {
        $block = new Stair(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->mapStairs($id,
            fn(): Stair => clone $block
        );
        GlobalBlockStateHandlers::getSerializer()->mapStairs($block, $id);
    }

    public static function trapdoor(string $id): void
    {
        $block = new Trapdoor(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): Trapdoor => BlockStateDeserializerHelper::decodeTrapdoor(clone $block, $reader)
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(Trapdoor $block) => BlockStateSerializerHelper::encodeTrapdoor($block, Writer::create($id))
        );
    }

    public static function wall(string $id): void
    {
        $block = new Wall(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $in): Wall => BlockStateDeserializerHelper::decodeWall(clone $block, $in)
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(Wall $block) => BlockStateSerializerHelper::encodeWall($block, new Writer($id))
        );
    }

    public static function BeeHive(string $id): void
    {
        $block = new BeeHive(new BlockIdentifier(BlockTypeIds::newId(), DummyTile::class), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): BeeHive => (clone $block)
                ->setFacing($reader->readLegacyHorizontalFacing())
                ->setHoneyLevel($reader->readInt(BlockStateNames::HONEY_LEVEL))
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(BeeHive $block) => Writer::create($id)
                ->writeLegacyHorizontalFacing($block->getFacing())
                ->writeInt(BlockStateNames::HONEY_LEVEL, $block->getHoneyLevel())
        );
    }

    // obsolete in 5.4
    public static function BigDripleaf(): void
    {
        $id = BlockTypeNames::BIG_DRIPLEAF;
        $block = new BigDripleaf(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): BigDripleaf => (clone $block)
                ->setFacing($reader->readLegacyHorizontalFacing())
                ->setHead($reader->readBool(BlockStateNames::BIG_DRIPLEAF_HEAD))
                ->setTilt(match ($reader->readString(BlockStateNames::BIG_DRIPLEAF_TILT)) {
                    BlockStateStringValues::BIG_DRIPLEAF_TILT_NONE => BigDripleafTilt::NONE(),
                    BlockStateStringValues::BIG_DRIPLEAF_TILT_PARTIAL_TILT => BigDripleafTilt::PARTIAL_TILT(),
                    BlockStateStringValues::BIG_DRIPLEAF_TILT_FULL_TILT => BigDripleafTilt::FULL_TILT(),
                    BlockStateStringValues::BIG_DRIPLEAF_TILT_UNSTABLE => BigDripleafTilt::UNSTABLE(),
                    default => throw $reader->badValueException(BlockStateNames::BIG_DRIPLEAF_TILT, $reader->readString(BlockStateNames::BIG_DRIPLEAF_TILT))
                })
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(BigDripleaf $block) => Writer::create($id)
                ->writeLegacyHorizontalFacing($block->getFacing())
                ->writeBool(BlockStateNames::BIG_DRIPLEAF_HEAD, $block->isHead())
                ->writeString(BlockStateNames::BIG_DRIPLEAF_TILT, $block->getTilt()->name())
        );
    }

    public static function BubbleColumn(): void
    {
        $id = BlockTypeNames::BUBBLE_COLUMN;
        $block = new BubbleColumn(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::indestructible()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): BubbleColumn => (clone $block)
                ->setDragDown($reader->readBool(BlockStateNames::DRAG_DOWN))
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(BubbleColumn $block) => Writer::create($id)
                ->writeBool(BlockStateNames::DRAG_DOWN, $block->getDragDown())
        );
    }

    public static function CalibratedSculkSensor(): void
    {
        $id = BlockTypeNames::CALIBRATED_SCULK_SENSOR;
        $block = new CalibratedSculkSensor(new BlockIdentifier(BlockTypeIds::newId(), DummyTile::class), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): CalibratedSculkSensor => (clone $block)
                ->setFacing($reader->readLegacyHorizontalFacing())
                ->setPhase($reader->readBoundedInt(BlockStateNames::SCULK_SENSOR_PHASE, 0, 2))
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(CalibratedSculkSensor $block) => Writer::create($id)
                ->writeLegacyHorizontalFacing($block->getFacing())
                ->writeInt(BlockStateNames::SCULK_SENSOR_PHASE, $block->getPhase())
        );
    }

    // obsolete when merged https://github.com/pmmp/PocketMine-MP/pull/4696
    public static function Campfire(string $id): void
    {
        $block = new Campfire(new BlockIdentifier(BlockTypeIds::newId(), DummyTile::class), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id . '_block'], false);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): Campfire => (clone $block)
                ->setExtinguished($reader->readBool(BlockStateNames::EXTINGUISHED))
                ->setFacing($reader->readLegacyHorizontalFacing())
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(Campfire $block) => Writer::create($id)
                ->writeBool(BlockStateNames::EXTINGUISHED, $block->isExtinguished())
                ->writeLegacyHorizontalFacing($block->getFacing())
        );
    }

    public static function CherrySapling(): void
    {
        $id = BlockTypeNames::CHERRY_SAPLING;
        $block = new CherrySapling(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): CherrySapling => (clone $block)
                ->setAgeBit($reader->readBool(BlockStateNames::AGE_BIT))
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(CherrySapling $block) => Writer::create($id)
                ->writeBool(BlockStateNames::AGE_BIT, $block->isAgeBit())
        );
    }

    // obsolete when 5.2
    public static function CherryWood(): void
    {
        $id = BlockTypeNames::CHERRY_WOOD;
        $block = new Wood(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()), WoodType::OAK());
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            function (Reader $reader) use ($block) {
                $reader->ignored(BlockStateNames::STRIPPED_BIT); //this is also ignored by vanilla
                return BlockStateDeserializerHelper::decodeLog($block, false, $reader);
            }
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            function (Wood $block): Writer {
                //we can't use the standard method for this because cherry_wood has a useless property attached to it
                if (!$block->isStripped()) {
                    return Writer::create(BlockTypeNames::CHERRY_WOOD)
                        ->writePillarAxis($block->getAxis())
                        ->writeBool(BlockStateNames::STRIPPED_BIT, false); //this is useless, but it has to be written
                } else {
                    return Writer::create(BlockTypeNames::STRIPPED_CHERRY_WOOD)
                        ->writePillarAxis($block->getAxis());
                }
            }
        );
    }

    // obsolete when merged https://github.com/pmmp/PocketMine-MP/pull/5827
    public static function ChiseledBookshelf(): void
    {
        $id = BlockTypeNames::CHISELED_BOOKSHELF;
        $block = new ChiseledBookshelf(new BlockIdentifier(BlockTypeIds::newId(), DummyTile::class), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): ChiseledBookshelf => (clone $block)
                ->setFacing($reader->readLegacyHorizontalFacing())
                ->setBooks($reader->readBoundedInt(BlockStateNames::BOOKS_STORED, 0, 63))
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(ChiseledBookshelf $block) => Writer::create($id)
                ->writeLegacyHorizontalFacing($block->getFacing())
                ->writeInt(BlockStateNames::BOOKS_STORED, $block->getBooks())
        );
    }

    public static function CommandBlock(string $id): void
    {
        $block = new CommandBlock(new BlockIdentifier(BlockTypeIds::newId(), DummyTile::class), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::indestructible()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): CommandBlock => (clone $block)
                ->setConditional($reader->readBool(BlockStateNames::CONDITIONAL_BIT))
                ->setFacing($reader->readFacingDirection())
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(CommandBlock $block) => Writer::create($id)
                ->writeBool(BlockStateNames::CONDITIONAL_BIT, $block->isConditional())
                ->writeFacingDirection($block->getFacing())
        );
    }

    // obsolete when merged https://github.com/pmmp/PocketMine-MP/pull/4742
    public static function Composter(): void
    {
        $id = BlockTypeNames::COMPOSTER;
        $block = new Composter(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): Composter => (clone $block)
                ->setFillLevel($reader->readBoundedInt(BlockStateNames::COMPOSTER_FILL_LEVEL, 0, 8))
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(Composter $block) => Writer::create($id)
                ->writeInt(BlockStateNames::COMPOSTER_FILL_LEVEL, $block->getFillLevel())
        );
    }

    public static function DecoratedPot(): void
    {
        $id = BlockTypeNames::DECORATED_POT;
        $block = new DecoratedPot(new BlockIdentifier(BlockTypeIds::newId(), DummyTile::class), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): DecoratedPot => (clone $block)
                ->setFacing($reader->readLegacyHorizontalFacing())
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(DecoratedPot $block) => Writer::create($id)
                ->writeLegacyHorizontalFacing($block->getFacing())
        );
    }

    public static function Dispenser(string $id): void
    {
        $block = new Dispenser(new BlockIdentifier(BlockTypeIds::newId(), DummyTile::class), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): Dispenser => (clone $block)
                ->setFacing($reader->readFacingDirection())
                ->setTriggered($reader->readBool(BlockStateNames::TRIGGERED_BIT))
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(Dispenser $block) => Writer::create($id)
                ->writeFacingDirection($block->getFacing())
                ->writeBool(BlockStateNames::TRIGGERED_BIT, $block->isTriggered())
        );
    }

    public static function Grindstone(): void
    {
        $id = BlockTypeNames::GRINDSTONE;
        $block = new Grindstone(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): Grindstone => (clone $block)
                ->setFacing($reader->readLegacyHorizontalFacing())
                ->setAttachmentType($reader->readBellAttachmentType())
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(Grindstone $block) => Writer::create($id)
                ->writeLegacyHorizontalFacing($block->getFacing())
                ->writeBellAttachmentType($block->getAttachmentType())
        );
    }

    public static function HangingSign(string $id): void
    {
        $block = new HangingSign(new BlockIdentifier(BlockTypeIds::newId(), DummyTile::class), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id . '_block'], false);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): HangingSign => (clone $block)
                ->setAttached($reader->readBool(BlockStateNames::ATTACHED_BIT))
                ->setFacing($reader->readHorizontalFacing())
                ->setRotation($reader->readBoundedInt(BlockStateNames::GROUND_SIGN_DIRECTION, 0, 15))
                ->setHanging($reader->readBool(BlockStateNames::HANGING))
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(HangingSign $block) => Writer::create($id)
                ->writeBool(BlockStateNames::ATTACHED_BIT, $block->isAttached())
                ->writeHorizontalFacing($block->getFacing())
                ->writeInt(BlockStateNames::GROUND_SIGN_DIRECTION, $block->getRotation())
                ->writeBool(BlockStateNames::HANGING, $block->isHanging())
        );
    }

    public static function Jigsaw(): void
    {
        $id = BlockTypeNames::JIGSAW;
        $block = new Jigsaw(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::indestructible()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): Jigsaw => (clone $block)
                ->setFacing($reader->readFacingDirection())
                ->setRotation($reader->readBoundedInt(BlockStateNames::ROTATION, 0, 3))
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(Jigsaw $block) => Writer::create($id)
                ->writeFacingDirection($block->getFacing())
                ->writeInt(BlockStateNames::ROTATION, $block->getRotation())
        );
    }

    public static function Kelp(): void
    {
        $id = BlockTypeNames::KELP;
        $block = new Kelp(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id . '_block'], false);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): Kelp => (clone $block)
                ->setAge($reader->readBoundedInt(BlockStateNames::KELP_AGE, 0, 25))
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(Kelp $block) => Writer::create($id)
                ->writeInt(BlockStateNames::KELP_AGE, $block->getAge())
        );
    }

    public static function MangrovePropagule(): void
    {
        $id = BlockTypeNames::MANGROVE_PROPAGULE;
        $block = new MangrovePropagule(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): MangrovePropagule => (clone $block)
                ->setHanging($reader->readBool(BlockStateNames::HANGING))
                ->setStage($reader->readBoundedInt(BlockStateNames::PROPAGULE_STAGE, 0, 4))
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(MangrovePropagule $block) => Writer::create($id)
                ->writeBool(BlockStateNames::HANGING, $block->isHanging())
                ->writeInt(BlockStateNames::PROPAGULE_STAGE, $block->getStage())
        );
    }

    public static function Observer(): void
    {
        $id = BlockTypeNames::OBSERVER;
        $block = new Observer(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): Observer => (clone $block)
                ->setFacingDirection(match ($reader->readString(BlockStateNames::MC_FACING_DIRECTION)) {
                    BlockStateStringValues::MC_FACING_DIRECTION_DOWN => FacingDirection::DOWN(),
                    BlockStateStringValues::MC_FACING_DIRECTION_UP => FacingDirection::UP(),
                    BlockStateStringValues::MC_FACING_DIRECTION_NORTH => FacingDirection::NORTH(),
                    BlockStateStringValues::MC_FACING_DIRECTION_SOUTH => FacingDirection::SOUTH(),
                    BlockStateStringValues::MC_FACING_DIRECTION_WEST => FacingDirection::WEST(),
                    BlockStateStringValues::MC_FACING_DIRECTION_EAST => FacingDirection::EAST(),
                    default => throw $reader->badValueException(BlockStateNames::MC_FACING_DIRECTION, $reader->readString(BlockStateNames::MC_FACING_DIRECTION)),
                })
                ->setPowered($reader->readBool(BlockStateNames::POWERED_BIT))
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(Observer $block) => Writer::create($id)
                ->writeString(BlockStateNames::MC_FACING_DIRECTION, $block->getFacingDirection()->name())
                ->writeBool(BlockStateNames::POWERED_BIT, $block->isPowered())
        );
    }

    // obsolete when merged https://github.com/pmmp/PocketMine-MP/pull/5940
    public static function PinkPetals(): void
    {
        $id = BlockTypeNames::PINK_PETALS;
        $block = new PinkPetals(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): PinkPetals => (clone $block)
                ->setFacing($reader->readLegacyHorizontalFacing())
                ->setGrowth($reader->readBoundedInt(BlockStateNames::GROWTH, 0, 7))
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(PinkPetals $block) => Writer::create($id)
                ->writeLegacyHorizontalFacing($block->getFacing())
                ->writeInt(BlockStateNames::GROWTH, $block->getGrowth())
        );
    }

    public static function Piston(string $id): void
    {
        $block = new Piston(new BlockIdentifier(BlockTypeIds::newId(), DummyTile::class), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): Piston => (clone $block)
                ->setFacing($reader->readFacingDirection())
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(Piston $block) => Writer::create($id)
                ->writeFacingDirection($block->getFacing())
        );
    }

    public static function PitcherCrop(): void
    {
        $id = BlockTypeNames::PITCHER_CROP;
        $block = new PitcherCrop(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id], false);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): PitcherCrop => (clone $block)
                ->setAge($reader->readBoundedInt(BlockStateNames::GROWTH, 0, 4))
                ->setUpper($reader->readBool(BlockStateNames::UPPER_BLOCK_BIT))
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(PitcherCrop $block) => Writer::create($id)
                ->writeInt(BlockStateNames::GROWTH, $block->getAge())
                ->writeBool(BlockStateNames::UPPER_BLOCK_BIT, $block->isUpper())
        );
    }

    public static function PitcherPlant(): void
    {
        $id = BlockTypeNames::PITCHER_PLANT;
        $block = new PitcherPlant(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): PitcherPlant => (clone $block)
                ->setUpper($reader->readBool(BlockStateNames::UPPER_BLOCK_BIT))
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(PitcherPlant $block) => Writer::create($id)
                ->writeBool(BlockStateNames::UPPER_BLOCK_BIT, $block->isUpper())
        );
    }

    public static function PointedDripstone(): void
    {
        $id = BlockTypeNames::POINTED_DRIPSTONE;
        $block = new PointedDripstone(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): PointedDripstone => (clone $block)
                ->setHanging($reader->readBool(BlockStateNames::HANGING))
                ->setThickness(match ($reader->readString(BlockStateNames::DRIPSTONE_THICKNESS)) {
                    BlockStateStringValues::DRIPSTONE_THICKNESS_BASE => DripstoneThickness::BASE(),
                    BlockStateStringValues::DRIPSTONE_THICKNESS_FRUSTUM => DripstoneThickness::FRUSTUM(),
                    BlockStateStringValues::DRIPSTONE_THICKNESS_MERGE => DripstoneThickness::MERGE(),
                    BlockStateStringValues::DRIPSTONE_THICKNESS_MIDDLE => DripstoneThickness::MIDDLE(),
                    BlockStateStringValues::DRIPSTONE_THICKNESS_TIP => DripstoneThickness::TIP(),
                    default => throw $reader->badValueException(BlockStateNames::DRIPSTONE_THICKNESS, $reader->readString(BlockStateNames::DRIPSTONE_THICKNESS))
                })
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(PointedDripstone $block) => Writer::create($id)
                ->writeBool(BlockStateNames::HANGING, $block->isHanging())
                ->writeString(BlockStateNames::DRIPSTONE_THICKNESS, $block->getThickness()->name())
        );
    }

    public static function RespawnAnchor(): void
    {
        $id = BlockTypeNames::RESPAWN_ANCHOR;
        $block = new RespawnAnchor(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): RespawnAnchor => (clone $block)
                ->setCharges($reader->readBoundedInt(BlockStateNames::RESPAWN_ANCHOR_CHARGE, 0, 4))
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(RespawnAnchor $block) => Writer::create($id)
                ->writeInt(BlockStateNames::RESPAWN_ANCHOR_CHARGE, $block->getCharges())
        );
    }

    public static function Scaffolding(): void
    {
        $id = BlockTypeNames::SCAFFOLDING;
        $block = new Scaffolding(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): Scaffolding => (clone $block)
                ->setStability($reader->readBoundedInt(BlockStateNames::STABILITY, 0, 7))
                ->setStabilityCheck($reader->readBool(BlockStateNames::STABILITY_CHECK))
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(Scaffolding $block) => Writer::create($id)
                ->writeInt(BlockStateNames::STABILITY, $block->getStability())
                ->writeBool(BlockStateNames::STABILITY_CHECK, $block->isStabilityCheck())
        );
    }

    public static function SculkCatalyst(): void
    {
        $id = BlockTypeNames::SCULK_CATALYST;
        $block = new SculkCatalyst(new BlockIdentifier(BlockTypeIds::newId(), DummyTile::class), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): SculkCatalyst => (clone $block)
                ->setBloom($reader->readBool(BlockStateNames::BLOOM))
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(SculkCatalyst $block) => Writer::create($id)
                ->writeBool(BlockStateNames::BLOOM, $block->isBloom())
        );
    }

    public static function SculkSensor(): void
    {
        $id = BlockTypeNames::SCULK_SENSOR;
        $block = new SculkSensor(new BlockIdentifier(BlockTypeIds::newId(), DummyTile::class), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): SculkSensor => (clone $block)
                ->setPhase($reader->readBoundedInt(BlockStateNames::SCULK_SENSOR_PHASE, 0, 2))
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(SculkSensor $block) => Writer::create($id)
                ->writeInt(BlockStateNames::SCULK_SENSOR_PHASE, $block->getPhase())
        );
    }

    public static function SculkShrieker(): void
    {
        $id = BlockTypeNames::SCULK_SHRIEKER;
        $block = new SculkShrieker(new BlockIdentifier(BlockTypeIds::newId(), DummyTile::class), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): SculkShrieker => (clone $block)
                ->setActive($reader->readBool(BlockStateNames::ACTIVE))
                ->setCanSummon($reader->readBool(BlockStateNames::CAN_SUMMON))
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(SculkShrieker $block) => Writer::create($id)
                ->writeBool(BlockStateNames::ACTIVE, $block->isActive())
                ->writeBool(BlockStateNames::CAN_SUMMON, $block->canSummon())
        );
    }

    public static function SeaGrass(): void
    {
        $id = BlockTypeNames::SEAGRASS;
        $block = new SeaGrass(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): SeaGrass => (clone $block)
                ->setType(match ($reader->readString(BlockStateNames::SEA_GRASS_TYPE)) {
                    BlockStateStringValues::SEA_GRASS_TYPE_DEFAULT => SeaGrassType::DEFAULT(),
                    BlockStateStringValues::SEA_GRASS_TYPE_DOUBLE_TOP => SeaGrassType::DOUBLE_TOP(),
                    BlockStateStringValues::SEA_GRASS_TYPE_DOUBLE_BOT => SeaGrassType::DOUBLE_BOT(),
                    default => throw $reader->badValueException(BlockStateNames::SEA_GRASS_TYPE, $reader->readString(BlockStateNames::SEA_GRASS_TYPE))
                })
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(SeaGrass $block) => Writer::create($id)
                ->writeString(BlockStateNames::SEA_GRASS_TYPE, $block->getType()->name())
        );
    }

    public static function SnifferEgg(): void
    {
        $id = BlockTypeNames::SNIFFER_EGG;
        $block = new SnifferEgg(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): SnifferEgg => (clone $block)
                ->setCrackedState(match ($reader->readString(BlockStateNames::CRACKED_STATE)) {
                    BlockStateStringValues::CRACKED_STATE_NO_CRACKS => CrackedState::NO_CRACKS(),
                    BlockStateStringValues::CRACKED_STATE_CRACKED => CrackedState::CRACKED(),
                    BlockStateStringValues::CRACKED_STATE_MAX_CRACKED => CrackedState::MAX_CRACKED(),
                    default => throw $reader->badValueException(BlockStateNames::CRACKED_STATE, $reader->readString(BlockStateNames::CRACKED_STATE))
                })
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(SnifferEgg $block) => Writer::create($id)
                ->writeString(BlockStateNames::CRACKED_STATE, $block->getCrackedState()->name())
        );
    }

    // obsolete in 5.4
    public static function SmallDripleafBlock(): void
    {
        $id = BlockTypeNames::SMALL_DRIPLEAF_BLOCK;
        $block = new SmallDripleafBlock(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): SmallDripleafBlock => (clone $block)
                ->setFacing($reader->readLegacyHorizontalFacing())
                ->setUpper($reader->readBool(BlockStateNames::UPPER_BLOCK_BIT))
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(SmallDripleafBlock $block) => Writer::create($id)
                ->writeLegacyHorizontalFacing($block->getFacing())
                ->writeBool(BlockStateNames::UPPER_BLOCK_BIT, $block->isUpper())
        );
    }

    public static function StructureBlock(): void
    {
        $id = BlockTypeNames::STRUCTURE_BLOCK;
        $block = new StructureBlock(new BlockIdentifier(BlockTypeIds::newId(), DummyTile::class), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::indestructible()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): StructureBlock => (clone $block)
                ->setType(match ($reader->readString(BlockStateNames::STRUCTURE_BLOCK_TYPE)) {
                    BlockStateStringValues::STRUCTURE_BLOCK_TYPE_SAVE => StructureBlockType::SAVE(),
                    BlockStateStringValues::STRUCTURE_BLOCK_TYPE_LOAD => StructureBlockType::LOAD(),
                    BlockStateStringValues::STRUCTURE_BLOCK_TYPE_CORNER => StructureBlockType::CORNER(),
                    BlockStateStringValues::STRUCTURE_BLOCK_TYPE_DATA => StructureBlockType::DATA(),
                    BlockStateStringValues::STRUCTURE_BLOCK_TYPE_EXPORT => StructureBlockType::EXPORT(),
                    BlockStateStringValues::STRUCTURE_BLOCK_TYPE_INVALID => StructureBlockType::INVALID(),
                    default => throw $reader->badValueException(BlockStateNames::STRUCTURE_BLOCK_TYPE, $reader->readString(BlockStateNames::STRUCTURE_BLOCK_TYPE))
                })
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(StructureBlock $block) => Writer::create($id)
                ->writeString(BlockStateNames::STRUCTURE_BLOCK_TYPE, $block->getType()->name())
        );
    }

    public static function StructureVoid(): void
    {
        $id = BlockTypeNames::STRUCTURE_VOID;
        $block = new StructureVoid(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::indestructible()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): StructureVoid => (clone $block)
                ->setType(match ($reader->readString(BlockStateNames::STRUCTURE_VOID_TYPE)) {
                    BlockStateStringValues::STRUCTURE_VOID_TYPE_VOID => StructureVoidType::VOID(),
                    BlockStateStringValues::STRUCTURE_VOID_TYPE_AIR => StructureVoidType::AIR(),
                    default => throw $reader->badValueException(BlockStateNames::STRUCTURE_VOID_TYPE, $reader->readString(BlockStateNames::STRUCTURE_VOID_TYPE))
                })
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(StructureVoid $block) => Writer::create($id)
                ->writeString(BlockStateNames::STRUCTURE_VOID_TYPE, $block->getType()->name())
        );
    }

    public static function SuspiciousFallable(string $id): void
    {
        if ($id === BlockTypeNames::SUSPICIOUS_SAND) {
            $tags = [BlockTypeTags::SAND];
        } else {
            $tags = [];
        }
        // Note: does not implement Fallable nor use the FallableTrait
        $block = new SuspiciousFallable(new BlockIdentifier(BlockTypeIds::newId(), DummyTile::class), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant(), $tags));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): SuspiciousFallable => (clone $block)
                ->setBrushedProgress($reader->readBoundedInt(BlockStateNames::BRUSHED_PROGRESS, 0, 3))
                ->setHanging($reader->readBool(BlockStateNames::HANGING))
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(SuspiciousFallable $block) => Writer::create($id)
                ->writeInt(BlockStateNames::BRUSHED_PROGRESS, $block->getBrushedProgress())
                ->writeBool(BlockStateNames::HANGING, $block->isHanging())
        );
    }

    public static function TorchflowerCrop(): void
    {
        $id = BlockTypeNames::TORCHFLOWER_CROP;
        $block = new TorchflowerCrop(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id], false);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): TorchflowerCrop => (clone $block)
                ->setAge($reader->readBoundedInt(BlockStateNames::GROWTH, 0, 2))
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(TorchflowerCrop $block) => Writer::create($id)
                ->writeInt(BlockStateNames::GROWTH, $block->getAge())
        );
    }

    public static function TurtleEgg(): void
    {
        $id = BlockTypeNames::TURTLE_EGG;
        $block = new TurtleEgg(new BlockIdentifier(BlockTypeIds::newId()), Utils::generateNameFromId($id), new BlockTypeInfo(BlockBreakInfo::instant()));
        self::register($block, [$id]);

        GlobalBlockStateHandlers::getDeserializer()->map($id,
            fn(Reader $reader): TurtleEgg => (clone $block)
                ->setEggCount(match ($reader->readString(BlockStateNames::TURTLE_EGG_COUNT)) {
                    BlockStateStringValues::TURTLE_EGG_COUNT_ONE_EGG => TurtleEggCount::ONE_EGG(),
                    BlockStateStringValues::TURTLE_EGG_COUNT_TWO_EGG => TurtleEggCount::TWO_EGG(),
                    BlockStateStringValues::TURTLE_EGG_COUNT_THREE_EGG => TurtleEggCount::THREE_EGG(),
                    BlockStateStringValues::TURTLE_EGG_COUNT_FOUR_EGG => TurtleEggCount::FOUR_EGG(),
                    default => throw $reader->badValueException(BlockStateNames::TURTLE_EGG_COUNT, $reader->readString(BlockStateNames::TURTLE_EGG_COUNT))
                })
                ->setCrackedState(match ($reader->readString(BlockStateNames::CRACKED_STATE)) {
                    BlockStateStringValues::CRACKED_STATE_NO_CRACKS => CrackedState::NO_CRACKS(),
                    BlockStateStringValues::CRACKED_STATE_CRACKED => CrackedState::CRACKED(),
                    BlockStateStringValues::CRACKED_STATE_MAX_CRACKED => CrackedState::MAX_CRACKED(),
                    default => throw $reader->badValueException(BlockStateNames::CRACKED_STATE, $reader->readString(BlockStateNames::CRACKED_STATE))
                })
        );
        GlobalBlockStateHandlers::getSerializer()->map($block,
            fn(TurtleEgg $block) => Writer::create($id)
                ->writeString(BlockStateNames::TURTLE_EGG_COUNT, $block->getEggCount()->name())
                ->writeString(BlockStateNames::CRACKED_STATE, $block->getCrackedState()->name())
        );
    }
}