<?php
include('config.php');
session_start();
$successMessage = ''; // Variable to store success message

if (!isset($_SESSION['adminlogin']) || !$_SESSION['adminlogin']) {
    header('location: admin_login.php');
    exit();
}

// Edit category
if (isset($_POST['edit_category'])) {
    $categoryId = $_POST['category_id'];
    $newCategoryName = $_POST['category_name'];
    $query = "UPDATE test_categories SET category_name = '$newCategoryName' WHERE category_id = $categoryId";
    mysqli_query($conn, $query);
}

// Delete category
if (isset($_GET['delete_category'])) {
    $categoryId = $_GET['delete_category'];
    $categoryId = mysqli_real_escape_string($conn, $categoryId);
    $query = "DELETE FROM test_categories WHERE category_id = '$categoryId'";
    mysqli_query($conn, $query);

    header("Location: view_categories.php");
    exit();
}

// Fetch diagnostic centers for the dropdown
$centers = array();
$sql = "SELECT center_id, center_name FROM diagnostic_centers";
$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $centers[] = $row;
    }
} else {
    // Handle any errors
    echo "Error: " . mysqli_error($conn);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Diagnostic Test Categories</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert library -->

    <style>
        body {
            background-color: antiquewhite;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            background-color: #fff;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody + tbody {
            border-top: 2px solid #dee2e6;
        }

        .table-sm th,
        .table-sm td {
            padding: 0.3rem;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .table-bordered thead th,
        .table-bordered thead td {
            border-bottom-width: 2px;
        }

        .btn-secondary {
            color: #fff;
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-danger {
            color: #fff;
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-secondary:hover,
        .btn-secondary:focus {
            color: #fff;
            background-color: #5a6268;
            border-color: #545b62;
        }

        .btn-danger:hover,
        .btn-danger:focus {
            color: #fff;
            background-color: #c82333;
            border-color: #bd2130;
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>

    <div class="container mt-5">
        <h3 class="text-center mt-4 mb-4">Manage Categories</h3>
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Category ID</th>
                    <th>Category Name</th>
                    <th>Diagnostic Center</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT tc.category_id, tc.category_name, dc.center_name
                          FROM test_categories tc
                          LEFT JOIN diagnostic_centers dc ON tc.center_id = dc.center_id";
                $result = mysqli_query($conn, $query);
                
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <tr>
                        <td><?php echo $row['category_id']; ?></td>
                        <td><?php echo $row['category_name']; ?></td>
                        <td><?php echo $row['center_name']; ?></td>
                        <td>
                            <a href="edit_category.php?category_id=<?php echo $row['category_id']; ?>" class="btn btn-secondary">Edit</a>
                            <a href="#" class="btn btn-danger" onclick="confirmDelete(<?php echo $row['category_id']; ?>)">Delete</a>
                        </td>
                    </tr>
                <?php
                    }
                } else {
                    // Handle any errors
                    echo "Error: " . mysqli_error($conn);
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        function confirmDelete(categoryId) {
            Swal.fire({
                title: 'Confirmation',
                text: 'Are you sure you want to delete this category?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // User confirmed deletion, redirect to the delete URL
                    window.location.href = '?delete_category=' + categoryId;
                }
            });
        }
    </script>
</body>
</html>
