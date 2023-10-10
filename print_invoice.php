<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Your database connection code
include('config.php');

// Check if the request_id parameter exists
if (isset($_GET['request_id'])) {
    $requestId = $_GET['request_id'];

    // Fetch the test request details for the given request_id
    $query = "SELECT * FROM test_requests WHERE request_id = '$requestId'";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) === 0) {
        die("Invalid request ID or test request not found.");
    }

    // Load fpdf library
    require('FPDF-master/fpdf.php');

    // Create a new PDF document
    $pdf = new FPDF();
    $pdf->AddPage();

    // Set font and size for the title
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Test Invoice', 0, 1, 'C');

    // Set font and size for the rest of the content
    $pdf->SetFont('Arial', '', 12);

    // Fetch test request details and add them to the PDF
    $row = mysqli_fetch_assoc($result);
    $pdf->Cell(0, 10, 'Request ID: ' . $row['request_id'], 0, 1);
    $pdf->Cell(0, 10, 'Patient Name: ' . $row['patient_name'], 0, 1);
    $pdf->Cell(0, 10, 'Test Name: ' . $row['test_name'], 0, 1);
    $pdf->Cell(0, 10, 'Patient Email: ' . $row['patient_email'], 0, 1);
    $pdf->Cell(0, 10, 'Patient Contact No.: ' . $row['patient_contactno'], 0, 1);
    $pdf->Cell(0, 10, 'Date Requested: ' . $row['date_requested'], 0, 1);
    $pdf->Cell(0, 10, 'Cost: RS ' . $row['cost'], 0, 1);
    $pdf->Cell(0, 10, 'Reporting Time: ' . $row['reporting_time'], 0, 1);
    $pdf->Cell(0, 10, 'Category Name: ' . $row['category_name'], 0, 1);
    $pdf->Cell(0, 10, 'Status: ' . $row['status'], 0, 1);

    $pdf->Output('invoice.pdf', 'I');
    exit();
} else {
    die("Invalid request.");
}
