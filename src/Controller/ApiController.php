<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

// Services
use App\Service\Importer;
use App\Service\CustomerService;

class ApiController extends AbstractController
{

    private $importer;
    private $customer;

    public function __construct(Importer $importer, CustomerService $customer) {
        $this->importer = $importer;
        $this->customer = $customer;
    }

    /**
     * @Route("/test", name="app_api")
     */
    public function index(): JsonResponse
    {

        $this->importer->import();

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ApiController.php',
        ]);
    }

    /**
     * @Route("/customers", name="get_customers", methods={"GET"})
     */
    public function customers(): JsonResponse
    {
        return $this->json($this->customer->listCustomer());
    }

    /**
     * @Route("/customers/{customerId}", name="get_customer", methods={"GET"})
     */
    public function specificCustomer(int $customerId): JsonResponse
    {

        return $this->json($this->customer->getSpecificCustomer($customerId));
    }
}
