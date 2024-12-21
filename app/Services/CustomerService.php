<?php

namespace App\Services;

use App\Exceptions\CustomerNotFoundException;
use App\Models\Customer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class CustomerService
{
    public function getAllCustomers()
    {
        return Customer::all();
    }

    public function createCustomer(Request $request)
    {
        $data = $this->validateCustomerData($request);
        return $this->storeCustomer($data);
    }

    public function getCustomerById(string $id)
    {
        return $this->findCustomerOrFail($id);
    }

    public function updateCustomer(Request $request, string $id)
    {
        $customer = $this->findCustomerOrFail($id);

        $data = $this->validateCustomerData($request, $customer);
        $this->updateCustomerData($customer, $data);

        return $customer;
    }

    public function deleteCustomer(string $id)
    {
        $customer = $this->findCustomerOrFail($id);
        $this->deleteCustomerRecord($customer);
    }

    private function validateCustomerData(Request $request, Customer $customer = null): array
    {
        $uniquePhoneRule = $customer
            ? Rule::unique('users')->ignore($customer->id)
            : 'unique:users';

        $uniqueEmailRule = $customer
            ? Rule::unique('users')->ignore($customer->id)
            : 'unique:users';

        return $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'phone' => ['required', $uniquePhoneRule],
            'email' => ['required','email',$uniqueEmailRule],
            'password' => 'required|string|min:6'
        ]);
    }

    private function storeCustomer(array $data): Customer
    {
        $data['password'] = Hash::make($data['password']);
        return Customer::create($data);
    }

    private function findCustomerOrFail(string $id): Customer
    {
        $customer = Customer::find($id);
        if (!$customer) {
            throw new CustomerNotFoundException();
        }

        return $customer;
    }

    private function updateCustomerData(Customer $customer, array $data): void
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $customer->update($data);
    }

    private function deleteCustomerRecord(Customer $customer): void
    {
        $customer->delete();
    }
}
