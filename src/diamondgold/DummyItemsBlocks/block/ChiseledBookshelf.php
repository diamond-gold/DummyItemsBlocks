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
use pocketmine\math\Axis;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;

class ChiseledBookshelf extends Opaque
{
    use FacesOppositePlacingPlayerTrait {
        describeBlockOnlyState as describeFacing;
    }
    use DummyTileTrait;

    protected int $books = 0;

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $this->describeFacing($w);
        $w->boundedInt(6, 0, 63, $this->books);
    }

    public function getBooks(): int
    {
        return $this->books;
    }

    public function setBooks(int $books): self
    {
        Utils::checkWithinBounds($books, 0, 63);
        $this->books = $books;
        return $this;
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if (!Main::canChangeBlockStates($this, $player)) return false;
        // adapted from https://github.com/pmmp/PocketMine-MP/pull/5827
        if ($face !== $this->facing) {
            return false;
        }
        $x = Facing::axis($face) === Axis::X ? $clickVector->getZ() : $clickVector->getX();
        $x = match ($face) {
            Facing::NORTH, Facing::EAST => 1 - $x,
            default => $x
        };
        $index = ($clickVector->y < 0.5 ? 3 : 0) + (int)(fmod($x, 1) * 3);
        $bits = $this->books;
        if ($bits & (1 << $index)) {
            $bits &= ~(1 << $index);
        } else {
            $bits |= (1 << $index);
        }
        // No data on the tile is changed because it doesn't affect appearance, main purpose is just for decor (￣▽￣)
        $this->position->getWorld()->setBlock($this->position, $this->setBooks($bits));
        //$this->position->getWorld()->setBlock($this->position, $this->setBooks(($this->books + 1) % 64));
        return true;
    }

    protected function writeDefaultTileData(CompoundTag $tag): void
    {
        $tag->setString(Tile::TAG_ID, TileNames::CHISELED_BOOKSHELF);
        /*
        // with books
        $this->setTagIfNotExist($tag, Container::TAG_ITEMS, new ListTag([
            // problem: server does not allow serializing air..., but we would need it here if we were to follow vanilla implementation
            VanillaItems::BOOK()->nbtSerialize(),
            VanillaItems::WRITABLE_BOOK()->nbtSerialize(),
            VanillaItems::WRITTEN_BOOK()->nbtSerialize(),
            StringToItemParser::getInstance()->parse(ItemTypeNames::ENCHANTED_BOOK)->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS()))->nbtSerialize(),
            GlobalItemDataHandlers::getDeserializer()->deserializeType(new SavedItemData(ItemTypeNames::ENCHANTED_BOOK))->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS()))->nbtSerialize(),
        ]));
        $this->setTagIfNotExist($tag,TileNbtTagNames::LastInteractedSlot, new IntTag(0));
        */
        $this->setTagIfNotExist($tag, TileNbtTagNames::isMovable, new ByteTag(1));
    }
}