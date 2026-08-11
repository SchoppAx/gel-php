<?php

declare(strict_types=1);

use mehrWEBnet\Gel\Api\Shipments;
use mehrWEBnet\Gel\Gel;
use PHPUnit\Framework\TestCase;

final class GelTest extends TestCase
{
    public function testMakeReturnsConfiguredClient(): void
    {
        $gel = Gel::make('test-key', 1, [123], true);

        $this->assertInstanceOf(Gel::class, $gel);
        $this->assertInstanceOf(Shipments::class, $gel->shipments());
    }
}
