<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Orders;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $orders = DB::table('Orders as o')
                ->join('Order Details as od', 'o.OrderID', '=', 'o.OrderID')
                ->join('Products as p', 'p.ProductID', '=', 'od.ProductID')
                ->join('Employees as e', 'e.EmployeeID', '=', 'o.EmployeeID')
                ->select(DB::raw("CONCAT(e.FirstName, ' ', e.LastName) as EmployeeFullName"), "od.Quantity", "od.UnitPrice", "p.ProductName", "o.*")
                ->paginate(10);

            return new OrderResource($orders);
        } catch (\Exception $e) {
            $return = array(
                [
                    "message" => $e->getMessage()
                ]
            );

            return (new OrderResource($return))
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
            $order = Order::firstOrCreate([
                "CustomerID" => $request->CustomerID ?? "",
                "EmployeeID" => $request->EmployeeID ?? "",
                "OrderDate" => $request->OrderDate ?? "",
                "RequiredDate" => $request->RequiredDate ?? "",
                "ShippedDate" => $request->ShippedDate ?? "",
                "ShipVia" => $request->ShipVia ?? "",
                "Freight" => $request->Freight ?? "",
                "ShipName" => $request->ShipName ?? "",
                "ShipAddress" => $request->ShipAddress ?? "",
                "ShipCity" => $request->ShipCity ?? "",
                "ShipRegion" => $request->ShipRegion ?? "",
                "ShipPostalCode" => $request->ShipPostalCode ?? "",
                "ShipCountry" => $request->ShipCountry ?? ""
            ]);

            $order->save();

            if ($request->has("products") && count($request->products) > 0) {
                foreach ($request->products as $requestProduct) {

                    $product = Product::findOrFail($requestProduct["ProductID"]);

                    $orderDetails = new OrderDetail([
                        "OrderID" => $order->OrderID,
                        "ProductID" => $requestProduct["ProductID"],
                        "UnitPrice" => $product->UnitPrice,
                        "Quantity" => $requestProduct["Quantity"],
                        "Discount" => 0
                    ]);

                    $orderDetails->save();
                }
            }

            $order->products = $order->orderDetail()->get();

            return (new OrderResource($order))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
        } catch (\Exception $e) {
            $return = array(
                [
                    "message" => $e->getMessage()
                ]
            );
            return (new OrderResource($return))
                ->response()
                ->setStatusCode(Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function show(Orders $orders)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Orders $orders)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function destroy(Orders $orders)
    {
        //
    }
}
