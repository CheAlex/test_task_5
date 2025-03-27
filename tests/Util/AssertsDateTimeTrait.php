<?php

declare(strict_types=1);

namespace Tests\Util;

use PHPUnit\Framework\Assert;

/**
 * @mixin Assert
 */
trait AssertsDateTimeTrait
{
    /**
     * Useful for field "createdAt".
     */
    private static function assertDateTimeInRangeFromStartToCurrentDateTime(
        \DateTimeInterface $testedDateTime,
        \DateTimeInterface $startDateTime,
    ): void {
        static::assertGreaterThanOrEqual($startDateTime, $testedDateTime);
        static::assertLessThanOrEqual(new \DateTimeImmutable(), $testedDateTime);
    }
}
