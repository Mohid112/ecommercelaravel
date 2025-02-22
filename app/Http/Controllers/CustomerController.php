<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CustomerService;
use Illuminate\Http\Response;

class CustomerController extends Controller
{
    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * Display a listing of customers.
     */
    public function index()
    {
        return response()->json($this->customerService->getAllCustomers(), Response::HTTP_OK);
    }

    /**
     * Store a newly created customer.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string|max:20',
        ]);

        $customer = $this->customerService->addCustomer($validatedData);

        return response()->json($customer, Response::HTTP_CREATED);
    }

    /**
     * Display the specified customer.
     */
    public function show($id)
    {
        return response()->json($this->customerService->getCustomerById($id), Response::HTTP_OK);
    }

    /**
     * Update the specified customer.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:customers,email,' . $id,
            'phone' => 'nullable|string|max:20',
        ]);

        $customer = $this->customerService->updateCustomer($id, $validatedData);

        return response()->json($customer, Response::HTTP_OK);
    }

    /**
     * Remove the specified customer.
     */
    public function destroy($id)
    {
        $this->customerService->deleteCustomer($id);

        return response()->json(['message' => 'Customer deleted successfully'], Response::HTTP_OK);
    }
}
