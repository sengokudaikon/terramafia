<?php

namespace App\Helpers;

use DateInterval;
use DateTimeImmutable;
use InvalidArgumentException;

/**
 * Вспомогательный класс для работы с датами и временем.
 *
 * @package App\Helpers
 */
class DateTimeUtil
{
    /**
     * Добавляет минуты к заданой дате.
     *
     * @param DateTimeImmutable $dateTime
     * @param int $minutes
     * @return DateTimeImmutable
     * @throws InvalidArgumentException
     */
    public static function addMinutes(DateTimeImmutable $dateTime, int $minutes): DateTimeImmutable
    {
        if ($minutes < 0) {
            throw new InvalidArgumentException('Значение $minutes должно быть больше 0');
        }

        $interval = DateInterval::createFromDateString(sprintf('%d minutes', $minutes));

        return $dateTime->add($interval);
    }

    /**
     * Возвращает текущую дату.
     *
     * @return DateTimeImmutable
     */
    public static function currentDate(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}
