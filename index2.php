<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "feedback_db";
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    $url = "https://";
else
    $url = "http://";
// Append the host(domain name, ip) to the URL.   
$url .= $_SERVER['HTTP_HOST'];

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the feedback table
$sql = "SELECT content, path, created_at FROM feedback";
$result = $conn->query($sql);

// Close the connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Feedback</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css">
</head>

<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-4">Manage Feedback</h2>
            <button class="btn btn-primary" id="excelButton">Export Excel</button>
        </div>
        <table id="feedbackTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>Content</th>
                    <th>path</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Populate DataTable with fetched data
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['content']}</td>";
                        echo "<td>{$row['path']}</td>";
                        echo "<td>{$row['created_at']}</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap modal for detailed view -->
    <div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-labelledby="feedbackModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="feedbackModalLabel">Feedback Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="modalContent"></div>
                    <div id="modalCreatedAt"></div>
                    <div id="modalPath"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>


    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#feedbackTable').DataTable({
                dom: 'Bfrtip', // Include 'B' for buttons
                buttons: [],
                order: [
                    [2, 'desc']
                ], // Sort by the 4th column (index 3) in descending order
                lengthMenu: [25, 50, 1000], // Custom step sizes for paging
                pageLength: 10,
            });

            // Add Excel export button to DataTable
            new $.fn.dataTable.Buttons(table, {
                buttons: [{
                    extend: 'excelHtml5',
                    text: 'Export Excel',
                    className: 'btn btn-primary'
                }]
            });

            // Show modal with content and path when row is clicked
            $('#feedbackTable tbody').on('click', 'tr', function() {
                var rowData = $('#feedbackTable').DataTable().row(this).data();
                $('#modalContent').html('<strong>Content:</strong> ' + rowData[0]);
                $('#modalCreatedAt').html('<strong>Create at:</strong> ' + rowData[2]);
                $('#modalPath').html('<img src="' + rowData[1] + '" class="img-fluid" alt="no image">');
                $('#feedbackModal').modal('show');
            });

            // Add Excel export button outside the DataTable
            $('#excelButton').on('click', function() {
                table.buttons(0).trigger();
            });
        });
    </script>
</body>

</html>