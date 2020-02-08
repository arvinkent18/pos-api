<?php

namespace App\Http\Controllers\API;

use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController;
use App\Http\Resources\Customer as CustomerResource;

class CustomerController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::all();

        #return response()->json([
        #    'error' => false,
        #    'customers' => $customers,
        #], 200) ;
        return $this->sendResponse(CustomerResource::collection($customers), 'Customers retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $customer = Customer::create($input);

        #return response()->json([
        #    'error' => false,
        #    'customer' => $customer,
        #], 201);
        return $this->sendResponse(new CustomerResource($customer), 'Customer created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::with('orders')
                        ->with('orders.details')
                        ->find($id);
        if (is_null($customer)) {
            return $this->sendError('Customer not found.');
        }
        
        #return response()->json([
        #    'error' => false,
        #    'customer' => $customer,
        #], 200);
        return $this->sendResponse(new CustomerResource($customer), 'Customer retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
        ]);

        if ($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $customer->update($input);

        #return response()->json([
        #    'error' => false,
        #    'customer' => $customer,
        #], 200);
        return $this->sendResponse(new CustomerResource($customer), 'Customer updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        #return response()->json([
        #    'error' => false,
        #    'message' => 'The customer with the id {$customer->id} has been deleted.',
        #]);
        return $this->sendResponse([], 'Product deleted successfully');
    }

    public function order(Request $request, $id)
    {
        $customer = Customer::find($id);
        
        $order = $customer->orders()->create([
            'order_date' => Carbon::now(),
            'order_notes' => $request->input('order_notes'),
        ]);

        $items = $request->input('items');

        foreach ($items as $item) {
            $order->details()->create([
                'inventory_id' => $item['inventory_id'],
                'quantity' => $item['quantity'],
            ]);
        }

        return response()->json([
            'error' => false,
            'order' => $order,
        ], 201);
    }
}
