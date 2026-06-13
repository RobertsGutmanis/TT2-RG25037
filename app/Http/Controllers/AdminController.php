<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            abort_unless(auth()->user()?->hasRole('admin'), 403);

            return $next($request);
        });
    }

    public function index()
    {
        $products = Product::with(['category', 'specifications'])->get();
        $categories = Category::all();
        $orders = Order::with(['user.userData', 'items'])->orderByDesc('id')->get();

        $allLogs = $this->readAuditLogs();
        $logPerPage = 5;
        $logTotal = count($allLogs);
        $logPage = max(1, min((int) request('log_page', 1), (int) ceil($logTotal / $logPerPage) ?: 1));
        $logPages = max(1, (int) ceil($logTotal / $logPerPage));
        $logs = array_slice($allLogs, ($logPage - 1) * $logPerPage, $logPerPage);

        return view('admin', compact('products', 'categories', 'orders', 'logs', 'logPage', 'logPages', 'logTotal'));
    }

    private function readAuditLogs(): array
    {
        $path = storage_path('logs/audit.log');

        if (! file_exists($path)) {
            return [];
        }

        $logs = [];

        foreach (array_reverse(file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)) as $line) {
            $start = strpos($line, '{');

            if ($start !== false) {
                $entry = json_decode(substr($line, $start), true);
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
        $request->validate(['status' => 'required|in:pending,processing,delivered,cancelled']);
        Order::findOrFail($id)->update(['status' => $request->status]);

        return redirect()->route('admin.index')->with('success', 'Order status updated.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:32',
            'manufacturer' => 'required|string|max:64',
            'description' => 'required|string|max:32',
            'price' => 'required|numeric',
            'image_url' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product = Product::create(array_merge(
            $request->only(['name', 'manufacturer', 'description', 'price', 'image_url', 'category_id']),
            ['last_price' => $request->price]
        ));

        foreach ($request->input('specs', []) as $spec) {
            if (! empty($spec['key']) && ! empty($spec['value'])) {
                $product->specifications()->create($spec);
            }
        }

        return redirect()->route('admin.index')->with('success', 'Product added!');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();

        return redirect()->route('admin.index')->with('success', 'Product deleted!');
    }

    public function editSpecs($id)
    {
        $product = Product::with('specifications')->findOrFail($id);
        $categories = Category::all();

        return view('admin-specs', compact('product', 'categories'));
    }

    public function updateSpecs(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:32',
            'manufacturer' => 'required|string|max:64',
            'description' => 'required|string|max:32',
            'price' => 'required|numeric',
            'last_price' => 'required|numeric',
            'image_url' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->only(['name', 'manufacturer', 'description', 'price', 'last_price', 'image_url', 'category_id']));

        $product->specifications()->delete();

        foreach ($request->input('specs', []) as $spec) {
            if (! empty($spec['key']) && ! empty($spec['value'])) {
                $product->specifications()->create($spec);
            }
        }

        return redirect()->route('admin.index')->with('success', 'Product updated!');
    }
}
