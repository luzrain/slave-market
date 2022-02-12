<?php

namespace SlaveMarket\Modules\Lease\Domain\Logic\СontractValidator\Validators;

use Carbon\CarbonImmutable;
use SlaveMarket\Modules\Lease\Domain\Logic\СontractValidator\Validator;
use SlaveMarket\Modules\Lease\Persistence\Entity\LeaseContract;

/**
 * Проверка максимального рабочего времени в течение дня
 */
class ValidateSlaveWorkTime extends Validator
{
    // Максимальное количество рабочих часов в день
    private const MAX_DAY_HOURS = 16;

    // Шаблон текста ошибки
    private const ERROR_TEMPLATE = 'Ошибка. %s у раба #%s "%s" свободно %s часов';

    public function validate(LeaseContract $leaseContract): void
    {
        $slave = $leaseContract->getSlave();
        $range = $leaseContract->getDateTimeRange();
        $leaseDayStart = CarbonImmutable::instance($range->getStartTime())->startOf('day');
        $leaseDayEnd = CarbonImmutable::instance($range->getEndTime())->endOf('day');

        // Поиск активных договоров аренды за все дни в договоре
        $contracts = $this->contractRepository->getForSlave($slave->getId(), $leaseDayStart, $leaseDayEnd);

        // Массив часов для нового договора с разбивкой по датам
        $currentLeaseDayMap = $this->getDaysMap([$leaseContract]);

        // Массив часов для других существующих договоров с разбивкой по датам
        $otherLeasesDayMap = $this->getDaysMap($contracts);

        // Проверяем каждый день из всех договоров и нового, и уже существующих
        foreach (array_keys($currentLeaseDayMap) + array_keys($otherLeasesDayMap) as $date) {
            // Массив часов из нового договора аренды на день
            $currentLeaseHours = $currentLeaseDayMap[$date] ?? [];

            // Если в договоре аренды заняты все 24 часа дня, не ограничиваем время
            if (count($currentLeaseHours) === 24) {
                continue;
            }

            // Массив уже занятых часов раба на день
            $busyHours = $otherLeasesDayMap[$date] ?? [];

            // Если сумма запрошенных и уже занятых часов больше допустимого максимума
            if (count($currentLeaseHours) + count($busyHours) > self::MAX_DAY_HOURS) {
                // Количество доступных свободных часов раба на день
                $freeHours = self::MAX_DAY_HOURS - count($busyHours);

                // Бросаем исключение
                $error = sprintf(self::ERROR_TEMPLATE, $date, $slave->getId()->toBase32(), $slave->getName(), $freeHours);
                throw $this->createException($error);
            }
        }
    }

    /**
     * Возвращает массив часов, разбитый по датам.
     * Ключ массива - дата в формате Y-m-d, а значение это массив занятых часов в эту дату
     *
     * @param LeaseContract[] $contracts
     * @return array
     */
    private function getDaysMap(array $contracts): array
    {
        // Массив занятых часов с разбивкой по дням, где ключ это дата в формате Y-m-d, а значение это массив занятых часов.
        $map = [];

        // Цикл по всем часам во всех найденных контрактах
        foreach ($contracts as $contract) {
            $range = $contract->getDateTimeRange();
            foreach ($range->getLeaseHours() as $hour) {
                $map[$hour->getDate()][] = $hour;
            }
        }

        return $map;
    }
}
