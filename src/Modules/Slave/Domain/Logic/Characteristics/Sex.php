<?php

namespace SlaveMarket\Modules\Slave\Domain\Logic\Characteristics;

/**
 * Пол рабов
 */
enum Sex: string
{
    case MALE = 'male';
    case FEMALE = 'female';
}
