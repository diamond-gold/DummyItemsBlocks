<?php

namespace diamondgold\DummyItemsBlocks\item\firework;

use InvalidArgumentException;
use pocketmine\block\utils\DyeColor;
use pocketmine\color\Color;
use pocketmine\data\bedrock\DyeColorIdMap;
use pocketmine\data\SavedDataLoadingException;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\Binary;
use pocketmine\utils\Utils;

final class FireworkRocketExplosion
{

    protected const TAG_TYPE = "FireworkType"; //TAG_Byte
    protected const TAG_COLORS = "FireworkColor"; //TAG_ByteArray
    protected const TAG_FADE_COLORS = "FireworkFade"; //TAG_ByteArray
    protected const TAG_TWINKLE = "FireworkFlicker"; //TAG_Byte
    protected const TAG_TRAIL = "FireworkTrail"; //TAG_Byte

    public static function fromCompoundTag(CompoundTag $tag): self
    {
        $colors = self::decodeColors($tag->getByteArray(self::TAG_COLORS));
        if (count($colors) === 0) {
            throw new SavedDataLoadingException("Colors list cannot be empty");
        }

        return new self(
            FireworkRocketTypeIdMap::getInstance()->fromId($tag->getByte(self::TAG_TYPE)) ?? throw new SavedDataLoadingException("Invalid firework type"),
            $colors,
            self::decodeColors($tag->getByteArray(self::TAG_FADE_COLORS)),
            $tag->getByte(self::TAG_TWINKLE, 0) !== 0,
            $tag->getByte(self::TAG_TRAIL, 0) !== 0
        );
    }

    /**
     * @return DyeColor[]
     * @phpstan-return list<DyeColor>
     */
    protected static function decodeColors(string $colorsBytes): array
    {
        $colors = [];

        $dyeColorIdMap = DyeColorIdMap::getInstance();
        for ($i = 0; $i < strlen($colorsBytes); $i++) {
            $colorByte = Binary::readByte($colorsBytes[$i]);
            $color = $dyeColorIdMap->fromInvertedId($colorByte);
            if ($color !== null) {
                $colors[] = $color;
            } else {
                throw new SavedDataLoadingException("Unknown color $colorByte");
            }
        }

        return $colors;
    }

    /**
     * @param DyeColor[] $colors
     */
    protected static function encodeColors(array $colors): string
    {
        $colorsBytes = "";

        $dyeColorIdMap = DyeColorIdMap::getInstance();
        foreach ($colors as $color) {
            $colorsBytes .= Binary::writeByte($dyeColorIdMap->toInvertedId($color));
        }

        return $colorsBytes;
    }

    /**
     * @param DyeColor[] $colors
     * @param DyeColor[] $fadeColors
     * @phpstan-param non-empty-list<DyeColor> $colors
     * @phpstan-param list<DyeColor> $fadeColors
     */
    public function __construct(
        protected FireworkRocketType $type,
        protected array              $colors,
        protected array              $fadeColors = [],
        protected bool               $twinkle = false,
        protected bool               $trail = false
    )
    {
        if (count($colors) === 0) {
            throw new InvalidArgumentException("Colors list cannot be empty");
        }

        $colorsValidator = function (DyeColor $_): void {
        };

        Utils::validateArrayValueType($colors, $colorsValidator);
        Utils::validateArrayValueType($fadeColors, $colorsValidator);
    }

    public function getType(): FireworkRocketType
    {
        return $this->type;
    }

    /**
     * Returns the colors of the particles.
     *
     * @return DyeColor[]
     * @phpstan-return non-empty-list<DyeColor>
     */
    public function getColors(): array
    {
        return $this->colors;
    }

    /**
     * Returns the flash color of the explosion.
     */
    public function getFlashColor(): DyeColor
    {
        return $this->colors[array_key_first($this->colors)];
    }

    /**
     * Returns the mixture of colors from {@link FireworkRocketExplosion::getColors()})
     */
    public function getColorMix(): Color
    {
        /** @var Color[] $colors */
        $colors = [];
        foreach ($this->colors as $dyeColor) {
            $colors[] = $dyeColor->getRgbValue();
        }
        return Color::mix(...$colors);
    }

    public function toCompoundTag(): CompoundTag
    {
        return CompoundTag::create()
            ->setByte(self::TAG_TYPE, FireworkRocketTypeIdMap::getInstance()->toId($this->type))
            ->setByteArray(self::TAG_COLORS, self::encodeColors($this->colors))
            ->setByteArray(self::TAG_FADE_COLORS, self::encodeColors($this->fadeColors))
            ->setByte(self::TAG_TWINKLE, $this->twinkle ? 1 : 0)
            ->setByte(self::TAG_TRAIL, $this->trail ? 1 : 0);
    }
}