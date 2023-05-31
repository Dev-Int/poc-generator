<?php

declare(strict_types=1);

namespace App\Command\GenerateCustomersList;

final class GenerateCustomerListException extends \Exception
{
    public function __construct(string $getMessage)
    {
        parent::__construct($this->message);
    }
}
