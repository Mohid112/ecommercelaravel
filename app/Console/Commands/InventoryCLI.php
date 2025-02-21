<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Customer;
use Carbon\Carbon;
use Exception;

class InventoryCLI extends Command
{
    protected $signature = 'inventory:cli';
    protected $description = 'E-Commerce Inventory Management System CLI';

    public function handle()
    {
        while (true) {
            $this->info("\n📦 E-Commerce Inventory Management System");
            $this->info("1. Product Management");
            $this->info("2. Category Management");
            $this->info("3. Order Management");
            $this->info("4. Customer Management");
            $this->info("5. Generate Reports");
            $this->info("6. Exit");

            $choice = $this->ask("Enter your choice");

            switch ($choice) {
                case '1': $this->manageProducts(); break;
                case '2': $this->manageCategories(); break;
                case '3': $this->manageOrders(); break;
                case '4': $this->manageCustomers(); break;
                case '5': $this->generateReports(); break;
                case '6': $this->info("Exiting..."); return;
                default: $this->error("❌ Invalid choice! Try again.");
            }
        }
    }

    private function manageProducts()
    {
        while (true) {
            $this->info("\n📦 Product Management");
            $this->info("1. Add Product");
            $this->info("2. View All Products");
            $this->info("3. Search Product");
            $this->info("4. Update Product");
            $this->info("5. Delete Product");
            $this->info("6. Back to Main Menu");

            $choice = $this->ask("Enter choice");

            switch ($choice) {
                case '1': $this->addProduct(); break;
                case '2': $this->viewProducts(); break;
                case '3': $this->searchProduct(); break;
                case '4': $this->updateProduct(); break;
                case '5': $this->deleteProduct(); break;
                case '6': return;
                default: $this->error("❌ Invalid choice! Try again.");
            }
        }
    }

    private function addProduct()
    {
        try {
            $name = $this->ask("Enter product name");
            if (empty($name)) throw new Exception("Product name cannot be empty.");

            $description = $this->ask("Enter description");
            $categoryId = (int) $this->ask("Enter category ID");
            if (!Category::find($categoryId)) throw new Exception("Invalid category ID.");

            $price = (float) $this->ask("Enter price");
            if ($price <= 0) throw new Exception("Price must be a positive number.");

            $quantity = (int) $this->ask("Enter quantity");
            if ($quantity < 0) throw new Exception("Quantity cannot be negative.");

            Product::create([ 'name' => $name, 'description' => $description, 'category_id' => $categoryId, 'price' => $price, 'quantity' => $quantity ]);
            $this->info("✅ Product added successfully!");
        } catch (Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
        }
    }

    private function viewProducts()
    {
        $products = Product::all();
        if ($products->isEmpty()) {
            $this->info("No products found.");
        } else {
            foreach ($products as $product) {
                $this->info("ID: {$product->id} | Name: {$product->name} | Price: \${$product->price}");
            }
        }
    }

    private function searchProduct()
    {
        $searchName = $this->ask("Enter product name to search");
        $product = Product::where('name', 'LIKE', "%$searchName%")->first();
        if ($product) {
            $this->info("✅ Found: {$product->name} - \${$product->price}");
        } else {
            $this->error("❌ Product not found!");
        }
    }

    private function updateProduct()
    {
        try {
            $productId = (int) $this->ask("Enter product ID to update");
            $product = Product::find($productId);
            if (!$product) throw new Exception("Product not found.");

            $newPrice = (float) $this->ask("Enter new price");
            if ($newPrice <= 0) throw new Exception("Price must be positive.");

            $newQuantity = (int) $this->ask("Enter new quantity");
            if ($newQuantity < 0) throw new Exception("Quantity cannot be negative.");

            $product->update(['price' => $newPrice, 'quantity' => $newQuantity]);
            $this->info("✅ Product updated successfully!");
        } catch (Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
        }
    }

    private function deleteProduct()
    {
        try {
            $deleteId = (int) $this->ask("Enter product ID to delete");
            if (!Product::find($deleteId)) throw new Exception("Product not found.");

            Product::destroy($deleteId);
            $this->info("✅ Product deleted successfully!");
        } catch (Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
        }
    }



