<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

#[AsCommand(
    name: 'input:password',
    description: 'Hide the use password ',
)]
class InputPasswordCommand extends Command
{
    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $confirmation = new ConfirmationQuestion('Hide the input (Y/n)? ', true);
        $question = new Question('What is the database password? ');

        if ($helper->ask($input, $output, $confirmation)) {
            $question->setHidden(true);
        }

        $value = $helper->ask($input, $output, $question);

        if (!$value) {
            $output->writeln('ERROR: Password required');

            return Command::INVALID;
        }

        $output->writeln('Thank you!');

        return Command::SUCCESS;
    }
}
