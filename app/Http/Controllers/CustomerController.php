<?php

namespace App\Http\Controllers;

use App\Services\CustomerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Customers')]
class CustomerController extends Controller
{
    private CustomerService $customerService;

    public function __construct(
        CustomerService $customerService
    )
    {
        $this->customerService = $customerService;
    }

    #[OA\Get(
        path: '/api/v1/customers',
        summary: 'List all customers',
        tags: ['Customers'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of customers',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/CustomerResource')
                )
            )
        ]
    )]
    public function index(): JsonResponse
    {
        return success_response($this->customerService->getAllCustomers());
    }

    #[OA\Post(
        path: '/api/v1/customers',
        summary: 'Create a new customer',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'surname', 'phone', 'email', 'password'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'John'),
                    new OA\Property(property: 'surname', type: 'string', example: 'Doe'),
                    new OA\Property(property: 'phone', type: 'string', example: '+5511999999999'),
                    new OA\Property(property: 'email', type: 'string', example: 'john.doe@example.com'),
                    new OA\Property(property: 'password', type: 'string', example: 'secret123')
                ]
            )
        ),
        tags: ['Customers'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Customer created successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/CustomerResource')
            )
        ]
    )]
    public function store(Request $request): JsonResponse
    {
        $customer = $this->customerService->createCustomer($request);
        return success_response($customer, "Customer created successfully", Response::HTTP_CREATED);
    }

    #[OA\Get(
        path: '/api/v1/customers/{id}',
        summary: 'Get a customer by ID',
        tags: ['Customers'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID of the customer',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Customer details',
                content: new OA\JsonContent(ref: '#/components/schemas/CustomerResource')
            ),
            new OA\Response(response: 404, description: 'Customer not found')
        ]
    )]
    public function show(string $id): JsonResponse
    {
        $customer = $this->customerService->getCustomerById($id);
        return success_response($customer);
    }

    #[OA\Put(
        path: '/api/v1/customers/{id}',
        summary: 'Update a customer',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'John'),
                    new OA\Property(property: 'surname', type: 'string', example: 'Doe'),
                    new OA\Property(property: 'phone', type: 'string', example: '+5511999999999'),
                    new OA\Property(property: 'email', type: 'string', example: 'john.doe@example.com'),
                    new OA\Property(property: 'password', type: 'string', example: 'secret123')
                ]
            )
        ),
        tags: ['Customers'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID of the customer to update',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Customer updated successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/CustomerResource')
            )
        ]
    )]
    public function update(Request $request, string $id)
    {
        $customer = $this->customerService->updateCustomer($request, $id);
        return success_response($customer);
    }

    #[OA\Delete(
        path: '/api/v1/customers/{id}',
        summary: 'Delete a customer',
        tags: ['Customers'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID of the customer to delete',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Customer deleted successfully'
            )
        ]
    )]
    public function destroy(string $id)
    {
        $this->customerService->deleteCustomer($id);
        return success_response(null, null, 204);
    }
}