    // 📁 Category Management
    private function manageCategories()
{
    while (true) {
        $this->info("\n📁 Category Management");
        $this->info("1. Add Category");
        $this->info("2. View All Categories");
        $this->info("3. Back to Main Menu");

        $choice = $this->ask("Enter choice");

        switch ($choice) {
            case '1':
                try {
                    $name = $this->ask( "Enter category name");
                    if (empty($name)) {
                        throw new \Exception("❌ Category name cannot be empty.");
                    }

                    $description = $this->ask("Enter category description");
                    if (empty($description)) {
                        throw new \Exception("❌ Category description cannot be empty.");
                    }

                    Category::create(['name' => $name, 'description' => $description]);
                    $this->info("✅ Category added successfully!");
                } catch (\Exception $e) {
                    $this->error($e->getMessage());
                }
                break;

            case '2':
                try {
                    $categories = Category::all();
                    if ($categories->isEmpty()) {
                        $this->info(string: "📂 No categories found.");
                    } else {
                        foreach ($categories as $category) {
                            $this->info(string: "{$category->id} | {$category->name}");
                        }
                    }
                } catch (\Exception $e) {
                    $this->error(string: "❌ Error fetching categories: " . $e->getMessage());
                }
                break;

            case '3':
                return;

            default:
                $this->error("❌ Invalid choice! Try again.");
        }
    }
}


    // 📦 Order Management
    private function manageOrders()
    {
        while (true) {
            $this->info("\n📦 Order Management");
            $this->info("1. Create Order");
            $this->info("2. View All Orders");
            $this->info("3. Back to Main Menu");
    
            $choice = $this->ask("Enter choice");
    
            switch ($choice) {
                case '1':
                    try {
                        $customerId = $this->ask("Enter customer ID");
                        if (!is_numeric($customerId) || !Customer::find($customerId)) {
                            throw new \Exception("❌ Invalid customer ID.");
                        }
    
                        $productId = $this->ask("Enter product ID");
                        if (!is_numeric($productId) || !Product::find($productId)) {
                            throw new \Exception("❌ Invalid product ID.");
                        }
    
                        $quantity = $this->ask("Enter quantity");
                        if (!is_numeric($quantity) || $quantity <= 0) {
                            throw new \Exception("❌ Quantity must be a positive number.");
                        }
    
                        $orderDate = Carbon::now();
    
                        Order::create([
                            'customer_id' => $customerId,
                            'product_id' => $productId,
                            'quantity' => $quantity,
                            'order_date' => $orderDate,
                            'status' => 'Pending'
                        ]);
    
                        $this->info("✅ Order created successfully!");
                    } catch (\Exception $e) {
                        $this->error($e->getMessage());
                    }
                    break;
    
                case '2':
                    try {
                        $orders = Order::all();
                        if ($orders->isEmpty()) {
                            $this->info("📦 No orders found.");
                        } else {
                            foreach ($orders as $order) {
                                $this->info("Order ID: {$order->id} | Product: {$order->product_id} | Quantity: {$order->quantity} | Status: {$order->status}");
                            }
                        }
                    } catch (\Exception $e) {
                        $this->error("❌ Error fetching orders: " . $e->getMessage());
                    }
                    break;
    
                case '3':
                    return;
    
                default:
                    $this->error("❌ Invalid choice! Try again.");
            }
        }
    }
    
