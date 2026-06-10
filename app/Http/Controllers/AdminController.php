<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductSpecification;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasRole('admin')) abort(403);

        $products   = Product::with(['category', 'specifications'])->get();
        $categories = Category::all();
        $orders     = Order::with(['user.userData', 'items'])->orderByDesc('id')->get();
        $allLogs    = $this->readAuditLogs();
        $logPage    = max(1, (int) request('log_page', 1));
        $logPerPage = 5;
        $logTotal   = count($allLogs);
        $logPages   = max(1, (int) ceil($logTotal / $logPerPage));
        $logPage    = min($logPage, $logPages);
        $logs       = array_slice($allLogs, ($logPage - 1) * $logPerPage, $logPerPage);

        return view('admin', compact('products', 'categories', 'orders', 'logs', 'logPage', 'logPages', 'logTotal'));
    }

    private function readAuditLogs(): array
    {
        $path = storage_path('logs/audit.log');
        if (!file_exists($path)) {
            return [];
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $logs  = [];

        foreach (array_reverse($lines) as $line) {
            if (preg_match('/\{.*\}/', $line, $m)) {
                $entry = json_decode($m[0], true);
                if ($entry) {
                    $logs[] = $entry;
                }
            }
            if (count($logs) >= 300) {
                break;
            }
        }

        return $logs;
    }

    public function updateOrderStatus(Request $request, $id)
    {
        if (!auth()->user()->hasRole('admin')) abort(403);

        $request->validate(['status' => 'required|in:pending,processing,delivered,cancelled']);

        Order::findOrFail($id)->update(['status' => $request->status]);

        return redirect()->route('admin.index')->with('success', 'Order status updated.');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) abort(403);

        $request->validate([
            'name'         => 'required|string|max:32',
            'manufacturer' => 'required|string|max:64',
            'description'  => 'required|string|max:32',
            'price'        => 'required|numeric',
            'last_price'   => 'required|numeric',
            'image_url'    => 'required|string',
            'category_id'  => 'required|exists:categories,id',
        ]);

        $product = Product::create($request->only([
            'name', 'manufacturer', 'description',
            'price', 'last_price', 'image_url', 'category_id'
        ]));

        if ($request->has('specs')) {
            foreach ($request->specs as $spec) {
                if (!empty($spec['key']) && !empty($spec['value'])) {
                    $product->specifications()->create($spec);
                }
            }
        }

        return redirect()->route('admin.index')->with('success', 'Product added!');
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasRole('admin')) abort(403);

        Product::findOrFail($id)->delete();
        return redirect()->route('admin.index')->with('success', 'Product deleted!');
    }

    public function editSpecs($id)
    {
        if (!auth()->user()->hasRole('admin')) abort(403);

        $product = Product::with('specifications')->findOrFail($id);
        $categories = Category::all();
        return view('admin-specs', compact('product', 'categories'));
    }

    public function updateSpecs(Request $request, $id)
    {
        if (!auth()->user()->hasRole('admin')) abort(403);

        $request->validate([
            'name'         => 'required|string|max:32',
            'manufacturer' => 'required|string|max:64',
            'description'  => 'required|string|max:32',
            'price'        => 'required|numeric',
            'last_price'   => 'required|numeric',
            'image_url'    => 'required|string',
            'category_id'  => 'required|exists:categories,id',
        ]);

        $product = Product::findOrFail($id);

        $product->update($request->only([
            'name', 'manufacturer', 'description',
            'price', 'last_price', 'image_url', 'category_id'
        ]));

        $product->specifications()->delete();

        if ($request->has('specs')) {
            foreach ($request->specs as $spec) {
                if (!empty($spec['key']) && !empty($spec['value'])) {
                    $product->specifications()->create($spec);
                }
            }
        }

        return redirect()->route('admin.index')->with('success', 'Product updated!');
    }
}