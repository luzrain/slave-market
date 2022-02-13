<?php

namespace SlaveMarket\Modules\Master\Domain\Logic\Characteristics;

/**
 * VIP статус хозяина
 * Чем больше значение, тем приоритетнее VIP статус
 */
enum Vip: int
{
    case GOLD = -1;
    case SILVER = -2;
    case BRONZE = -3;
}
