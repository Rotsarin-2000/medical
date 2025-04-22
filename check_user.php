<?php
// session_start();
// include './db/conn.php';
// include './db/api.php';

// if (!isset($_SESSION['employee_id'])) {
//     die("Employee ID not found. Please log in.");
// }

// $employee_id = $_SESSION['employee_id'];

// $sql = "SELECT * FROM medical_creates 
//         WHERE status = 0 
//         AND JSON_UNQUOTE(JSON_EXTRACT(medical, '$.employee_id')) = ? 
//         ORDER BY id DESC";

// $stmt = $conn->prepare($sql);
// $stmt->bind_param("s", $employee_id);
// $stmt->execute();

// $result = $stmt->get_result();

session_start();
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
    $api_url = "https://center.tsmolymer.co.th/center";

    // $api_url = "http://127.0.0.1:4000/center";

    $data = [];
    $data["send"] = json_encode([
        "path" => "share",
        "type" => $response,
        "session_employee" => $_COOKIE["session_employee"]
    ], JSON_UNESCAPED_UNICODE);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);

    if ($response === false) {
        die('Error occurred while fetching the data: ' . curl_error($ch));
    }

    curl_close($ch);

    return json_decode($response, true);
}

function response_user()
{
    if (!isset($_COOKIE["session_employee"])) {
        die('Error: Session cookie not set.');
    }

    $api_url = "https://center.tsmolymer.co.th/center";

    // $api_url = "http://127.0.0.1:4000/center";

    $data = [];
    $data["send"] = json_encode([
        "path" => "basic",
        "type" => 'user_name_chk',
        "session_employee" => $_COOKIE["session_employee"]
    ], JSON_UNESCAPED_UNICODE);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);

    // print_r($data);
    if ($response === false) {
        die('Error occurred while fetching the data: ' . curl_error($ch));
    }

    curl_close($ch);

    $response = json_decode($response, true);
    return $response;
}

$response_chk = response_user();

if (!$response_chk || !isset($response_chk['department_name']) || !isset($response_chk['name'])) {
    die("Error: Unauthorized access.");
}

$employee_name = $response_chk['department_name'] . "_" . $response_chk['name'];
$employee_id = $response_chk['employee_id'];

$pages = ['medical', 'check', 'check_user', 'report', 'check_manager'];

$user_permissions = [];
foreach ($pages as $page) {
    $sql = "SELECT permission FROM page_access WHERE employee_id = ? AND page = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $employee_id, $page);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($permission);
        $stmt->fetch();
        $user_permissions[$page] = $permission;
    } else {
        $user_permissions[$page] = 0;
    }

    $stmt->close();
}

// if (!isset($employee_id)) {
//     die("Department not found. Please log in.");
// }
// // $employee_id = $_SESSION['employee_id'];

// $sql_form = "SELECT * FROM medical_creates 
//              WHERE status = 0 
//              AND JSON_UNQUOTE(JSON_EXTRACT(medical, '$.employee_id')) = ? 
//              ORDER BY id DESC LIMIT 1";

// $stmt_form = $conn->prepare($sql_form);
// $stmt_form->bind_param("s", $employee_id);
// $stmt_form->execute();

// $result_form = $stmt_form->get_result();
// if ($result_form && $result_form->num_rows > 0) {
//     $row = $result_form->fetch_assoc();
//     $medical_data = json_decode($row['medical'], true);
// }

// ตรวจสอบว่า $employee_id ถูกกำหนดหรือไม่
if (!isset($employee_id)) {
    die("Department not found. Please log in.");
}

// ฟังก์ชันสำหรับดึงข้อมูลทางการแพทย์จากตารางที่ระบุ
function fetchMedicalData($conn, $tableName, $employee_id, $year)
{
    // สร้างคำสั่ง SQL แบบไดนามิก
    $sql = "SELECT * FROM $tableName 
            WHERE status = 0 
            AND JSON_UNQUOTE(JSON_EXTRACT(medical, '$.employee_id')) = ?";

    // เพิ่มเงื่อนไขกรณีเลือกปี
    if ($year && $year !== 'All') {
        $sql .= " AND JSON_UNQUOTE(JSON_EXTRACT(medical, '$.year')) = ?";
    }

    $sql .= " ORDER BY id DESC LIMIT 1";

    // เตรียมคำสั่ง SQL
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("เตรียมคำสั่ง SQL ล้มเหลว: " . $conn->error);
    }

    // ผูกพารามิเตอร์
    if ($year && $year !== 'All') {
        $stmt->bind_param("ss", $employee_id, $year);
    } else {
        $stmt->bind_param("s", $employee_id);
    }

    // รันคำสั่ง
    $stmt->execute();

    // ดึงผลลัพธ์
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        return json_decode($result->fetch_assoc()['medical'], true);
    }

    return null; // กรณีไม่มีข้อมูล
}

