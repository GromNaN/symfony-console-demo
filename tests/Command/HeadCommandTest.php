<?php

namespace App\Tests\Command;

use App\Command\HeadCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class HeadCommandTest extends TestCase
{
    public function testExecuteFromStdin(): void
    {
        $command = new HeadCommand();
        $commandTester = new CommandTester($command);

        $commandTester->setInputs(file(__FILE__));
        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();
        $this->assertSame(10, mb_substr_count($commandTester->getDisplay(), "\n"));
    }

    public function testExecute(): void
    {
        $command = new HeadCommand();
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'file' => __FILE__,
            '--lines' => 7,
        ]);

        $commandTester->assertCommandIsSuccessful();
        $this->assertSame(7, mb_substr_count($commandTester->getDisplay(), "\n"));
    }
}
