<?php

namespace diamondgold\DummyItemsBlocks\item\horn;

use InvalidArgumentException;
use pocketmine\utils\SingletonTrait;

final class GoatHornTypeIdMap
{
    use SingletonTrait;

    /**
     * @var GoatHornType[]
     * @phpstan-var array<int, GoatHornType>
     */
    private array $idToEnum = [];

    /**
     * @var int[]
     * @phpstan-var array<int, int>
     */
    private array $enumToId = [];

    private function __construct()
    {
        $this->register(GoatHornTypeIds::PONDER, GoatHornType::PONDER);
        $this->register(GoatHornTypeIds::SING, GoatHornType::SING);
        $this->register(GoatHornTypeIds::SEEK, GoatHornType::SEEK);
        $this->register(GoatHornTypeIds::FEEL, GoatHornType::FEEL);
        $this->register(GoatHornTypeIds::ADMIRE, GoatHornType::ADMIRE);
        $this->register(GoatHornTypeIds::CALL, GoatHornType::CALL);
        $this->register(GoatHornTypeIds::YEARN, GoatHornType::YEARN);
        $this->register(GoatHornTypeIds::DREAM, GoatHornType::DREAM);
    }

    private function register(int $id, GoatHornType $type): void
    {
        $this->idToEnum[$id] = $type;
        $this->enumToId[$type->value] = $id;
    }

    public function fromId(int $id): ?GoatHornType
    {
        return $this->idToEnum[$id] ?? null;
    }

    public function toId(GoatHornType $type): int
    {
        if (!isset($this->enumToId[$type->value])) {
            throw new InvalidArgumentException("Type does not have a mapped ID");
        }
        return $this->enumToId[$type->value];
    }
}