// ดึงปีจาก GET
$year = isset($_GET['year']) ? $_GET['year'] : '';

// ดึงข้อมูลจากตาราง medical_creates
$medical_data = fetchMedicalData($conn, 'medical_creates', $employee_id, $year);

// ดึงข้อมูลจากตาราง m_creates
$medical_create = fetchMedicalData($conn, 'm_creates', $employee_id, $year);

// กรณีไม่มีข้อมูล
// if (!$medical_data && !$medical_create) {
//     echo "No records found.";
// }

// $year = isset($_GET['year']) ? mysqli_real_escape_string($conn, $_GET['year']) : '';
// // $employee_id = isset($_GET['employee_id']) ? mysqli_real_escape_string($conn, $_GET['employee_id']) : '';

// // เริ่มต้น SQL query
// $sql_form = "SELECT * FROM medical_creates 
//              WHERE status = 0 
//              AND JSON_UNQUOTE(JSON_EXTRACT(medical, '$.employee_id')) = ?";

// // เพิ่มเงื่อนไขกรองปีถ้ามีค่า
// if ($year && $year !== 'All') {
//     $sql_form .= " AND JSON_UNQUOTE(JSON_EXTRACT(medical, '$.year')) = ?";
// }

// // เพิ่มคำสั่ง ORDER BY และ LIMIT
// $sql_form .= " ORDER BY id DESC LIMIT 1";

// // เตรียม statement
// $stmt_form = $conn->prepare($sql_form);

// // ตรวจสอบจำนวนตัวแปรที่จะ bind
// if ($year && $year !== 'All') {
//     $stmt_form->bind_param("ss", $employee_id, $year);
// } else {
//     $stmt_form->bind_param("s", $employee_id);
// }

// // Execute the query
// $stmt_form->execute();

// // ดึงผลลัพธ์
// $result_form = $stmt_form->get_result();

// if ($result_form && $result_form->num_rows > 0) {
//     $row = $result_form->fetch_assoc();
//     $medical_data = json_decode($row['medical'], true);

//     // แสดงข้อมูล medical_data
//     // print_r($medical_data);
// } 

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../tsm_medical/css/LOGOTSMOLYMER01.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/fontawesome-free/css/all.min.css" />
    <link rel="stylesheet" href="https://adminlte.io/themes/v3/dist/css/adminlte.min.css?v=3.2.0" />
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../tsm_medical/css/styles.css">
    <link rel="shortcut icon" href="#">
    <title> User list</title>
</head>

