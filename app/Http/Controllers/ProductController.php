<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Exports\ProductExport;
use App\Imports\ProductImport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;


class ProductController extends Controller
{
    public function dashboard(Request $request){
        $products= Product::all();
        //$products = Product::select ('created_at', 'updated_at', 'type')
        //-> orderBy('created_at')
        //->get();
        $typeCounts= $products-> groupBy ('type')-> map->count()->sortKeys();
        $newProducts = Product::whereDate('created_at', today())->count();
        $updateProducts = Product::whereDate('updated_at', today())->count();
        $range = $request-> get('range','7');
        $fromDate = match($range){
            'today' => Carbon::today(),
            'yesterday'=> Carbon::yesterday(),
            '7'=>Carbon::today()-> subdays(6),
            '30'=>Carbon::today()->subdays(29),
            '90'=>Carbon::today()->subdays(89),
            default => Carbon::today()->subdays(6),
        };
        
        // Group by ISO date (Y-m-d) first, then build an ordered series from $fromDate -> today
        $createdRaw = \App\Models\Product::where('created_at', '>=', $fromDate)
            ->get()
            ->groupBy(fn($p) => $p->created_at->format('Y-m-d'))
            ->map->count();

        $updatedRaw = \App\Models\Product::where('updated_at', '>=', $fromDate)
            ->get()
            ->groupBy(fn($p) => $p->updated_at->format('Y-m-d'))
            ->map->count();

        $productCreated = [];
        $productUpdated = [];

        $start = Carbon::parse($fromDate)->startOfDay();
        $end = Carbon::today()->startOfDay();
        for ($date = $start; $date->lte($end); $date->addDay()) {
            $key = $date->format('Y-m-d');
            $productCreated[$key] = $createdRaw->get($key, 0);
            $productUpdated[$key] = $updatedRaw->get($key, 0);
        }

        return view('dashboard', ['products' => $products, 'typeCounts' => $typeCounts, 
        'newProducts' => $newProducts, 
        'updateProducts' => $updateProducts, 
        'productCreated'=>$productCreated, 'productUpdated'=>$productUpdated, 'range'=>$range,]);
    }


    public function index(Request $request): View
    {
        $query = Product::query();
        $start = $request->input('start');
        $end = $request->input('end');
        $dateType = $request->input('date_type', 'updated_at'); // default

        if ($start && $end) {
    $query->whereBetween($dateType, [
        Carbon::parse($start)->startOfDay(),
        Carbon::parse($end)->endOfDay()
    ]);
} elseif ($start) {
    $query->where($dateType, '>=', Carbon::parse($start)->startOfDay());
} elseif ($end) {
    $query->where($dateType, '<=', Carbon::parse($end)->endOfDay());
}


        $products = $query->latest()->paginate(10);
        return view('products.index', compact('products'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    
    public function export()
    {
        return Excel::download(new ProductExport, 'products.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,csv,xls']);
        Excel::import(new ProductImport, $request->file('file'));
        return redirect()->route('products.index')->with('success', 'Products imported successfully.');
    }

    public function create(): View
    {
        $types = \App\Models\Product::select('type')
                ->distinct()
                ->whereNotNull('type')
                ->pluck('type');
        return view('products.create', compact ('types'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['name' => 'required','detail' => 'required','type' => 'required','image' => 'required|image|mimes:jpeg,png,jpg|max:2048']);
        $input = $request->all();
        if ($image = $request->file('image')) {
            $destinationPath = 'images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        }
        Product::create($input);
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    
    public function edit(Product $product): View
    {   
        $types = \App\Models\Product::select('type')
                ->distinct()
                ->whereNotNull('type')
                ->pluck('type');
        return view('products.edit', compact('product', 'types'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $request->validate(['name' => 'required','detail' => 'required', 'type'=> 'required']);
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
        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        return redirect()->route('products.index')->with('success','Product deleted successfully');
    }
}
