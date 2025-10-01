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
        
        $productCreated = \App\Models\product::where('created_at', '>=', $fromDate)
            ->get()
            ->groupBy(fn($p)=>$p->created_at->format('d M'))
            ->map->count()
            ->toArray();
        $productUpdated = \App\Models\product::where('updated_at', '>=', $fromDate)
            ->get()
            ->groupBy(fn($p)=>$p->updated_at->format('d M'))
            ->map->count()
            ->toArray();

        return view('dashboard', ['products' => $products, 'typeCounts' => $typeCounts, 
        'newProducts' => $newProducts, 
        'updateProducts' => $updateProducts, 
        'productCreated'=>$productCreated, 'productUpdated'=>$productUpdated, 'range'=>$range,]);
    }


    public function index(Request $request): View
    {
        $query = Product::query();
        $dateFrom = $request->filled('date_from') ? Carbon::createFromFormat('d-m-Y', $request->input('date_from'))->format('Y-m-d') : null;
        $dateTo = $request->filled('date_to') ? Carbon::createFromFormat('d-m-Y', $request->input('date_to'))->format('Y-m-d') : null;

        if ($dateFrom && $dateTo) {
            $query->whereBetween('updated_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
        } elseif ($dateFrom) {
            $query->whereDate('updated_at', $dateFrom);
        } elseif ($dateTo) {
            $query->whereDate('updated_at', '<=', $dateTo);
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
        return view('products.create');
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
        return view('products.edit', compact('product'));
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
