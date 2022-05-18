<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Demonstrates:
 *  - Progress Bar
 *  - Output sections
 */
#[AsCommand(
    name: 'farming',
    description: 'Show progression',
)]
class FarmingCommand extends Command
{

    protected function configure(): void
    {
        $this->addOption('count', 'c', InputOption::VALUE_REQUIRED, 'Number of chicken to grow', 10_000);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $chickens = range(1, $input->getOption('count'));

        $section1 = $output instanceof ConsoleOutputInterface ? $output->section() : null;
        $section2 = $output instanceof ConsoleOutputInterface ? $output->section() : null;
        $section3 = $output instanceof ConsoleOutputInterface ? $output->section() : null;

        $mainBar = new ProgressBar($section2, 3);
        $mainBar->start();

        $section1->writeln('Birth...');
        //
        $bar = new ProgressBar($section3);
        $bar->setRedrawFrequency(100);
        $bar->setBarCharacter('🐥');
        $bar->setProgressCharacter('🐣');
        $bar->setEmptyBarCharacter('🥚');
        foreach ($bar->iterate($chickens) as $i) {
            usleep(500);
        }
        $bar->clear();
        $section3->clear();
        $mainBar->advance();
        $section1->overwrite('Growth...');

        //
        $bar = new ProgressBar($section3);
        $bar->setRedrawFrequency(100);
        $bar->setBarCharacter('🐔');
        $bar->setProgressCharacter('🌽');
        $bar->setEmptyBarCharacter('🐤');
        foreach ($bar->iterate($chickens) as $i) {
            usleep(1000);
        }
        $bar->clear();
        $section3->clear();
        $mainBar->advance();
        $section1->overwrite('Cooking...');

        $bar = new ProgressBar($section3);
        $bar->setRedrawFrequency(100);
        $bar->setBarCharacter('🍗');
        $bar->setProgressCharacter('🔥');
        $bar->setEmptyBarCharacter('🐓');
        foreach ($bar->iterate($chickens) as $i) {
            usleep(100);
        }
        $bar->clear();
        $section3->clear();
        $mainBar->finish();
        $section2->clear();
        $section1->overwrite('Enjoy!');

        return Command::SUCCESS;
    }
}
