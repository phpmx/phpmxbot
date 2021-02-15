<?php

declare(strict_types=1);

namespace PhpMx\Tests\Unit\Services;

use PhpMx\Services\Greeter;
use PHPUnit\Framework\TestCase;
use SQLite3;
use SQLite3Result;
use SQLite3Stmt;

final class GreeterTest extends TestCase
{
    public function provideGreeterMessages(): array
    {
        return [
            'no messages' => ['foo', []],
            'one message' => ['bar', ['one message']],
            'multiple messages' => ['baz', ['one message', 'two messages']],
        ];
    }

    /**
     * @param string $channel
     * @param array $messages
     * @dataProvider provideGreeterMessages
     */
    public function testGetMessagesFor(string $channel, array $messages): void
    {
        $resultMock = $this->createMock(SQLite3Result::class);

        // `fetchArray` is called inside a while loop, therefore it is
        // count($messages) + 1 times, that is
        // at least once even if the array is empty.
        $resultMock->expects($this->exactly(count($messages) + 1))
            ->method('fetchArray')
            ->with(SQLITE3_ASSOC)
            ->willReturnOnConsecutiveCalls(...$messages);

        $queryMock = $this->createMock(SQLite3Stmt::class);

        $queryMock->expects($this->once())
            ->method('bindValue')
            ->with(':channel', $channel);

        $queryMock->expects($this->once())
            ->method('execute')
            ->willReturn($resultMock);

        $sqlMock = $this->createMock(SQLite3::class);

        $sqlMock->expects($this->once())
            ->method('prepare')
            ->willReturn($queryMock);

        $greeter = new Greeter($sqlMock);
        $result = $greeter->getMessagesFor($channel);

        $this->assertEquals($messages, $result);
    }
}
