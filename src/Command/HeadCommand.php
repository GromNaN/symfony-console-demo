<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\StreamableInputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Implementation of the `head` command.
 * Demonstrates how to read from stdin and write to stdout.
 */
#[AsCommand(
    name: 'head',
    description: 'Displays the first few lines of a file.',
)]
class HeadCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('file', InputArgument::OPTIONAL, 'File to read')
            // Shortcut "n" is already used by default option --no-interaction
            ->addOption('lines', 'l', InputOption::VALUE_REQUIRED, 'Number of lines', 10)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Read and validate option
        $lines = $input->getOption('lines');
        if (false === filter_var($lines, \FILTER_VALIDATE_INT) || $lines < 0) {
            $output->writeln(sprintf('Invalid line count "%s". A positive integer is expected.', $lines));

            return Command::INVALID;
        }

        // Open the file in argument or read piped contents from STDIN
        if ($file = $input->getArgument('file')) {
            $handle = fopen($file, 'r');
        } else {
            $handle = ($input instanceof StreamableInputInterface ? $input->getStream() : null) ?? \STDIN;
        }

        // Read $lines
        while ($lines-- > 0 && $current = fgets($handle)) {
            $output->write($current);
        }
        fclose($handle);

        return Command::SUCCESS;
    }
}
