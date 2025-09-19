<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Exports\ProductExport;
use App\Imports\ProductImport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Product::query();

        // Convert dd-mm-yyyy to yyyy-mm-dd for filtering
        // Handle separate date_from and date_to inputs
        $dateFrom = $request->filled('date_from') ? Carbon::createFromFormat('d-m-Y', $request->input('date_from'))->format('Y-m-d') : null;
        $dateTo = $request->filled('date_to') ? Carbon::createFromFormat('d-m-Y', $request->input('date_to'))->format('Y-m-d') : null;

        if ($dateFrom && $dateTo) {
            $query->whereBetween('updated_at', [
                $dateFrom . ' 00:00:00',
                $dateTo . ' 23:59:59'
            ]);
        } elseif ($dateFrom) {
            $query->whereDate('updated_at', $dateFrom);
        } elseif ($dateTo) {
            $query->whereDate('updated_at', '<=', $dateTo);
        }

        $products = $query->latest()->paginate(10);

        return view('products.index', compact('products'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    //export xls function
    public function export() 
    {
    return Excel::download(new ProductExport, 'products.xlsx');
    }

    //import xls function
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls'
        ]);

    Excel::import(new ProductImport, $request->file('file'));

        return redirect()->route('products.index')
                         ->with('success', 'Products imported successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create():View
    {
        return view ('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request-> validate ([
            'name'=>'required',
            'details' => 'required',
            'image' => 'required|image |mimes: jpeg,png, jpg, svg|max:2048',
        ]);

        $input = $request -> all();

        if ($image =$request -> file('image')){
            $destinationPath ='images/';
            $profileImage =date ('YmdHis') . "." . $image -> getClientOriginalExtension();
            $image -> move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        }

        Product::create($input);

        return redirect()->route('products.index')
                         ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): View
    {
        return view('products.show',compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        return view('products.edit',compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $request -> validate([
            'name' => 'required',
            'details' => 'required'
        ]);

        $input = $request->all();
        
        if ($image =$request -> file('image')){
            $destinationPath ='images/';
            $profileImage =date ('YmdHis') . "." . $image -> getClientOriginalExtension();
            $image -> move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        }else{
            unset ($input ['image']);
        }

        $product -> update ($input);
        
        return redirect()->route('products.index')
                        ->with('success','Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('products.index')
                        ->with('success','Product deleted successfully');
    }
}