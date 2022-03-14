<?php

namespace Tests\Feature;

use Psr\Log\LoggerInterface;
use Tests\TestCase;

class LogRequestTest extends TestCase
{
    public function testWillLogRequest(): void
    {
        $looger = $this->getMockBuilder(LoggerInterface::class)->getMock();

        $looger->expects($this->once())->method('info')->willReturnCallback(function (string $message, array $context) {
            $this->assertEquals(array_keys(json_decode($message, true)), [
                'method', 'uri', 'body', 'headers'
            ]);

            $this->assertArrayHasKey('request_id', $context);
        });

        $this->swap(LoggerInterface::class, $looger);

        $this->withoutExceptionHandling()->getJson(route('fetchVesselsTracks', ['mmsi' => '330']));
    }
}
