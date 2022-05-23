<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Demonstrates:
 *  - Interactively ask a question with value completion
 *  - Set value to missing argument with interaction
 *  - Use colors in output
 *  - Write errors to stderr.
 */
#[AsCommand(
    name: 'app:ask-color',
    description: 'Interactively ask for a color',
)]
class AskColorCommand extends Command
{
    private const COLORS = [
        'black', 'red', 'green', 'yellow', 'blue', 'magenta', 'cyan', 'white', 'default', 'gray',
        'bright-red', 'bright-green', 'bright-yellow', 'bright-blue', 'bright-magenta', 'bright-cyan', 'bright-white',
    ];

    protected function configure(): void
    {
        $this->addArgument('color', InputArgument::REQUIRED, 'Color', null, self::COLORS);
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        if (!$input->getArgument('color')) {
            $question = new ChoiceQuestion(
                'Please select your favorite color',
                // choices can also be PHP objects that implement __toString() method
                self::COLORS,
                0
            );
            $question->setErrorMessage('Color "%s" is invalid.');

            $value = $io->askQuestion($question);
            $input->setArgument('color', $value);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $color = $input->getArgument('color');
        try {
            $outputStyle = new OutputFormatterStyle(
                str_starts_with($color, 'bright-') ? 'black' : 'white',
                $color,
                ['bold']
            );
        } catch (InvalidArgumentException $error) {
            // Error messages must be written to stderr when available
            $errOutput = $output instanceof ConsoleOutputInterface ? $output->getErrorOutput() : $output;
            $errOutput->writeln('<error>'.$error->getMessage().'</error>');

            return Command::INVALID;
        }
        $output->getFormatter()->setStyle('color', $outputStyle);
        $output->writeln('You have just selected: <color> '.$color.' </color>');

        return Command::SUCCESS;
    }
}
