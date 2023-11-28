<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Region;
use App\Models\Territory;
use App\Models\User;
use App\Models\Zone;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderLine;
use App\Models\Product; // You need to import the Product model
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PurchaseOrderController extends Controller {
    public function index() {
        $purchaseOrders = PurchaseOrder::with('distributorUser')->get();
 
        // Fetch unique values for Region from the database
        $regions = DB::table('purchase_orders')->distinct()->pluck('region');

        // Fetch unique values for Territory
        $territories = DB::table('purchase_orders')->distinct()->pluck('territory');

        // Fetch unique values for PO Number
        $poNumbers = DB::table('purchase_orders')->distinct()->pluck('po_number');

        return view('purchase_orders.index', compact('purchaseOrders', 'regions', 'territories', 'poNumbers'));
    }

    public function create() {
        // Generate a unique PO number here (you can use your existing logic)
        $poNumber = $this->generateRandomString(5); // Replace with your logic

        $zones = Zone::all();
        $regions = Region::all();
        $territories = Territory::all();
        $distributors = User::where('username', '<>', '')
            ->select('id', 'username')
            ->get();
        $products = Product::all();

        return view('purchase_orders.create', compact('poNumber', 'zones', 'regions', 'territories', 'distributors', 'products'));
    }

    public function store(Request $request) {
        $request->validate([
            'po_number' => 'required|unique:purchase_orders',
            'zone' => 'required',
            'region' => 'required',
            'territory' => 'required',
            'distributor' => 'required',
            'remark' => 'nullable',
            'products' => 'required|array',
            'unit_amount.*' => 'required|numeric|min:1',
        ]);
    
        $distributorId = $request->input('distributor');
        $distributor = User::find($distributorId);
        $regionId = $request->input('region');
        $region = Region::find($regionId);
        $zoneId = $request->input('zone');
        $zone = Zone::find($zoneId);
        $territoryId = $request->input('territory');
        $territory = Territory::find($territoryId);

        $purchaseOrder = new PurchaseOrder();
        $purchaseOrder->zone = $zone->short_description;  // Save the zone name
        $purchaseOrder->region = $region->region_name;  // Save the region name
        $purchaseOrder->territory = $territory->territory_name;
        $purchaseOrder->distributor = $distributor->username;
        $purchaseOrder->remark = $request->input('remark');
        $purchaseOrder->order_date = now();
        $purchaseOrder->po_number = $request->input('po_number');
        $purchaseOrder->save();

        $selectedProducts = $request->input('products');
        $skuCodes = $request->input('sku_code');
        $skuNames = $request->input('sku_name');
        $distributorPrices = $request->input('distributor_price');
        $mrps = $request->input('mrp');
        $unitAmounts = $request->input('unit_amount');

        foreach ($selectedProducts as $key => $product) {
            $purchaseOrderLine = new PurchaseOrderLine();
            $purchaseOrderLine->purchase_order_id = $purchaseOrder->id;
            $purchaseOrderLine->sku_code = $skuCodes[$key];
            $purchaseOrderLine->sku_name = $skuNames[$key];
            $purchaseOrderLine->distributor_price = $distributorPrices[$key];
            $purchaseOrderLine->mrp = $mrps[$key];
            $purchaseOrderLine->units = $unitAmounts[$key];
            $purchaseOrderLine->total_price = $mrps[$key] * $unitAmounts[$key];
            $purchaseOrderLine->save();
        }

        return redirect()->route('purchase_orders.index')->with('success', 'Purchase Order created successfully.');
    }

    public function filter(Request $request) {
        $selected_region = $request->input('region');
        $selected_territory = $request->input('territory');
        $selected_po_numbers = $request->input('po_number');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        
        // Build your SQL query based on the selected filters
        $query = DB::table('purchase_orders')->select('region', 'territory', 'distributor', 'order_date', 'po_number', 'remark')
            ->where(function ($query) use ($selected_region, $selected_territory, $selected_po_numbers, $from_date, $to_date) {
                if (!empty($selected_region)) {
                    $query->where('region', $selected_region);
                }

                if (!empty($selected_territory)) {
                    $query->where('territory', $selected_territory);
                }

                if (!empty($selected_po_numbers)) {
                    $query->whereIn('po_number', $selected_po_numbers);
                }

                if (!empty($from_date)) {
                    $query->where('order_date', '>=', $from_date);
                }

                if (!empty($to_date)) {
                    $query->where('order_date', '<=', $to_date);
                }
            });

            $filteredPurchaseOrders = $query->get();
            $regions = DB::table('purchase_orders')->distinct()->pluck('region');
            $territories = DB::table('purchase_orders')->distinct()->pluck('territory');
            $poNumbers = DB::table('purchase_orders')->distinct()->pluck('po_number');
        return view('purchase_orders.filtered', compact('filteredPurchaseOrders','regions','territories','poNumbers'));
    }
    
    private function generateRandomString($length) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }
    public function viewInvoice($po_number) {
        // Retrieve the purchase order details based on $po_number
        $purchaseOrder = PurchaseOrder::where('po_number', $po_number)->first();
    
        if (!$purchaseOrder) {
            return redirect()->back()->with('error', 'Purchase Order not found.');
        }
    
        // You can load a view to display the details of the purchase order and generate the invoice
        $pdf = PDF::loadView('purchase_orders.invoice', compact('purchaseOrder'));
    
        return $pdf->stream('invoice.pdf');
    }
    
    public function generateInvoices(Request $request) {
        $selectedInvoices = $request->input('selected_invoices');
    
        // Initialize an array to store generated invoice files
        $invoiceFiles = [];
    
        // Loop through the selected invoices and generate invoices for each one
        foreach ($selectedInvoices as $po_number) {
            // Retrieve the purchase order details based on $po_number
            $purchaseOrder = PurchaseOrder::where('po_number', $po_number)->first();
    
            if ($purchaseOrder) {
                // Pass the $purchaseOrder variable to the view
                $pdf = PDF::loadView('purchase_orders.invoice', compact('purchaseOrder'));
    
                // Save the PDF invoice to a file
                $fileName = 'invoice_' . $po_number . '.pdf';
                $pdf->save(storage_path('invoices/' . $fileName));
    
                // Add the file path to the list of generated invoices
                $invoiceFiles[] = storage_path('invoices/' . $fileName);
            }
        }
    
        // Compress the generated invoices into a ZIP file
        $zipFileName = 'invoices.zip';
        $zip = new \ZipArchive();
        $zip->open(storage_path($zipFileName), \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
    
        foreach ($invoiceFiles as $invoiceFile) {
            $zip->addFile($invoiceFile, basename($invoiceFile));
        }
    
        $zip->close();
    
        // Create a response to download the ZIP file
        return response()->download(storage_path($zipFileName))->deleteFileAfterSend(true);
    }
    
    
    public function show($po_number)
    {
        // Retrieve the purchase order details based on $po_number
        $purchaseOrder = PurchaseOrder::where('po_number', $po_number)->first();

        if (!$purchaseOrder) {
            return redirect()->back()->with('error', 'Purchase Order not found.');
        }

        // You can load a view to display the details of the purchase order
        return view('purchase_orders.show', compact('purchaseOrder'));
    }

   // Method to get regions based on zone
    public function getRegions($zoneId) {
        $regions = Region::where('zone_id', $zoneId)->pluck('region_name', 'id');
        return response()->json($regions);
    }

    // Method to get territories based on region
    public function getTerritories($regionId) {
        $territories = Territory::where('region_id', $regionId)->pluck('territory_name', 'id');
        return response()->json($territories);
    }

    // Method to get distributors based on territory
    public function getDistributors($territoryId) {
        $distributors = User::where('territory_id', $territoryId)->pluck('username', 'id');
        return response()->json($distributors);
    }

}
