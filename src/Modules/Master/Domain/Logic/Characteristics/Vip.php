<?php

namespace SlaveMarket\Modules\Master\Domain\Logic\Characteristics;

/**
 * VIP статус хозяев
 * Чем значение больше, тем более приоритетнее статус
 */
enum Vip: int
{
    case GOLD = -1;
    case SILVER = -2;
    case BRONZE = -3;
}
