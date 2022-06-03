<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\SignalableCommandInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Demonstrates:
 *  - Progress Bar
 *  - Output sections.
 */
#[AsCommand(
    name: 'app:signal',
    description: 'Rewind on cancel',
)]
class SignalCommand extends Command implements SignalableCommandInterface
{
    private int $increment = 1;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Press <comment>Ctrl+C</comment> to change the direction');

        $i = 0;
        $max = 10000;
        $bar = new ProgressBar($output, $max);
        $bar->setRedrawFrequency(100);
        $bar->start();

        while ($i >= 0 && $i < $max) {
            $bar->setProgressCharacter($this->increment > 0 ? '>' : '<');
            $i += $this->increment;
            $bar->advance($this->increment);
            usleep(1000);
        }

        $bar->clear();

        return Command::SUCCESS;
    }

    public function getSubscribedSignals(): array
    {
        return [\SIGINT];
    }

    public function handleSignal(int $signal): void
    {
        if (\SIGINT === $signal) {
            $this->increment = -$this->increment;
        }
    }
}
