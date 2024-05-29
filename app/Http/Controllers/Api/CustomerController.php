<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->get();
        return response()->json([
            'status' => true,
            'message' => 'Customers retrieved successfully',
            'data' => $customers
        ], 200);
    }

    public function show($id)
    {
        if(Customer::where('id', $id)->exists()) {
            $customer = Customer::findOrFail($id);
            return response()->json([
                'status' => true,
                'message' => 'Customer found successfully.',
                'data' => $customer
            ], 200);
        }
        else {
            return response()->json([
                'status' => false,
                'message' => 'Customer not found!'
            ], 404);
        } 
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:customers|email'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $customer = Customer::create($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Customer created successfully.',
            'data' => $customer
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:customers,email,'.$id
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if(Customer::where('id', $id)->exists()) {
            $customer = Customer::findOrFail($id);
            $customer->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Customer updated successfully.',
                'data' => $customer
            ], 200);
        }
        else {
            return response()->json([
                'status' => false,
                'message' => 'Customer not found!'
            ], 404);
        } 
    }

    public function delete($id)
    {
        if(Customer::where('id', $id)->exists()) {
            $customer = Customer::findOrFail($id);
            $customer->delete();

            return response()->json([
                'status' => true,
                'message' => 'Customer deleted successfully.'
            ], 202);
        }
        else {
            return response()->json([
                'status' => false,
                'message' => 'Customer not found!'
            ], 404);
        } 
    }
}
