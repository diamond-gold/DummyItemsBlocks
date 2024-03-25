<?php

namespace diamondgold\DummyItemsBlocks\block\enum;

enum VaultState
{
    case INACTIVE;
    case ACTIVE;
    case UNLOCKING;
    case EJECTING;
}