<?php

namespace diamondgold\DummyItemsBlocks\item\firework;

use pocketmine\block\utils\DyeColor;
use pocketmine\color\Color;
use pocketmine\data\SavedDataLoadingException;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\Binary;

final class FireworkStar extends Item
{
    protected const TAG_EXPLOSION = "FireworksItem"; //TAG_Compound
    protected const TAG_CUSTOM_COLOR = "customColor"; //TAG_Int

    protected FireworkRocketExplosion $explosion;

    public function __construct(ItemIdentifier $identifier, string $name)
    {
        parent::__construct($identifier, $name);

        $this->explosion = new FireworkRocketExplosion(FireworkRocketType::SMALL_BALL, [DyeColor::BLACK()], [], false, false);
    }

    public function getExplosion(): FireworkRocketExplosion
    {
        return $this->explosion;
    }

    /**
     * Returns the displayed color of the item.
     * The mixture of explosion colors, or the custom color if it is set.
     */
    public function getColor(): Color
    {
        return $this->explosion->getColorMix();
    }


    protected function deserializeCompoundTag(CompoundTag $tag): void
    {
        parent::deserializeCompoundTag($tag);

        $explosionTag = $tag->getTag(self::TAG_EXPLOSION);
        if (!$explosionTag instanceof CompoundTag) {
            throw new SavedDataLoadingException("Missing explosion data");
        }
        $this->explosion = FireworkRocketExplosion::fromCompoundTag($explosionTag);
    }

    protected function serializeCompoundTag(CompoundTag $tag): void
    {
        parent::serializeCompoundTag($tag);

        $tag->setTag(self::TAG_EXPLOSION, $this->explosion->toCompoundTag());
        $tag->setInt(self::TAG_CUSTOM_COLOR, Binary::signInt($this->getColor()->toARGB()));
    }
}