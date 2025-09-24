<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Exports\ProductExport;
use App\Imports\ProductImport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use OpenApi\Attributes as OA;
use Illuminate\Http\JsonResponse;

class ProductApiController extends Controller
{
    #[OA\Get(
        path: "/api/products",
        summary: "Get list of products",
        operationId: "getProducts",
        tags: ["Products"],
        description: "Returns a paginated list of products.",
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful operation",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Product")
                )
            )
        ]
    )]
    public function index(Request $request)
    {
        $query = Product::query();
        //$dateFrom = $request->filled('date_from') ? Carbon::createFromFormat('d-m-Y', $request->input('date_from'))->format('Y-m-d') : null;
        //$dateTo = $request->filled('date_to') ? Carbon::createFromFormat('d-m-Y', $request->input('date_to'))->format('Y-m-d') : null;

        $dateFrom =null;
        $dateTo =null;

        try{
        if ($dateFrom && $dateTo) {
            $query->whereBetween('updated_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
        } elseif ($dateFrom) {
            $query->whereDate('updated_at', $dateFrom);
        } elseif ($dateTo) {
            $query->whereDate('updated_at', '<=', $dateTo);
        }
    } catch (\Exception $e) {
        return response()->json(['error' => 'Invalid date format. Please use dd-mm-yyyy.'], 422);
    }

        $products = $query->latest()->paginate(10);
        return response()->json($products);
    }

    #[OA\Get(
        path: "/api/products-export",
        summary: "Export list of products",
        operationId: "exportProducts",
        tags: ["Products"],
        description: "Exports a list of products to an Excel file.",
        responses: [new OA\Response(response: 200, description: "Successful operation")]
    )]
    public function export()
    {
        return response()->json(['message' => 'Export endpoint does not support JSON response. Please access via browser.'], 400);
    }

    #[OA\Post(
        path: "/api/products-import",
        summary: "Import products list from Excel file",
        operationId: "importProducts",
        tags: ["Products"],
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                "multipart/form-data" => new OA\MediaType(
                    mediaType: "multipart/form-data",
                    schema: new OA\Schema(
                        type: "object",
                        required: ["file"],
                        properties: [
                            new OA\Property(property: "file", type: "string", format: "binary")
                        ]
                    )
                )
            ]
        ),
        responses: [new OA\Response(response: 201, description: "Products imported successfully")]
    )]
    public function import(Request $request)
    {
        return response()->json(['message' => 'Products imported successfully.'], 201);
    }

    #[OA\Post(
        path: "/api/products",
        summary: "Create a new product",
        operationId: "createProduct",
        tags: ["Products"],
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                "multipart/form-data" => new OA\MediaType(
                    mediaType: "multipart/form-data",
                    schema: new OA\Schema(
                        type: "object",
                        required: ["name", "detail", "image"],
                        properties: [
                            new OA\Property(property: "name", type: "string"),
                            new OA\Property(property: "detail", type: "string"),
                            new OA\Property(property: "image", type: "string", format: "binary")
                        ]
                    )
                )
            ]
        ),
        responses: [new OA\Response(response: 201, description: "Product created successfully")]
    )]
    public function store(Request $request)
    {
        $request->validate(['name' => 'required','detail' => 'required','image' => 'required|image|mimes:jpeg,png,jpg|max:2048']);
        $input = $request->all();
        if ($image = $request->file('image')) {
            $destinationPath = 'images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        }
        Product::create($input);
        return response()->json(['message' => 'Product created successfully.'], 201);
    }

    #[OA\Get(
        path: "/api/products/{product}",
        summary: "Get a specific product",
        operationId: "getProductById",
        tags: ["Products"],
        parameters: [new OA\Parameter(name: "product", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
        responses: [new OA\Response(response: 200, description: "Successful operation")]
    )]
    public function show(Product $product)
    {
        return response()->json($product);
    }    

    #[OA\Post(
        path: "/api/products/{product}",
        summary: "Update a product by ID (POST + _method=PUT",
        operationId: "updateProductviaPost",
        tags: ["Products"],
        parameters: [new OA\Parameter(name: "product", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                "multipart/form-data" => new OA\MediaType(
                    mediaType: "multipart/form-data",
                    schema: new OA\Schema(
                        type: "object",
                        required: ["name", "detail", "image", "_method"],
                        properties: [
                            new OA\Property(property: "_method", type: "string", example:"PUT"),
                            new OA\Property(property: "name", type: "string"),
                            new OA\Property(property: "detail", type: "string"),
                            new OA\Property(property: "image", type: "string", format: "binary")
                        ]
                    )
                )
            ]
        ),
        responses: [new OA\Response(response: 200, description: "Product updated successfully")]
    )]
    public function update(Request $request, Product $product)
    {
        
        $request->validate(['name' => 'required','detail' => 'required']);
        $input = $request->all();
        if ($image = $request->file('image')) {
            $destinationPath = 'images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        } else {
            unset($input['image']);
        }
        $product->update($input);
         return response()->json(['message' => 'Product updated successfully.'], 200);
        }

    #[OA\Delete(
        path: "/api/products/{product}",
        summary: "Delete a product by ID",
        operationId: "deleteProduct",
        tags: ["Products"],
        parameters: [new OA\Parameter(name: "product", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
        responses: [new OA\Response(response: 200, description: "Product deleted successfully")]
    )]
    public function destroy(Product $product)
    {
        $product->delete();
       return response()->json(['message' => 'Product deleted successfully.'], 200);
    }
    
}
