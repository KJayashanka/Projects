<?php
require('fpdf/fpdf.php');

if (isset($_GET["order_number"])) {
    $order_number = $_GET["order_number"];

    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ceylontask";

    // Create a new database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch order details based on the provided order_number
    $sql = "SELECT * FROM orders WHERE order_number = '$order_number'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Create a new PDF document
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(40, 10, 'Purchase Order Details');

        // Insert order details into the PDF
        $pdf->Ln(10);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Order Number: ' . $row['order_number'], 0, 1);
        $pdf->Cell(0, 10, 'Customer Name: ' . $row['customer_name'], 0, 1);
        $pdf->Cell(0, 10, 'Product Name: ' . $row['product_name'], 0, 1);
       
        if ($result===false){
            echo "Error executing the product query: " . $conn->error;
        }else if ($result->num_rows > 0) {
            // Create a table header
            $pdf->Cell(30, 10, "Product Code", 1);
            $pdf->Cell(20, 10, "Price", 1);
            $pdf->Cell(25, 10, "P Quantity", 1);
            $pdf->Cell(25, 10, "F Quantity", 1);
            $pdf->Cell(25, 10, "Net Amount", 1);
            $pdf->Cell(30, 10, "Order Date", 1);
            $pdf->Cell(30, 10, "Order Time", 1);
            $pdf->Ln(); // Move to the next line
            while ($productRow = $result->fetch_assoc()) {
                // Output product details in the table
                $pdf->Cell(30, 10, $productRow['product_code'], 1);
                $pdf->Cell(20, 10, $productRow['price'], 1);
                $pdf->Cell(25, 10, $productRow['purchase_quantity'], 1);
                $pdf->Cell(25, 10, $productRow['free_quantity'], 1);
                $pdf->Cell(25, 10, $productRow['amount'], 1);
                $pdf->Cell(30, 10, $productRow['order_date'], 1);
                $pdf->Cell(30, 10, $productRow['order_time'], 1);
                $pdf->Ln(); // Move to the next line
            }
        } else {
            $pdf->Cell(0, 10, "No product details found for this PO Number.", 0, 1);
       }

        // Output the PDF content to the browser
        $pdf->Output();
    } else {
        echo "Order not found.";
    }

    // Close the database connection
    $conn->close();
} else {
    echo "Order number not specified.";
}
?>
