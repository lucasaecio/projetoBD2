<?php

namespace App\Http\Controllers;


use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;

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
            return new CustomerResource(Customer::paginate());
        } catch (\Exception $e) {
            $return = array(
                [
                    "message" => $e->getMessage()
                ]
            );

            return (new CustomerResource($return))
                ->response()
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'CompanyName' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors());
            }

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
            $return = array(
                [
                    "message" => $e->getMessage()
                ]
            );
            return (new CustomerResource($return))
                ->response()
                ->setStatusCode(Response::HTTP_FORBIDDEN);
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
            $return = array(
                [
                    "message" => $e
                ]
            );
            return (new CustomerResource($return))
                ->response()
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
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
                $customer->$key = $request->$key ?? "";
            }

            $customer->save();

            return (new CustomerResource($customer))
                ->response()
                ->setStatusCode(Response::HTTP_OK);
        } catch (\Exception $e) {
            $return = array(
                [
                    "message" => $e
                ]
            );
            return (new CustomerResource($return))
                ->response()
                ->setStatusCode(Response::HTTP_FORBIDDEN);
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
            $customer->delete();

            return (new CustomerResource($customer))
                ->response()
                ->setStatusCode(Response::HTTP_OK);
        } catch (\Exception $e) {
            $return = array(["message" => $e]);
            return (new CustomerResource($return))
                ->response()
                ->setStatusCode(Response::HTTP_FORBIDDEN);
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
