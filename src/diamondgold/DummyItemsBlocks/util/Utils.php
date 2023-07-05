<?php

namespace diamondgold\DummyItemsBlocks\util;

use InvalidArgumentException;

final class Utils
{
    private function __construct()
    {
    }

    public static function generateNameFromId(string $id): string
    {
        $id = str_replace('minecraft:', '', $id);
        $words = explode('_', $id);
        $convertedText = '';
        foreach ($words as $word) {
            $convertedText .= ucfirst($word) . ' ';
        }
        return trim($convertedText);
    }

    /**
     * @param string $id
     * @param string[] $list
     * @return bool
     */
    public static function removeIfPresent(string $id, array &$list): bool
    {
        $key = array_search($id, $list, true);
        if ($key !== false) {
            unset($list[$key]);
            return true;
        }
        return false;
    }

    public static function checkWithinBounds(int $value, int $min, int $max): void
    {
        if ($value < $min || $value > $max) {
            throw new InvalidArgumentException("Value must be between $min and $max, got $value");
        }
    }
}