<?php
// Include the FPDF library
require('fpdf/fpdf.php');

// Database connection configuration 
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'laravel-test';

// Create a database connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the PO Number from the query string
if (isset($_GET['po_number'])) {
    $po_number = $_GET['po_number'];

    // Retrieve purchase order details based on the PO Number
    $poSql = "SELECT * FROM purchase_orders WHERE po_number = '$po_number'";
    $poResult = $conn->query($poSql);
 
    // Retrieve purchase order and invoice details based on the PO Number
    $sql = "SELECT po.*, inv.invoice_id, inv.invoice_number
    FROM purchase_orders_lines po
    LEFT JOIN invoices inv ON po.po_number = inv.po_number
    WHERE po.po_number = '$po_number'";

    
    $result = $conn->query($sql);

    if ($result === false) {
        // Query execution failed
        echo "Error executing the query: " . $conn->error;
    } else 

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Create a new FPDF instance
        $pdf = new FPDF();
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('Arial', 'B', 12);

        // Output PO Number as title
        $pdf->Cell(0, 10, 'Purchase Order Invoice', 0, 1, 'C',);
        
        // Fetch purchase order details based on the PO Number
        $poDetailsSql = "SELECT * FROM purchase_orders WHERE po_number = '$po_number'";
        $poDetailsResult = $conn->query($poDetailsSql);

        if ($poDetailsResult === false) {
            echo "Error executing the purchase order details query: " . $conn->error;
        } else if ($poDetailsResult->num_rows > 0) {
            $poDetails = $poDetailsResult->fetch_assoc();
        } else {
            echo "Purchase order details not found for PO Number: $po_number";
        }

        // Output purchase order details
        
        $pdf->Cell(0, 20, "Purchase Order Details", 0, 1,);
        $pdf->Cell(0, 10, "Invoice ID: {$row['invoice_id']}", 0, 1);
        $pdf->Cell(0, 10, "Invoice Number: {$row['invoice_number']}", 0, 1);
        $pdf->Cell(0, 10, "PO Number: " . $po_number, 0, 1);
        $pdf->Cell(0, 10, "Region: " . $poDetails['region'], 0, 1);
        $pdf->Cell(0, 10, "Territory: " . $poDetails['territory'], 0, 1);
        $pdf->Cell(0, 10, "Distributor: " . $poDetails['distributor'], 0, 1);
        $pdf->Cell(0, 10, "Order Date: " . $poDetails['order_date'], 0, 1);
        $pdf->Cell(0, 10, "Remark: " . $poDetails['remark'], 0, 1);


        $totalPrice = 0;

        // Fetch product details associated with the PO Number
        $productSql = "SELECT sku_code, sku_name, distributor_price, mrp, units, total_price FROM purchase_orders_lines WHERE po_number = '$po_number'";
        $productResult = $conn->query($productSql);
        
        if ($productResult===false){
            echo "Error executing the product query: " . $conn->error;
        }else if ($productResult->num_rows > 0) {
            // Create a table header
            $pdf->Cell(30, 10, "SKU Code", 1);
            $pdf->Cell(40, 10, "SKU Name", 1);
            $pdf->Cell(40, 10, "Distributor Price", 1);
            $pdf->Cell(20, 10, "MRP", 1);
            $pdf->Cell(20, 10, "Units", 1);
            $pdf->Cell(30, 10, "Total Price", 1);
            $pdf->Ln(); // Move to the next line
            while ($productRow = $productResult->fetch_assoc()) {
                // Output product details in the table
                $pdf->Cell(30, 10, $productRow['sku_code'], 1);
                $pdf->Cell(40, 10, $productRow['sku_name'], 1);
                $pdf->Cell(40, 10, $productRow['distributor_price'], 1);
                $pdf->Cell(20, 10, $productRow['mrp'], 1);
                $pdf->Cell(20, 10, $productRow['units'], 1);
                $pdf->Cell(30, 10, $productRow['total_price'], 1);
                $pdf->Ln(); // Move to the next line
            }
        } else {
             $pdf->Cell(0, 10, "No product details found for this PO Number.", 0, 1);
        }
 
        // Output the PDF as a download
        $pdf->Output();

        // Close the database connection
        $conn->close();
    } else {
        echo "Invoice not found for PO Number: $po_number";
    }
} else {
    echo "PO Number not specified.";
}
?>
