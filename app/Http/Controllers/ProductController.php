<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $products = DB::table('Products as p')
                ->join('Suppliers as s', 's.SupplierID', '=', 'p.SupplierID')
                ->join('Categories as c', 'c.CategoryID', '=', 'p.CategoryID')
                ->select("s.CompanyName", "c.CategoryName", "p.*")
                ->get();

            return new ProductResource($products);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function dashboard($SupplierID)
    {
        try {
            if (!$SupplierID) {
                throw new Exception("ID do fornecedor inválido!");
            }

            $dashboard = DB::select('exec RetornaProdutos ?', array($SupplierID));

            return new ProductResource($dashboard);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
