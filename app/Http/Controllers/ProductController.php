<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

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
            if (!$SupplierID || !is_numeric($SupplierID)) {
                throw new Exception("ID do fornecedor inválido!");
            }

            $result = array("products" => [], "companies" => []);
            $dashboard = DB::select('exec RetornaProdutos ?', array($SupplierID));

            $result["products"] = array_unique(array_column($dashboard, 'ProductName')) ?? [];
            $allCompanies = array_unique(array_column($dashboard, 'CompanyName')) ?? [];

            foreach ($allCompanies as $companyName) {

                if (empty($result["companies"][$companyName])) {
                    $result["companies"][$companyName] = new stdClass();
                    $result["companies"][$companyName]->data = array();
                    $result["companies"][$companyName]->label = $companyName;
                }


                foreach ($result["products"] as $productName) {
                    $object = $this->findDataInArray($productName, $companyName, $dashboard);

                    $result["companies"][$companyName]->data[] = !$object ? 0 : intval($object->Quantity);
                }
            }


            return new ProductResource($result);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function findDataInArray($ProductName, $CompanyName, $array)
    {
        foreach ($array as $element) {
            if ($CompanyName == $element->CompanyName && $ProductName == $element->ProductName) {
                return $element;
            }
        }

        return false;
    }
}
