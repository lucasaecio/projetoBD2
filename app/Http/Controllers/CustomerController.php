<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return new CustomerResource(Customer::all());
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerRequest $request)
    {
        try {
            $request->validated();

            $customer = new Customer();

            foreach ($request->all() as $key => $value) {
                $customer->$key = $request->$key ?? "";
            }

            $customer->CustomerID = self::generateCustomerID($customer->CompanyName);
            $customer->save();

            return (new CustomerResource($customer))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return (new CustomerResource(Customer::findOrFail($id)))
                ->response()
                ->setStatusCode(Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        try {
            $customer = Customer::findOrFail($id);

            foreach ($request->all() as $key => $value) {
                if ($key == '_method') continue;

                $customer->$key = $request->$key ?? "";
            }

            $customer->save();

            return (new CustomerResource($customer))->response()->setStatusCode(Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $deleted = $customer->delete();

            return response()->json(["data" => ["deleted" => $deleted]], Response::HTTP_ACCEPTED);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    private static function generateCustomerID($companyName)
    {
        $companyName = preg_replace('/\s/', '', $companyName);
        $strLength = strlen($companyName) - 1;
        $customerID = substr($companyName, 0, 1);

        for ($i = 0; $i < 3; $i++) {
            $charIndex = random_int(1, $strLength - 1);
            $customerID .= $companyName[$charIndex];
        }

        $customerID .=  substr($companyName, -1);


        return strtoupper($customerID);
    }
}
