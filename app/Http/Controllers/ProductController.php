<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
        ->when($request->input('name'), function($query, $name){
           return $query->where('name', 'like', "%$name%");
    })
        ->orderBy('id','desc')
        ->paginate(10);
        return view('pages.products.index', compact('products'));
    }
    public function create()
    {
        return view('pages.products.create');
    }
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'category' => 'required',
        ]);

        $data = ([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'category' => $request->category,
        ]);

        Product::create($data);

        return redirect()->route('product.index')->with('success', 'product created successfully');

    }

    public function edit($id)
    {
        $product = Product::find($id);
        return view('pages.products.edit', compact('product'));
    }
    public function update(Request $request, Product $product)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'category' => 'required',
        ]);

        $data = ([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'category' => $request->category,
        ]);

        $product->update($data);
        
        return redirect()->route('product.index')->with('success', 'product updated successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('product.index')->with('success', 'product deleted successfully');
    }

}

