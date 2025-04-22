<?php
include './db/conn.php';
include './db/api.php';
include './inserview/check.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
    <link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/fontawesome-free/css/all.min.css" />
    <link rel="stylesheet" href="https://adminlte.io/themes/v3/dist/css/adminlte.min.css?v=3.2.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="shortcut icon" href="#">
</head>

<body class="sidebar-mini sidebar-closed sidebar-collapse">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light text-center" style="height: 90px; background: linear-gradient(to right, #0033FF, #0066FF, #0099FF, #00CCFF, #00FFFF);">
            <ul class="navbar-nav w-100 d-flex justify-content-between align-items-center">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button" style="color: #fff;"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item mx-auto d-flex align-items-center">
                    <span class="text bold-white" style="font-weight: bold; font-size: 28pt;">TSM MEDICAL</span>
                </li>
            </ul>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <header style="display: flex; justify-content: center; align-items: center; padding: 10px;">
                <li class="search-box" style="list-style: none; display: flex; justify-content: center;">
                    <span class="image">
                        <img src="https://storage.googleapis.com/jobfinfin_etl_image/1691741438313-d2b2da51-8b41-42b5-99a4-1cd2aef8972b.jpg" width="50" height="50" style="border-radius: 10px; margin-right: 10px;">
                    </span>
                </li>
            </header>
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <?php
                        $current_page = basename($_SERVER['PHP_SELF']);
                        ?>
                        <div class="menu-bar">
                            <div class="menu">
                                <li class="search-box <?php if ($current_page == 'index.php') echo 'active'; ?>">
                                    <a href="/index.php" class="d-flex justify-content-start align-items-center nav-link" style="color: #000;">
                                        <div class="d-flex align-items-center">
                                            <i class='bx bx-home icon'></i>
                                            <span class="ms-auto">Home</span>
                                        </div>
                                    </a>
                                </li>

                                <li class="search-box <?php if ($current_page == 'medical.php') echo 'active'; ?>">
                                    <a href="/medical.php" class="d-flex justify-content-start align-items-center nav-link" style="color: #000;">
                                        <div class="d-flex align-items-center">
                                            <i class='bx bx-plus-medical icon'></i>
                                            <span class="ms-auto">Medical</span>
                                        </div>
                                    </a>
                                </li>

                                <li class="search-box <?php if ($current_page == 'check.php') echo 'active'; ?>">
                                    <a href="/check.php" class="d-flex justify-content-start align-items-center nav-link" style="color: #000;">
                                        <div class="d-flex align-items-center">
                                            <i class='bx bxs-user-check icon'></i>
                                            <span class="ms-auto">Check</span>
                                        </div>
                                    </a>
                                </li>

                                <li class="search-box <?php if ($current_page == 'report.php') echo 'active'; ?>">
                                    <a href="/report.php" class="d-flex justify-content-start align-items-center nav-link" style="color: #000;">
                                        <div class="d-flex align-items-center">
                                            <i class='bx bxs-report icon'></i>
                                            <span class="ms-auto">Report</span>
                                        </div>
                                    </a>
                                </li>

                                <li class="search-box <?php if ($current_page == 'permission.php') echo 'active'; ?>">
                                    <a href="/permission.php" class="d-flex justify-content-start align-items-center nav-link" style="color: #000;">
                                        <div class="d-flex align-items-center">
                                            <i class='bx bx-cog icon'></i>
                                            <span class="ms-auto">Permission</span>
                                        </div>
                                    </a>
                                </li>
                            </div>
                            <div class="bottom-content">
                            </div>
                        </div>
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper" style="min-height: 2080.26px;">
            <div class="container-fluid">
                <div class="col-md-12 p-2">
                    <div class="card">
                        <div class="card-header">
                            <i class='bx bx-filter-alt'></i> Filter
                        </div>
                        <div class="card-body">
                            <form class="row" method="GET" action="">
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <label for="year" class="form-label">Year</label>
                                    <select class="form-select js-example-basic-multiple-limit" name="year" id="year">
                                        <option value="All" selected>All</option>
                                        <?php
                                        $currentYear = date('Y');
                                        $startYear = $currentYear - 10;
                                        $endYear = $currentYear + 10;

                                        for ($year = $startYear; $year <= $endYear; $year++) {
                                            if ($year == $currentYear) {
                                                echo "<option value=\"$year\">$year (ปัจจุบัน)</option>";
                                            } else {
                                                echo "<option value=\"$year\">$year</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-4 col-sm-12 mb-3">
                                    <label for="employee_id" class="form-label">Employee id</label>
                                    <select id="employee_id" class="form-select js-example-basic-multiple-limit" name="employee_id">
                                        <option selected>All</option>
                                        <?php
                                        $response = response_chk("user_tsm");

                                        if ($response === false) {
                                            echo '<option value="">เกิดข้อผิดพลาดในการดึงข้อมูล</option>';
                                        } else {
                                            if ($response) {
                                                foreach ($response as $key => $value) {
                                                    echo '<option value="' . $key . '">' . $value['employee']  . '</option>';
                                                }
                                            } else {
                                                echo '<option value="">No records found</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-4 col-sm-12 mb-3">
                                    <label for="employee_name" class="form-label">Employee name</label>
                                    <select id="employee_name" class="form-select js-example-basic-multiple-limit" name="employee_name">
                                        <option value="All" selected>All</option>
                                        <?php
                                        $response = response_chk("user_tsm");

                                        if ($response === false) {
                                            echo '<option value="">เกิดข้อผิดพลาดในการดึงข้อมูล</option>';
                                        } else {
                                            if ($response) {
                                                foreach ($response as $key => $value) {
                                                    echo '<option value="' . $value['employee name'] . '">' . (str_replace('/', '   ', substr($value['employee name'], 0, -9))) . '</option>';
                                                }
                                            } else {
                                                echo '<option value="">No records found</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <br />
                    </div>

                    <div class="col-md-12 p-1" id="dataTableContainer" style="display: <?php echo $isFiltered ? 'block' : 'none'; ?>;">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-list-ul" style="font-size: 22px;"></i>
                                <div class="card-tools">
                                    <button id="downloadExcel" class="btn btn me-1 excel"><i class='bx bxs-file-export'></i></button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table table-responsive-md">
                                    <table id="questionTable" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">No.</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Employee name</th>
                                                <th scope="col">Employee id</th>
                                                <th scope="col">Department</th>
                                                <th scope="col">Age Work</th>
                                                <th scope="col">Remark Memo</th>
                                                <th scope="col">Total amount</th>
                                                <th scope="col">Total spent</th>
                                                <th scope="col">Cost</th>
                                                <th scope="col">Balance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $id = 1;
                                            $totalPrice = 0;

                                            if ($result && $result->num_rows > 0) :
                                                while ($row = $result->fetch_assoc()) :
                                                    $medical_data = json_decode($row['medical'], true);

                                                    if (isset($medical_data['price']) && is_numeric($medical_data['price'])) {
                                                        $totalPrice += $medical_data['price'];
                                                    }
                                            ?>
                                                    <td scope="row"><?php echo $id++; ?></td>
                                                    <td><?php echo ($medical_data['date']); ?></td>
                                                    <td><?php echo ($medical_data['employee_name']); ?></td>
                                                    <td><?php echo ($medical_data['employee_id']); ?></td>
                                                    <td><?php echo ($medical_data['department']); ?></td>
                                                    <td><?php echo ($medical_data['age_year']); ?></td>
                                                    <td><?php echo ($medical_data['remark_memo']); ?></td>
                                                    <td><?php echo number_format($medical_data['amount'], 2); ?></td>
                                                    <td><?php echo number_format($medical_data['value'], 2); ?></td>
                                                    <td><?php echo number_format($medical_data['price'], 2); ?></td>
                                                    <td><?php echo number_format($medical_data['balance'], 2); ?></td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            <?php else : ?>
                                                <tr>
                                                    <td colspan="11" class="text-center">No records found</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="9" style="font-weight: bold; font-size: 16px;">รวมเป็นเงินทั้งสิ้น</td>
                                                <td style="font-weight: bold; font-size: 16px;"><?php echo number_format($totalPrice, 2); ?></td>
                                                <td></td>

                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://adminlte.io/themes/v3/plugins/jquery/jquery.min.js"></script>
        <script src="https://adminlte.io/themes/v3/dist/js/adminlte.js?v=3.2.0"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.4/xlsx.full.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const currentLocation = window.location.pathname.split('/').pop();
                const menuItems = document.querySelectorAll('.menu .search-box');

                menuItems.forEach(item => {
                    const link = item.querySelector('a');
                    if (link.href.endsWith(currentLocation)) {
                        item.classList.add('active');
                    }
                });
            });

            $(document).ready(function() {
                $('#year').select2({
                    templateResult: formatIcon,
                    templateSelection: formatIcon
                });
            });

            function formatIcon(option) {
                if (!option.id) {
                    return option.text;
                }
                var $option = $(
                    '<span><i class="bx bx-calendar"></i> ' + option.text + '</span>'
                );
                return $option;
            }

            document.addEventListener('DOMContentLoaded', function() {
                const form = document.querySelector('form');
                form.addEventListener('submit', function() {
                    document.getElementById('dataTableContainer').style.display = 'block';
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                const form = document.querySelector('form');
                form.addEventListener('keydown', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();

                        form.submit();
                    }
                });
            });
        </script>

        <script>
            $(document).ready(function() {
                $('#questionTable').DataTable({
                    pageLength: 50
                });
            });

            $(".js-example-basic-multiple-limit").select2({
                maximumSelectionLength: 2
            });


            document.getElementById('downloadExcel').addEventListener('click', function() {
                var table = document.getElementById('questionTable');
                var data = [];

                for (var i = 0; i < table.rows.length; i++) {
                    var row = table.rows[i];
                    var rowData = [];

                    if (row.style.display === 'none') continue;

                    for (var j = 0; j < row.cells.length; j++) {
                        var cellText = row.cells[j].textContent.trim();
                        rowData.push(cellText);
                    }

                    data.push(rowData);
                }

                var wb = XLSX.utils.book_new();
                var ws = XLSX.utils.aoa_to_sheet(data);

                ws['!cols'] = [{
                        wch: 10
                    },
                    {
                        wch: 20
                    },
                    {
                        wch: 20
                    },
                    {
                        wch: 20
                    },
                    {
                        wch: 20
                    },
                    {
                        wch: 10
                    }
                ];

                XLSX.utils.book_append_sheet(wb, ws, "Sheet1");

                var today = new Date();
                var filename = today.getDate() + '-' + (today.getMonth() + 1) + '-' + today.getFullYear() + '.xlsx';

                XLSX.writeFile(wb, filename);
            });
        </script>
</body>

</html>