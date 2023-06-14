<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CustomerController extends AbstractController
{
    public function __construct(private readonly CustomerRepository $customerRepository)
    {
    }

    #[Route('/customers', name: 'app_customer_list')]
    public function index(): Response
    {
        $customers = $this->customerRepository->findAll();

        return $this->render('customer/index.html.twig', [
            'customers' => $customers,
        ]);
    }
}
