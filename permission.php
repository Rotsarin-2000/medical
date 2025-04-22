<?php
$servername = 'localhost';
$username = 'tsmmedicaldb';
$password = 'Wd719z$4c';
$dbname = 'tsm_medical_2023';

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
function response_chk($response)
{
    $url = 'https://api1.tsmolymer.co.th/share';
    $data = [
        'type' => $response
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen(json_encode($data))
    ));

    $response = curl_exec($ch);

    if ($response === false) {
        die('Error occurred while fetching the data: ' . curl_error($ch));
    }

    curl_close($ch);

    return json_decode($response, true);
}

$sql = "SELECT * FROM permissions";
$result = $conn->query($sql);

$permissions_sql = "SELECT * FROM page_access";
$permissions_result = $conn->query($permissions_sql);

$permissions = [];
while ($row = $permissions_result->fetch_assoc()) {
    $permissions[$row['employee_id']][$row['page']] = $row['permission'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://adminlte.io/themes/v3/dist/css/adminlte.min.css?v=3.2.0" />
    <link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/fontawesome-free/css/all.min.css" />
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" /> -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
    <link rel="stylesheet" href="../tsm_medical/css/styles.css" />
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
                                    <a href="/tsm_medical/index.php" class="d-flex justify-content-start align-items-center nav-link" style="color: #000;">
                                        <div class="d-flex align-items-center">
                                            <i class='bx bx-home icon'></i>
                                            <span class="ms-auto">Home</span>
                                        </div>
                                    </a>
                                </li>

                                <li class="search-box <?php if ($current_page == 'medical.php') echo 'active'; ?>">
                                    <a href="/tsm_medical/medical.php" class="d-flex justify-content-start align-items-center nav-link" style="color: #000;">
                                        <div class="d-flex align-items-center">
                                            <i class='bx bx-plus-medical icon'></i>
                                            <span class="ms-auto">Medical</span>
                                        </div>
                                    </a>
                                </li>

                                <li class="search-box <?php if ($current_page == 'check_manager.php') echo 'active'; ?>">
                                    <a href="/tsm_medical/check_manager.php" class="d-flex justify-content-start align-items-center nav-link" style="color: #000;">
                                        <div class="d-flex align-items-center">
                                            <i class='bx bxs-user-detail icon'></i>
                                            <span class="ms-auto">Check Employee</span>
                                        </div>
                                    </a>
                                </li>

                                <li class="search-box <?php if ($current_page == 'report.php') echo 'active'; ?>">
                                    <a href="/tsm_medical/report.php" class="d-flex justify-content-start align-items-center nav-link" style="color: #000;">
                                        <div class="d-flex align-items-center">
                                            <i class='bx bxs-report icon'></i>
                                            <span class="ms-auto">Report</span>
                                        </div>
                                    </a>
                                </li>

                                <li class="search-box <?php if ($current_page == 'check_user.php') echo 'active'; ?>">
                                    <a href="/tsm_medical/check_user.php" class="d-flex justify-content-start align-items-center nav-link" style="color: #000;">
                                        <div class="d-flex align-items-center">
                                            <i class='bx bx-user-circle icon'></i>
                                            <span class="ms-auto">Myself</span>
                                        </div>
                                    </a>
                                </li>

                                <li class="search-box <?php if ($current_page == 'permission.php') echo 'active'; ?>">
                                    <a href="/tsm_medical/permission.php" class="d-flex justify-content-start align-items-center nav-link" style="color: #000;">
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
            <div class="container-xxl">
                <div class="col-md-12 p-2">
                    <div class="card">
                        <div class="card-header">
                            <i class='bx bx-filter-alt'></i> Add Permissions
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <form class="row g-3" action="/tsm_medical/inserdata/permission.php" method="POST">
                                    <div class="col-sm-4 col-md-5 col-lg-4">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="employee_id" class="form-label">Employee Id</label>
                                                    <select id="employee_id" class="form-control" name="employee_id" required>
                                                        <option selected>--- All ---</option>
                                                        <?php
                                                        $response = response_chk("user_tsm");
                                                        if ($response === false) {
                                                            echo '<option value="">เกิดข้อผิดพลาดในการดึงข้อมูล</option>';
                                                        } else {
                                                            if ($response) {
                                                                foreach ($response as $key => $value) {
                                                                    echo '<option value="' . $key . '" 
                                                                    data-employee_name="' . (str_replace('/', '  ', substr($value['employee name'], 0, -9))) . '"
                                                                    data-department="' . ($value['department']) . '" 
                                                                    data-level="' . ($value['level']) . '" 
                                                                    data-position="' . ($value['position']) . '">' .
                                                                        ($value['employee']) .
                                                                        '</option>';
                                                                }
                                                            } else {
                                                                echo '<option value="">ไม่พบข้อมูล</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="col-md-12">
                                                    <label for="employee_name" class="form-label">Employee Name</label>
                                                    <input type="text" id="employee_name" class="form-control" name="employee_name" required>
                                                </div>

                                                <div class="col-md-12">
                                                    <label for="department" class="form-label">Department</label>
                                                    <input type="text" id="department" class="form-control" name="department" required>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="level" class="form-label">level</label>
                                                    <input type="text" id="level" class="form-control" name="level" required>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="position" class="form-label">position</label>
                                                    <input type="text" id="position" class="form-control" name="position" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-8 col-md-5 offset-md-2 col-lg-8 offset-lg-0">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Page</th>
                                                    <th scope="col">Access Permission</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th scope="row">1</th>
                                                    <td>Medical</td>
                                                    <td>
                                                        <input type="radio" name="medical" value="1"> มีสิทธิ์
                                                        <input type="radio" name="medical" value="0" checked> ไม่มีสิทธิ์
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">2</th>
                                                    <td>Check</td>
                                                    <td>
                                                        <input type="radio" name="check" value="1"> มีสิทธิ์
                                                        <input type="radio" name="check" value="0" checked> ไม่มีสิทธิ์
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th scope="row">3</th>
                                                    <td>Department</td>
                                                    <td>
                                                        <input type="radio" name="check_manager" value="1"> มีสิทธิ์
                                                        <input type="radio" name="check_manager" value="0" checked> ไม่มีสิทธิ์
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">4</th>
                                                    <td>Myself</td>
                                                    <td>
                                                        <input type="radio" name="check_user" value="1"> มีสิทธิ์
                                                        <input type="radio" name="check_user" value="0" checked> ไม่มีสิทธิ์
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">5</th>
                                                    <td>Report</td>
                                                    <td>
                                                        <input type="radio" name="report" value="1"> มีสิทธิ์
                                                        <input type="radio" name="report" value="0" checked> ไม่มีสิทธิ์
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">6</th>
                                                    <td>Permission</td>
                                                    <td>
                                                        <input type="radio" name="permission" value="1"> มีสิทธิ์
                                                        <input type="radio" name="permission" value="0" checked> ไม่มีสิทธิ์
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-12 d-flex justify-content-center">
                                        <button type="submit" class="btn btn-primary">Add permission</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 p-2">
                    <div class="card">
                        <div class="card-header">
                            <i class='bx bx-filter-alt'></i>User Permissions
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 col-md-5 offset-md-2 col-lg-12 offset-lg-0">
                                    <form method="post" action="/tsm_medical/inserupdate/save_permissions.php">
                                        <table id="questionTable" class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Employee ID</th>
                                                    <th scope="col">Employee Name</th>
                                                    <th scope="col">Department</th>
                                                    <th scope="col">Level</th>
                                                    <th scope="col">Position</th>
                                                    <th scope="col">Page</th>
                                                    <th scope="col">Access Permission</th>
                                                    <!-- <th scope="col">Delete</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $pages = ['Medical', 'Check', 'Check Manager', 'Check User', 'Report', 'Permission'];

                                                while ($employee = $result->fetch_assoc()) {
                                                    foreach ($pages as $page) { ?>
                                                        <tr>
                                                            <th scope="row"><?php echo $employee['employee_id']; ?></th>
                                                            <td><input type="text" class="form-control" style="pointer-events: none;" name="employee_name_<?php echo $employee['employee_id']; ?>" value="<?php echo $employee['employee_name']; ?>" /></td>
                                                            <td><input type="text" class="form-control" style="pointer-events: none;" name="department_<?php echo $employee['employee_id']; ?>" value="<?php echo $employee['department']; ?>" /></td>
                                                            <td><input type="text" class="form-control" style="pointer-events: none;" name="level_<?php echo $employee['employee_id']; ?>" value="<?php echo $employee['level']; ?>" /></td>
                                                            <td><input type="text" class="form-control" style="pointer-events: none;" name="position_<?php echo $employee['employee_id']; ?>" value="<?php echo $employee['position']; ?>" /></td>
                                                            <td><?php echo $page; ?></td>
                                                            <td>
                                                                <input type="radio" name="<?php echo strtolower(str_replace(' ', '_', $page)); ?>_<?php echo $employee['employee_id']; ?>" value="1"
                                                                    <?php echo isset($permissions[$employee['employee_id']][strtolower(str_replace(' ', '_', $page))]) && $permissions[$employee['employee_id']][strtolower(str_replace(' ', '_', $page))] == 1 ? 'checked' : ''; ?>> มีสิทธิ์
                                                                <input type="radio" name="<?php echo strtolower(str_replace(' ', '_', $page)); ?>_<?php echo $employee['employee_id']; ?>" value="0"
                                                                    <?php echo isset($permissions[$employee['employee_id']][strtolower(str_replace(' ', '_', $page))]) && $permissions[$employee['employee_id']][strtolower(str_replace(' ', '_', $page))] == 0 ? 'checked' : ''; ?>> ไม่มีสิทธิ์
                                                            </td>
                                                            <!-- <td>
                                                                <a href="/tsm_medical/inserupdate/delete_employee.php?employee_id=<?php echo $employee['employee_id']; ?>" onclick="return confirm('คุณต้องการลบข้อมูลนี้หรือไม่?');">ลบ</a>
                                                            </td> -->
                                                        </tr>
                                                <?php }
                                                } ?>
                                            </tbody>
                                        </table>
                                        <button type="submit" class="btn btn-primary">บันทึก</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://adminlte.io/themes/v3/plugins/jquery/jquery.min.js"></script>
        <script src="https://adminlte.io/themes/v3/dist/js/adminlte.js?v=3.2.0"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.4/xlsx.full.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
                $('#questionTable').DataTable({
                    pageLength: 6
                });
            });
        </script>
        <script>
            var employeeSelect = document.getElementById('employee_id');
            var employeeNameInput = document.getElementById('employee_name');
            var departmentInput = document.getElementById('department');
            var levelInput = document.getElementById('level');
            var positionInput = document.getElementById('position');

            $(document).ready(function() {
                $('#employee_id').select2();

                $('#employee_id').on('change', function() {
                    var selectedOption = $(this).find('option:selected');
                    var employee_name = selectedOption.data('employee_name');
                    var department = selectedOption.data('department');
                    var level = selectedOption.data('level');
                    var position = selectedOption.data('position');

                    employeeNameInput.value = employee_name ? employee_name : '';
                    departmentInput.value = department ? department : '';
                    levelInput.value = level ? level : '';
                    positionInput.value = position ? position : '';

                });
            });
        </script>
</body>

</html>