<?php

declare(strict_types=1);

namespace App\Command\GenerateCustomersList;

final class CustomerAddress
{
    /**
     * @param array<string> $customerAddress
     */
    public function __construct(
        private readonly int $iterCustomer,
        private readonly int $countCustomer,
        private readonly array $customerAddress
    ) {
    }

    public function iterCustomer(): int
    {
        return $this->iterCustomer;
    }

    public function countCustomer(): int
    {
        return $this->countCustomer;
    }

    public function customerAddress(): array
    {
        return $this->customerAddress;
    }
}
