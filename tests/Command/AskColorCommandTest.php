<?php

namespace App\Tests\Command;

use App\Command\AskColorCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;

class AskColorCommandTest extends TestCase
{
    public function testExecuteWithArgument(): void
    {
        $command = new AskColorCommand();
        $commandTester = new CommandTester($command);
        $commandTester->execute(['color' => 'red']);
        $commandTester->assertCommandIsSuccessful();

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('You have just selected:  red ', $output);
    }

    public function testExecuteWithInvalidArgument(): void
    {
        $command = new AskColorCommand();
        $commandTester = new CommandTester($command);
        $commandTester->execute(['color' => 'pink']);
        $this->assertSame(2, $commandTester->getStatusCode(), 'Command has an error');

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Invalid "pink" color; expected one of (black, red, green', $output);
    }

    public function testExecuteWithInterative(): void
    {
        $command = new AskColorCommand();
        $commandTester = new CommandTester($command);
        $commandTester->setInputs(['blue']);
        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Please select your favorite color', $output);
        $this->assertStringContainsString('You have just selected:  blue ', $output);
    }

    public function testExecuteNonInterative(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not enough arguments (missing: "color")');
        $command = new AskColorCommand();
        $commandTester = new CommandTester($command);
        $commandTester->execute([], ['interactive' => false]);
    }
}
