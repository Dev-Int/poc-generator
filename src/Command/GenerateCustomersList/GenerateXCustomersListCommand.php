<?php

namespace App\Command\GenerateCustomersList;

use App\Repository\CustomerRepository;
use PhpOffice\PhpSpreadsheet\Cell\CellAddress;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Stopwatch\Stopwatch;

#[AsCommand(
    name: 'generate:x-customers-list',
    description: 'Generate list of customers from generator.',
)]
final class GenerateXCustomersListCommand extends Command
{
    private const EXPORT_DIRECTORY = 'var/storage/';
    private const FILE_NAME = 'x-customers.xls';
    private const HEADER_ROW = [
        'Firstname',
        'Lastname',
        'Address',
        'PostalCode',
        'Town',
        'CreatedAt'
    ];

    public function __construct(
        private readonly CustomerRepository $customerRepository,
        private readonly Filesystem $filesystem,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $pathFilename = self::EXPORT_DIRECTORY . self::FILE_NAME;
        if (false === $this->filesystem->exists($pathFilename)) {
            $this->filesystem->touch($pathFilename);
        }

        $io = new SymfonyStyle($input, $output);
        $stopwatch = new Stopwatch(true);
        $count = 0;
        $iterCustomer = 0;

        try {
            $io->title('Generation started...');
            $stopwatch->start('x-customers');

            $spreadsheet = new Spreadsheet();
            $activeWorksheet = $spreadsheet->getActiveSheet();
            $this->prepareHeader($activeWorksheet);

            $pb = new ProgressBar($io, 1);
            $pb->setFormat(' %current%/%max% customers [%bar%] %percent:3s%% %elapsed:21s%/%estimated:-21s% %memory:21s%');
            $pb->start();
            $io->title('Extract data...');
            $customersList = $this->getCustomersList();

            $io->title('Prepare rows...');

            foreach ($customersList as $idx => $customerAddress) {
                $this->prepareRow($customerAddress->customerAddress(), $idx + 1, $activeWorksheet);
                $pb->setMaxSteps($customerAddress->countCustomer());

                if ($iterCustomer === $customerAddress->iterCustomer()) {
                    $pb->advance();
                    ++$iterCustomer;
                }
                ++$count;
            }

            $activeWorksheet->setAutoFilter($activeWorksheet->calculateWorksheetDimension());
            $writer = new Xlsx($spreadsheet);
            $io->title('Generation file...');
            $writer->save($pathFilename);

            // Free memory
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);

            $event = $stopwatch->stop('x-customers');

            $io->success(
                sprintf(
                    '%d customers\' addresses are written in Excel file with success. %s',
                    $count,
                    $event->__toString()
                )
            );
        } catch (\Exception $exception) {
            $io->error($exception->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * @return iterable<int, CustomerAddress>
     */
    private function getCustomersList(): iterable
    {
        $customers = $this->customerRepository->findAll();

        foreach ($customers as $key => $customer) {
            foreach ($customer->getAddresses() as $address) {
                yield new CustomerAddress($key, count($customers), $customer->getByAddressName($address->getName()));
            }
        }
    }

    private function prepareRow(
        array $customerAddress,
        int $rowId,
        Worksheet $activeWorksheet
    ): void {
        foreach ($customerAddress as $columnId => $value) {
            $activeWorksheet->setCellValue(
                CellAddress::fromColumnAndRow($columnId + 1, $rowId + 1),
                $value
            );
        }
    }

    private function prepareHeader(Worksheet $activeWorksheet): void
    {
        foreach (self::HEADER_ROW as $columnId => $value) {
            $activeWorksheet->setCellValue(
                CellAddress::fromColumnAndRow($columnId + 1, 1),
                $value
            );
        }
    }
}
