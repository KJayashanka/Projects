<?php
require('fpdf/fpdf.php'); // Include the FPDF library

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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['view_selected'])) {
    // Get selected PO numbers from the form
    $selected_invoices = $_POST['selected_invoices'];

    // Create a new PDF instance
    $pdf = new FPDF();
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('Arial', 'B', 12);

    // Loop through the selected PO numbers and generate invoices
    foreach ($selected_invoices as $po_number) {
        // Retrieve purchase order details based on the PO Number
        $poSql = "SELECT * FROM purchase_orders WHERE po_number = '$po_number'";
        $poResult = $conn->query($poSql);

        if ($poResult === false) {
            echo "Error executing the purchase order details query: " . $conn->error;
            continue; // Skip this order and continue with the next one
        }

        if ($poResult->num_rows > 0) {
            $poDetails = $poResult->fetch_assoc();

            // Retrieve purchase order lines based on the PO Number
            $linesSql = "SELECT * FROM purchase_orders_lines WHERE po_number = '$po_number'";
            $linesResult = $conn->query($linesSql);

            if ($linesResult === false) {
                echo "Error executing the purchase order lines query: " . $conn->error;
                continue; // Skip this order and continue with the next one
            }

            // Output PO Number as title
            $pdf->Cell(0, 10, 'Purchase Order Invoice', 0, 1, 'C');
            $pdf->Cell(0, 10, "PO Number: " . $po_number, 0, 1);
            $pdf->Cell(0, 10, "Region: " . $poDetails['region'], 0, 1);
            $pdf->Cell(0, 10, "Territory: " . $poDetails['territory'], 0, 1);
            $pdf->Cell(0, 10, "Distributor: " . $poDetails['distributor'], 0, 1);
            $pdf->Cell(0, 10, "Order Date: " . $poDetails['order_date'], 0, 1);
            $pdf->Cell(0, 10, "Remark: " . $poDetails['remark'], 0, 1);

            $totalPrice = 0;

            if ($linesResult->num_rows > 0) {
                // Create a table header for purchase order lines
                $pdf->Cell(30, 10, "SKU Code", 1);
                $pdf->Cell(40, 10, "SKU Name", 1);
                $pdf->Cell(40, 10, "Distributor Price", 1);
                $pdf->Cell(20, 10, "MRP", 1);
                $pdf->Cell(20, 10, "Units", 1);
                $pdf->Cell(30, 10, "Total Price", 1);
                $pdf->Ln(); // Move to the next line

                while ($line = $linesResult->fetch_assoc()) {
                    // Output purchase order lines in the table
                    $pdf->Cell(30, 10, $line['sku_code'], 1);
                    $pdf->Cell(40, 10, $line['sku_name'], 1);
                    $pdf->Cell(40, 10, $line['distributor_price'], 1);
                    $pdf->Cell(20, 10, $line['mrp'], 1);
                    $pdf->Cell(20, 10, $line['units'], 1);
                    $pdf->Cell(30, 10, $line['total_price'], 1);
                    $pdf->Ln(); // Move to the next line

                    $totalPrice += $line['total_price'];
                }
            }

            // Output the total price
            $pdf->Cell(150, 10, "Total Price", 1);
            $pdf->Cell(30, 10, $totalPrice, 1);
            $pdf->Ln(); // Move to the next line
            $pdf->AddPage(); // Add a page for the next invoice
        } else {
            echo "Purchase order details not found for PO Number: $po_number";
        }
    }

    // Output the PDF 
    $pdf->Output();
    
} else {
    echo "No selected purchase orders.";
}


// Close the database connection
$conn->close();
?>
