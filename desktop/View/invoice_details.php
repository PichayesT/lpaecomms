<?php
// filepath: c:\Warmpserver\www\lpaecomms\desktop\View\invoice_details.php
include '../model/config.php';

if (isset($_GET['id'])) {
    $invoiceId = intval($_GET['id']);

    // Fetch invoice details
    $stmt = $conn->prepare("SELECT * FROM lpa_invoice_items WHERE lpa_invitem_inv_no = ?");
    $stmt->bind_param("i", $invoiceId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h3>Invoice Details (ID: $invoiceId)</h3>";
        echo "<table border='1' style='width: 100%; text-align: left;'>";
        echo "<thead>
                <tr>
                    <th>Invoice No</th>
                    <th>Stock Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
              </thead>";
        echo "<tbody>";

        $grandTotal = 0; // Initialize total amount
        while ($row = $result->fetch_assoc()) {
            $grandTotal += $row['lpa_invitem_stock_amount']; // Add to total
            echo "<tr>
                    <td>" . htmlspecialchars($row['lpa_invitem_inv_no']) . "</td>
                    <td>" . htmlspecialchars($row['lpa_invitem_stock_name']) . "</td>
                    <td>" . htmlspecialchars($row['lpa_invitem_qty']) . "</td>
                    <td>" . number_format($row['lpa_invitem_stock_price'], 2) . "</td>
                    <td>" . number_format($row['lpa_invitem_stock_amount'], 2) . "</td>
                  </tr>";
        }

        // Add a row for the total at the end
        echo "<tr>
                <td colspan='4' style='text-align: right; font-weight: bold;'>Grand Total:</td>
                <td style='font-weight: bold;'>" . number_format($grandTotal, 2) . "</td>
              </tr>";

        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>No details found for this invoice.</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<p>Invalid invoice ID.</p>";
}
?>

<?php
    require 'header.php';
    require 'footer.php';
?>