    // 👤 Customer Management
    private function manageCustomers()
    {
        while (true) {
            $this->info("\n👤 Customer Management");
            $this->info("1. Add Customer");
            $this->info("2. View All Customers");
            $this->info("3. Back to Main Menu");
    
            $choice = $this->ask("Enter choice");
    
            switch ($choice) {
                case '1':
                    try {
                        $name = $this->ask("Enter customer name");
                        if (empty(trim($name))) {
                            throw new \Exception("❌ Customer name cannot be empty.");
                        }
    
                        $address = $this->ask("Enter customer address");
                        if (empty(trim($address))) {
                            throw new \Exception("❌ Address cannot be empty.");
                        }
    
                        $contact = $this->ask("Enter contact number");
                        if (!preg_match('/^\d{10,15}$/', $contact)) {
                            throw new \Exception("❌ Invalid contact number. It should contain only 10-15 digits.");
                        }
    
                        Customer::create([
                            'name' => $name,
                            'address' => $address,
                            'contact' => $contact
                        ]);
    
                        $this->info("✅ Customer added successfully!");
                    } catch (\Exception $e) {
                        $this->error($e->getMessage());
                    }
                    break;
    
                case '2':
                    try {
                        $customers = Customer::all();
                        if ($customers->isEmpty()) {
                            $this->info("👤 No customers found.");
                        } else {
                            foreach ($customers as $customer) {
                                $this->info("{$customer->id} | {$customer->name} | {$customer->address}");
                            }
                        }
                    } catch (\Exception $e) {
                        $this->error("❌ Error fetching customers: " . $e->getMessage());
                    }
                    break;
    
                case '3':
                    return;
    
                default:
                    $this->error("❌ Invalid choice! Try again.");
            }
        }
    }
    
    private function generateReports()
{
    try {
        $reportService = new \App\Services\ReportService();

        $this->info("\n📊 Report Menu:");
        $this->info("1. Products by Category");
        $this->info("2. Orders by Date Range");
        $this->info("3. Top-Selling Products");
        $this->info("4. Back to Main Menu");

        $choice = (int) $this->ask("Enter your choice:");

        switch ($choice) {
            case 1:
                try {
                    $categoryId = (int) $this->ask("Enter Category ID:");
                    if ($categoryId <= 0) {
                        throw new \Exception("❌ Invalid category ID. Please enter a valid number.");
                    }

                    $products = $reportService->getProductsByCategory(categoryId: $categoryId);
                    if ($products->isEmpty()) {
                        $this->info("No products found in this category.");
                    } else {
                        $this->info("\n📦 Products in Category:");
                        foreach ($products as $product) {
                            $this->info("ID: {$product->id}, Name: {$product->name}, Price: {$product->price}");
                        }
                    }
                } catch (\Exception $e) {
                    $this->error($e->getMessage());
                }
                break;

            case 2:
                try {
                    $startDate = $this->ask("Enter Start Date (YYYY-MM-DD):");
                    $endDate = $this->ask("Enter End Date (YYYY-MM-DD):");

                    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $startDate) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $endDate)) {
                        throw new \Exception("❌ Invalid date format. Please enter dates in YYYY-MM-DD format.");
                    }

                    if (Carbon::parse($startDate)->gt(Carbon::parse($endDate))) {
                        throw new \Exception("❌ Start date cannot be after the end date.");
                    }

                    $orders = $reportService->getOrdersByDateRange(startDate: $startDate, endDate: $endDate);
                    if ($orders->isEmpty()) {
                        $this->info("No orders found in this date range.");
                    } else {
                        $this->info("\n📜 Orders from $startDate to $endDate:");
                        foreach ($orders as $order) {
                            $this->info("Order ID: {$order->id}, Customer: {$order->customer->name}, Total: {$order->total_price}");
                        }
                    }
                } catch (\Exception $e) {
                    $this->error($e->getMessage());
                }
                break;

            case 3:
                try {
                    $limit = (int) $this->ask("How many top-selling products do you want to see?", 10);
                    if ($limit <= 0) {
                        throw new \Exception("❌ Invalid limit. Enter a positive number.");
                    }

                    $topProducts = $reportService->getTopSellingProducts(limit: $limit);

                    if ($topProducts->isEmpty()) {
                        $this->info("No top-selling products found.");
                    } else {
                        $this->info("\n🏆 Top Selling Products:");
                        foreach ($topProducts as $item) {
                            $this->info("Product ID: {$item->product->id}, Name: {$item->product->name}, Sold: {$item->total_quantity}");
                        }
                    }
                } catch (\Exception $e) {
                    $this->error($e->getMessage());
                }
                break;

            case 4:
                return;

            default:
                $this->error("❌ Invalid choice. Please enter a valid option.");
        }
    } catch (\Exception $e) {
        $this->error("❌ An error occurred: " . $e->getMessage());
    }
}

}

