<?php

namespace diamondgold\DummyItemsBlocks\item\horn;

enum GoatHornType: int
{
    // can't use constants cuz "Enum case value must be compile-time evaluate-able" nani?!
    case PONDER = 0;
    case SING = 1;
    case SEEK = 2;
    case FEEL = 3;
    case ADMIRE = 4;
    case CALL = 5;
    case YEARN = 6;
    case DREAM = 7;
}