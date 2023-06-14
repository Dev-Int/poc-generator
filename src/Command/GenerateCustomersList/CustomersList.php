<?php

declare(strict_types=1);

namespace App\Command\GenerateCustomersList;

final readonly class CustomersList
{
    /**
     * @param iterable<string> $customersList
     */
    public function __construct(private iterable $customersList, private int $count)
    {
    }

    /**
     * @return iterable<string>
     */
    public function customersList(): iterable
    {
        foreach ($this->customersList as $customerString) {
            yield $customerString;
        }
    }

    public function count(): int
    {
        return $this->count;
    }
}
