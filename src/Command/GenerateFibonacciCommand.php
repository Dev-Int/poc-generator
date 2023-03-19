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
    name: 'generate:fibonacci',
    description: 'Generate a Fibonacci suite with maximum iteration from array.',
)]
class GenerateFibonacciCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('max', InputArgument::OPTIONAL, 'Maximum iteration.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $stopwatch = new Stopwatch();

        $io->title('Generation started...');
        $stopwatch->start('fibonacci');

        if ($input->getArgument('max')) {
            $fibonacciSuite = $this->fibonacci((int) $input->getArgument('max'));
        } else {
            $fibonacciSuite = $this->fibonacci();
        }

        foreach ($fibonacciSuite as $number) {
            $io->write(sprintf('%d ', $number));
        }
        $event = $stopwatch->stop('fibonacci');

        $io->success(
            sprintf('The fibonacci suite is generated with success. %s', $event->__toString())
        );

        return Command::SUCCESS;
    }

    private function fibonacci(int $maxIter = 10): iterable
    {
        $current = 1;
        $previous = 0;
        $return = [];

        for ($iter = 0; $iter < $maxIter; $iter++) {
            $temp = $current;
            $current = $previous + $current;
            $previous = $temp;

            $return[] = $current;
        }

        return $return;
    }
}
