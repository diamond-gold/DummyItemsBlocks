<?php

namespace diamondgold\DummyItemsBlocks\block\enum;

enum StructureBlockType
{
    case DATA;
    case SAVE;
    case LOAD;
    case CORNER;
    case EXPORT;
    case INVALID;
}