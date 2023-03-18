<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;

#[AsCommand(
    name: 'generate:range',
    description: 'Generate range from range php function.',
)]
final class GenerateRangeCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('start', InputArgument::REQUIRED, 'The start of the range to generate.')
            ->addArgument('limit', InputArgument::REQUIRED, 'The limit of the range to generate.')
            ->addArgument('step', InputOption::VALUE_OPTIONAL, 'The stepping for the range.', [1])
            ->addOption('down', null, InputOption::VALUE_NONE, 'The step direction for the range.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $start = (int) $input->getArgument('start');
        $limit = (int) $input->getArgument('limit');
        $step = (int) $input->getArgument('step')[0];

        if ($input->getOption('down')) {
            $step = -$step;
        }

        $stopwatch = new Stopwatch(true);

        try {
            $io->title('Generation started...');

            $stopwatch->start('range');

            $range = range($start, $limit, $step);

            foreach ($range as $number) {
                $io->write(sprintf('%d ', $number));
            }
            $event = $stopwatch->stop('range');

            $io->success(
                sprintf('The range is generated with success. %s', $event->__toString()
                )
            );
        } catch (\Exception $exception) {
            $io->error($exception->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