<body class="sidebar-mini sidebar-closed sidebar-collapse">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light text-center" style="height: 90px; background: linear-gradient(270deg, #00CCCC, #0066CC); color: #fff;">
            <ul class="navbar-nav w-100 d-flex justify-content-between align-items-center">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button" style="color: #fff;"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item mx-auto d-flex align-items-center">
                    <span class="text bold-white" style="font-weight: bold; font-size: 28pt;">TSM MEDICAL</span>
                </li>
            </ul>

            <div class="home-section">
                <div class="home-content">
                    <div class="profile">
                        <img src="https://fs.tsmolymer.co.th/default/img/748669.png" alt="profile_picture" width="50" height="50" style="border-radius: 50px; margin-bottom: -10px;">
                        <!-- <span class="text-name" style=" font-weight: bold; font-size: 16px; color: white;"><?php echo $response_chk["department_name"] . "_" . $response_chk["name"]; ?></span> -->
                        <div class="dropdown">
                            <button class="btn btn-outline dropdown-toggle no-outline"
                                type="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                                style="font-weight: bold; font-size: 16px; color: white; margin-bottom: -15px;">
                                <?php echo $response_chk["department_name"] . "_" . $response_chk["name"]; ?>
                            </button>

                            <ul class="dropdown-menu">
                                <li><a href="https://ts.tsmolymer.co.th/tsm/login.php?page_go=aHR0cHM6Ly90cy50c21vbHltZXIuY28udGgvdHNtL2luZGV4LnBocA==" class="btn btn-secondary dropdown-item" href="#"><i class='bx bx-log-in'></i> Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <header style="display: flex; justify-content: center; align-items: center; padding: 10px;">
                <li class="search-box" style="list-style: none; display: flex; justify-content: center;">
                    <span class="image">
                        <img src="../tsm_medical/css/logo.jpg" width="50" height="50" style="border-radius: 10px; margin-right: 10px;">
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
                                <?php if ($user_permissions['medical'] == 1): ?>
                                    <li class="search-box <?php if ($current_page == 'medical.php') echo 'active'; ?>">
                                        <a href="/tsm_medical/medical.php" class="d-flex justify-content-start align-items-center nav-link" style="color: #000;">
                                            <div class="d-flex align-items-center">
                                                <i class='bx bx-plus-medical icon'></i>
                                                <span class="ms-auto">Medical</span>
                                            </div>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($user_permissions['report'] == 1): ?>
                                    <li class="search-box <?php if ($current_page == 'report.php') echo 'active'; ?>">
                                        <a href="/tsm_medical/report.php" class="d-flex justify-content-start align-items-center nav-link" style="color: #000;">
                                            <div class="d-flex align-items-center">
                                                <i class='bx bxs-report icon'></i>
                                                <span class="ms-auto">Report</span>
                                            </div>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($user_permissions['check_manager'] == 1): ?>
                                    <li class="search-box <?php if ($current_page == 'check_manager.php') echo 'active'; ?>">
                                        <a href="/tsm_medical/check_manager.php" class="d-flex justify-content-start align-items-center nav-link" style="color: #000;">
                                            <div class="d-flex align-items-center">
                                                <i class='bx bxs-user-detail icon'></i>
                                                <span class="ms-auto">Department</span>
                                            </div>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <li class="search-box <?php if ($current_page == 'check_user.php') echo 'active'; ?>">
                                    <a href="/tsm_medical/check_user.php" class="d-flex justify-content-start align-items-center nav-link" style="color: #000;">
                                        <div class="d-flex align-items-center">
                                            <i class='bx bx-user-circle icon'></i>
                                            <span class="ms-auto">Myself</span>
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
                            <!-- <i class="fas fa-list-ul" style="font-size: 22px;"></i> -->
                            <i class='bx bxs-user-circle' style="font-size: 32px;"> </i> Personal information
                            <div class="card-tools">
                                <span class="text-center">
                                    <!-- <form class="row d-flex align-items-center" method="GET" action="">
                                        <div class="col-md-12">
                                            <label for="year" class="form-label"></label>
                                            <select class="form-select form-select-lg js-example-basic-multiple-limit" name="year" id="year">
                                                <?php
                                                $currentYear = date('Y');
                                                $startYear = $currentYear - 10;
                                                $endYear = $currentYear + 10;

                                                $selectedYear = isset($_GET['year']) ? $_GET['year'] : $currentYear;

                                                // for ($year = $startYear; $year <= $endYear; $year++) {
                                                //     if ($year == $selectedYear) {
                                                //         echo "<option value=\"$year\" selected>$year &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>";
                                                //     } else {
                                                //         echo "<option value=\"$year\">$year</option>";
                                                //     }
                                                // }
                                                ?>
                                            </select>
                                        </div>
                                    </form> -->

                                    <form class="row d-flex align-items-center" method="GET" action="" id="filterForm">
                                        <div class="col-md-12">
                                            <select class="form-select form-select-lg js-example-basic-multiple-limit" name="year" id="year" onchange="document.getElementById('filterForm').submit()">
                                                <?php
                                                $currentYear = date('Y');
                                                $startYear = $currentYear - 10;
                                                $endYear = $currentYear + 10;

                                                $selectedYear = isset($_GET['year']) ? $_GET['year'] : $currentYear;

                                                for ($year = $startYear; $year <= $endYear; $year++) {
                                                    if ($year == $selectedYear) {
                                                        echo "<option value=\"$year\" selected>$year &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>";
                                                    } else {
                                                        echo "<option value=\"$year\">$year</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </form>
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <form class="row g-3">
                                <div class="col-md-3">
                                    <label for="employee_id" class="form-label">Employee Id</label>
                                    <input type="text" id="employee_id" class="form-control form-control-lg" name="employee_id"
                                        value="<?php if (!empty($medical_data['employee_id'])) {
                                                    echo $medical_data['employee_id'];
                                                } elseif (!empty($medical_create['employee_id'])) {
                                                    echo $medical_create['employee_id'];
                                                } else {
                                                    echo $response_chk["employee_id"];
                                                } ?>" style="pointer-events: none;">
                                </div>

                                <div class="col-md-3">
                                    <label for="employee_name" class="form-label">Employee Name</label>
                                    <input type="text" id="employee_name" class="form-control form-control-lg" name="employee_name"
                                        value="<?php if (!empty($medical_data['employee_name'])) {
                                                    echo $medical_data['employee_name'];
                                                } elseif (!empty($medical_create['employee_name'])) {
                                                    echo $medical_create['employee_name'];
                                                } else {
                                                    echo $response_chk["name"] . " " . $response_chk["surname"];
                                                } ?>" style="pointer-events: none;">
                                </div>

                                <div class="col-md-3">
                                    <label for="department" class="form-label">Department</label>
                                    <input type="text" id="department" class="form-control form-control-lg" name="department"
                                        value="<?php if (!empty($medical_data['department'])) {
                                                    echo $medical_data['department'];
                                                } elseif (!empty($medical_create['department'])) {
                                                    echo $medical_create['department'];
                                                } else {
                                                    echo $response_chk["department_name"];
                                                } ?>" style="pointer-events: none;">
                                </div>

                                <div class="col-md-3">
                                    <label for="birthday" class="form-label">Birthday</label>
                                    <input type="text" id="birthday" class="form-control form-control-lg" name="birthday"
                                        value="<?php if (!empty($medical_data['birthday'])) {
                                                    echo $medical_data['birthday'];
                                                } elseif (!empty($medical_create['birthday'])) {
                                                    echo $medical_create['birthday'];
                                                } else {
                                                    echo "1989-06-13";
                                                } ?>" style="pointer-events: none;">
                                </div>

                                <div class="col-md-4">
                                    <label for="start_work" class="form-label">Start Work</label>
                                    <input type="text" id="start_work" class="form-control form-control-lg" name="start_work"
                                        value="<?php
                                                if (!empty($medical_data['start_work'])) {
                                                    echo date('d-m-Y', strtotime($medical_data['start_work']));
                                                } elseif (!empty($medical_create['start_work'])) {
                                                    echo date('d-m-Y', strtotime($medical_create['start_work']));
                                                } else {
                                                    echo "22-07-2019";
                                                }
                                                ?>" style="pointer-events: none;">
                                </div>

                                <div class="col-md-4">
                                    <label for="duo_work" class="form-label">Duo Work</label>
                                    <input type="text" id="duo_work" class="form-control form-control-lg" name="duo_work"
                                        value="<?php
                                                if (!empty($medical_data['duo_work'])) {
                                                    echo date('d-m-Y', strtotime($medical_data['duo_work']));
                                                } elseif (!empty($medical_create['duo_work'])) {
                                                    echo date('d-m-Y', strtotime($medical_create['duo_work']));
                                                } else {
                                                    echo "No data Duo Work";
                                                }
                                                ?>" style="pointer-events: none;">
                                </div>

                                <div class="col-md-4">
                                    <label for="workage_year" class="form-label">Age Year</label>
                                    <input type="text" id="workage_year" class="form-control form-control-lg" name="workage_year"
                                        value="<?php if (!empty($medical_data['workage_year'])) {
                                                    echo $medical_data['workage_year'];
                                                } elseif (!empty($medical_create['workage_year'])) {
                                                    echo $medical_create['workage_year'];
                                                } else {
                                                    echo "No data Age Year";
                                                } ?>" style="pointer-events: none;">
                                </div>

                                <!-- <div class="col-md-12">
                                    <br />
                                    <h5 for="age_year" class="text-primary">Medical expenses reimbursement and balance</h5>
                                </div> -->
                                <div class="col-md-4 p-2">
                                    <label for="age_year" class="form-label" style="color: #007bff;">Budget in year</label>
                                    <input type="text" id="age_year" class="form-control form-control-lg custom-border" name="age_year"
                                        value="<?php
                                                if (!empty($medical_data['amount'])) {
                                                    echo number_format($medical_data['amount'], 2);
                                                } elseif (!empty($medical_create['amount'])) {
                                                    echo number_format($medical_create['amount'], 2);
                                                } else {
                                                    echo "No data Amount";
                                                }
                                                ?>
                                                " style="margin-right: 5px; font-weight: bold; pointer-events: none;">
                                </div>

                                <div class="col-md-4 p-2">
                                    <label for="age_year" class="form-label" style="color: #FF0000;">Total expenses</label>
                                    <input type="text" id="age_year" class="form-control form-control-lg custom-spent" name="age_year"
                                        value="<?php
                                                if (!empty($medical_data['value'])) {
                                                    echo number_format($medical_data['value'], 2);
                                                } elseif (!empty($medical_create['value'])) {
                                                    echo number_format($medical_create['value'], 2);
                                                } else {
                                                    echo "No data Value";
                                                }
                                                ?>" style="margin-right: 5px; font-weight: bold; pointer-events: none;">
                                </div>
                                <div class="col-md-4 p-2">
                                    <label for="balance" class="form-label" style="color: #009900;">Balance</label>
                                    <input type="text" id="balance" class="form-control form-control-lg custom-balance" name="balance"
                                        value="<?php
                                                if (!empty($medical_data['balance'])) {
                                                    echo number_format($medical_data['balance'], 2);
                                                } elseif (!empty($medical_create['balance'])) {
                                                    echo number_format($medical_create['balance'], 2);
                                                } else {
                                                    echo "No data Balance";
                                                }
                                                ?>" style="margin-right: 5px; font-weight: bold; pointer-events: none;">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 p-2">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-list-ul" style="font-size: 22px;"></i>
                            <div class="card-tools">
                                <!-- <button id="downloadExcel" class="btn btn me-1 excel"><i class='bx bxs-file-export'></i></button> -->
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table table-responsive-md">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th style=" background-color: #006633; color: #fff;">No.
                                                <button onclick="toggleFilter('filterNo')" class="btn btn btn-sm"><i class='bx bx-filter-alt' style="color: #006633;"></i></button>
                                            </th>
                                            <th style=" background-color: #006633; color: #fff;">Date
                                                <button onclick="toggleFilter('filterDate')" class="btn btn btn-sm"><i class='bx bx-filter-alt' style="color: #fff;"></i></button>
                                                <input type="text" class="form-control" id="filterDate" onkeyup="filterTable()" style="display:none;" />
                                            </th>
                                            <th style=" background-color: #006633; color: #fff;">Employee name
                                                <button onclick="toggleFilter('filterEmployeeName')" class="btn btn btn-sm"><i class='bx bx-filter-alt' style="color: #fff;"></i></button>
                                                <input type="text" class="form-control" id="filterEmployeeName" onkeyup="filterTable()" style="display:none;" />
                                            </th>
                                            <th style=" background-color: #006633; color: #fff;">Employee id
                                                <button onclick="toggleFilter('filterEmployeeId')" class="btn btn btn-sm"><i class='bx bx-filter-alt' style="color: #fff;"></i></button>
                                                <input type="text" class="form-control" id="filterEmployeeId" onkeyup="filterTable()" style="display:none;" />
                                            </th>
                                            <th style=" background-color: #006633; color: #fff;">Department
                                                <button onclick="toggleFilter('filterDepartment')" class="btn btn btn-sm"><i class='bx bx-filter-alt' style="color: #fff;"></i></button>
                                                <input type="text" class="form-control" id="filterDepartment" onkeyup="filterTable()" style="display:none;" />
                                            </th>
                                            <!-- <th style=" background-color: #006633; color: #fff;">Age Work
                                                <button onclick="toggleFilter('filterAgeWork')" class="btn btn btn-sm"><i class='bx bx-filter-alt' style="color: #fff;"></i></button>
                                                <input type="text" class="form-control" id="filterAgeWork" onkeyup="filterTable()" style="display:none;" />
                                            </th> -->
                                            <th style=" background-color: #006633; color: #fff;">Detail
                                                <button onclick="toggleFilter('filterRemark')" class="btn btn btn-sm"><i class='bx bx-filter-alt' style="color: #fff;"></i></button>
                                                <input type="text" class="form-control" id="filterRemark" onkeyup="filterTable()" style="display:none;" />
                                            </th>
                                            <th style=" background-color: #006633; color: #fff;">Budget in year
                                                <button onclick="toggleFilter('filterTotalAmount')" class="btn btn btn-sm"><i class='bx bx-filter-alt' style="color: #fff;"></i></button>
                                                <input type="text" class="form-control" id="filterTotalAmount" onkeyup="filterTable()" style="display:none;" />
                                            </th>
                                            <th style=" background-color: #006633; color: #fff;">Spent
                                                <button onclick="toggleFilter('filterTotalSpent')" class="btn btn btn-sm"><i class='bx bx-filter-alt' style="color: #fff;"></i></button>
                                                <input type="text" class="form-control" id="filterTotalSpent" onkeyup="filterTable()" style="display:none;" />
                                            </th>
                                            <!-- <th scope="col">Cost
                                                <button onclick="toggleFilter('filterCost')" class="btn btn btn-sm"><i class='bx bx-filter-alt'></i></button>
                                                <input type="text" class="form-control" id="filterCost" onkeyup="filterTable()" style="display:none;" />
                                            </th>
                                            <th scope="col">Balance
                                                <button onclick="toggleFilter('filterBalance')" class="btn btn btn-sm"><i class='bx bx-filter-alt'></i></button>
                                                <input type="text" class="form-control" id="filterBalance" onkeyup="filterTable()" style="display:none;" />
                                            </th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $id = 1;
                                        $totalPrice = 0;
                                        $sql_table = "SELECT * FROM medical_creates 
                              WHERE status = 0 
                              AND JSON_UNQUOTE(JSON_EXTRACT(medical, '$.employee_id')) = ? 
                              ORDER BY id DESC";
                                        $stmt_table = $conn->prepare($sql_table);
                                        $stmt_table->bind_param("s", $employee_id);
                                        $stmt_table->execute();

                                        $result_table = $stmt_table->get_result();

                                        if ($result_table && $result_table->num_rows > 0) :
                                            while ($row = $result_table->fetch_assoc()) :
                                                $medical_data = json_decode($row['medical'], true);

                                                if (isset($medical_data['price']) && is_numeric($medical_data['price'])) {
                                                    $totalPrice += $medical_data['price'];
                                                }
                                        ?>
                                                <tr>
                                                    <td scope="row"><?php echo $id++; ?></td>
                                                    <td><?php echo date('d-m-Y', strtotime($medical_data['date'])); ?></td>
                                                    <td><?php echo ($medical_data['employee_name']); ?></td>
                                                    <td><?php echo ($medical_data['employee_id']); ?></td>
                                                    <td><?php echo ($medical_data['department']); ?></td>
                                                    <!-- <td><?php echo ($medical_data['age_year']); ?></td> -->
                                                    <td><?php echo ($medical_data['remark_memo']); ?></td>
                                                    <td><?php echo number_format($medical_data['amount'], 2); ?></td>
                                                    <!-- <td><?php echo number_format($medical_data['value'], 2); ?></td> -->
                                                    <td><?php echo number_format($medical_data['price'], 2); ?></td>
                                                    <!-- <td><?php echo number_format($medical_data['balance'], 2); ?></td> -->
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="11" class="text-center">No records found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                    <!-- <tfoot>
                                        <tr>
                                            <td colspan="9" style="font-weight: bold; font-size: 16px;">รวมเป็นเงินทั้งสิ้น</td>
                                            <td style="font-weight: bold; font-size: 16px;"><?php echo number_format($totalPrice, 2); ?></td>
                                            <td></td>
                                        </tr>
                                    </tfoot> -->
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
        // new DataTable('#example', {
        //     pageLength: 50,
        //     initComplete: function() {
        //         this.api().columns().every(function() {
        //             let column = this;
        //             let input = column.header().querySelector('input');

        //             if (input) {
        //                 input.addEventListener('keyup', function() {
        //                     column.search(input.value).draw();
        //                 });
        //             }
        //         });
        //     }
        // });

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

        new DataTable('#example', {
            pageLength: 25,
            initComplete: function() {
                this.api().columns().every(function() {
                    let column = this;
                    let input = column.header().querySelector('input');

                    if (input) {
                        input.addEventListener('keyup', function() {
                            column.search(input.value).draw();
                        });
                    }
                });
            }
        });

        function toggleFilter(id) {
            var filterInput = document.getElementById(id);
            if (filterInput.style.display === "none" || filterInput.style.display === "") {
                filterInput.style.display = "block";
            } else {
                filterInput.style.display = "none";
            }
        }

        function filterTable() {
            const table = document.getElementById('example');
            const rows = table.getElementsByTagName('tr');

            var inputDate = document.getElementById("filterDate").value.toUpperCase();
            var inputEmployeeName = document.getElementById("filterEmployeeName").value.toUpperCase();
            var inputEmployeeId = document.getElementById("filterEmployeeId").value.toUpperCase();
            var inputDepartment = document.getElementById("filterDepartment").value.toUpperCase();
            var inputAgeWork = document.getElementById("filterAgeWork").value.toUpperCase();
            var inputRemark = document.getElementById("filterRemark").value.toUpperCase();
            var inputTotalAmount = document.getElementById('filterTotalAmount').value.toUpperCase();
            var inputTotalSpent = document.getElementById("filterTotalSpent").value.toUpperCase();
            var inputNo = document.getElementById("filterNo").value.toUpperCase();
            // var inputBalance = document.getElementById("filterBalance").value.toUpperCase();

            Array.from(rows).forEach(function(row) {
                var columns = row.getElementsByTagName("td");
                var match = true;

                if (columns[1] && columns[1].textContent.toUpperCase().indexOf(inputDate) === -1) match = false;
                if (columns[2] && columns[2].textContent.toUpperCase().indexOf(inputEmployeeName) === -1) match = false;
                if (columns[3] && columns[3].textContent.toUpperCase().indexOf(inputEmployeeId) === -1) match = false;
                if (columns[4] && columns[4].textContent.toUpperCase().indexOf(inputSerial) === -1) match = false;
                if (columns[5] && columns[5].textContent.toUpperCase().indexOf(inputAgeWork) === -1) match = false;
                if (columns[6] && columns[6].textContent.toUpperCase().indexOf(inputRemark) === -1) match = false;
                if (columns[7] && columns[7].textContent.toUpperCase().indexOf(inputTotalAmount) === -1) match = false;
                if (columns[8] && columns[8].textContent.toUpperCase().indexOf(inputTotalSpent) === -1) match = false;
                if (columns[9] && columns[9].textContent.toUpperCase().indexOf(inputNo) === -1) match = false;
                // if (columns[10] && columns[10].textContent.toUpperCase().indexOf(inputBalance) === -1) match = false;

                row.style.display = match ? "" : "none";
            });
        }

        $(".js-example-basic-multiple-limit").select2({
            maximumSelectionLength: 2
        });

        // document.getElementById('downloadExcel').addEventListener('click', function() {
        //     var table = document.getElementById('questionTable');
        //     var data = [];

        //     for (var i = 0; i < table.rows.length; i++) {
        //         var row = table.rows[i];
        //         var rowData = [];

        //         if (row.style.display === 'none') continue;

        //         for (var j = 0; j < row.cells.length; j++) {
        //             var cellText = row.cells[j].textContent.trim();
        //             rowData.push(cellText);
        //         }

        //         data.push(rowData);
        //     }

        //     var wb = XLSX.utils.book_new();
        //     var ws = XLSX.utils.aoa_to_sheet(data);

        //     ws['!cols'] = [{
        //             wch: 10
        //         },
        //         {
        //             wch: 20
        //         },
        //         {
        //             wch: 20
        //         },
        //         {
        //             wch: 20
        //         },
        //         {
        //             wch: 20
        //         },
        //         {
        //             wch: 10
        //         }
        //     ];

        //     XLSX.utils.book_append_sheet(wb, ws, "Sheet1");

        //     var today = new Date();
        //     var filename = today.getDate() + '-' + (today.getMonth() + 1) + '-' + today.getFullYear() + '.xlsx';

        //     XLSX.writeFile(wb, filename);
        // });
    </script>
</body>

</html>