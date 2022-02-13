<?php

namespace SlaveMarket\Modules\Slave\Domain\Logic\Characteristics;

/**
 * Пол раба
 */
enum Sex: string
{
    case MALE = 'male';
    case FEMALE = 'female';
}
