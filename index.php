<?php
// {*เชื่อมฐานข้อมูล*}
session_start();
$servername = 'localhost';
$username = 'tsmmedicaldb';
$password = 'Wd719z$4c';
$dbname = 'tsm_medical_2023';

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// function response_user()
// {

//     if (!isset($_COOKIE["session_employee"])) {
//         die('Error: Session cookie not set.');
//     }

//     $url = 'https://api.tsmolymer.co.th/basic';
//     $data = [
//         'type' => 'user_name_chk',
//         'cookie' => $_COOKIE["session_employee"]
//     ];

//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $url);
//     curl_setopt($ch, CURLOPT_POST, 1);
//     curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
//     curl_setopt($ch, CURLOPT_TIMEOUT, 0);
//     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

//     curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//         'Content-Type: application/json',
//         'Content-Length: ' . strlen(json_encode($data))
//     ));

//     //     $api_url = "https://api.center.tsmolymer.co.th/center";

//     //     // $api_url = "http://127.0.0.1:4000/center";

//     //     $data = [];
//     //     $data["send"] = json_encode([
//     //         "path" => "basic",
//     //         "type" => 'user_name_chk',
//     //         "session_employee" => $_COOKIE["session_employee"]
//     //     ], JSON_UNESCAPED_UNICODE);

//     //     $ch = curl_init();
//     //     curl_setopt($ch, CURLOPT_URL, $api_url);
//     //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     //     curl_setopt($ch, CURLOPT_POST, true);
//     //     curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//     //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//     //     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

//     $response = curl_exec($ch);

//     if ($response === false) {
//         die('Error occurred while fetching the data: ' . curl_error($ch));
//     }

//     curl_close($ch);

//     $response = json_decode($response, true);
//     return $response;
// }

// $response_chk = response_user();

// {*ดึง Api user "type" => 'user_name_chk' มาใช้งาน *}
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
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

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
if ($response_chk === null) {
    die('Error: API response is null.');
}

// print_r($response_chk);

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
$conn->close();
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
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" /> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../tsm_medical/css/styles.css">
    <link rel="shortcut icon" href="#">
    <title>Home</title>
</head>

<body class="sidebar-mini sidebar-closed sidebar-collapse">
    <div class="wrapper">
        <!-- {* ส่วนของ nav bar *} -->
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
                                <!-- {* api path basic type user_name_chk แสดง department_name และ name *} -->
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

        <!-- {* nav sild bar*} -->
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
                            Menu
                            <div class="card-tools">
                                <a href="/tsm_medical/file.php" target="_blank"><i class="bx bx-download"></i> คู่มือการใช้งาน</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php if ($user_permissions['medical'] == 1): ?>
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                                        <div class="card box-medical">
                                            <a href="/tsm_medical/medical.php" style="text-decoration: none; color:aliceblue">
                                                <div class="card-body text-center">
                                                    <button type="button" class="btn btn-primary">
                                                        <i class="bx bx-plus-medical" style="font-size: 48px;"></i>
                                                    </button>
                                                    <h4 class="mt-2">MEDICAL</h4>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($user_permissions['check_manager'] == 1): ?>
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                                        <div class="card box-check">
                                            <a href="/tsm_medical/check_manager.php" style="text-decoration: none; color:aliceblue">
                                                <div class="card-body text-center">
                                                    <button type="button" class="btn btn-warning">
                                                        <i class="bx bxs-user-detail" style="font-size: 48px;"></i>
                                                    </button>
                                                    <h4 class="mt-2">DEPARTMENT</h4>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($user_permissions['report'] == 1): ?>
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                                        <div class="card box-report">
                                            <a href="/tsm_medical/report.php" style="text-decoration: none; color:aliceblue">
                                                <div class="card-body text-center">
                                                    <button type="button" class="btn btn-success">
                                                        <i class="bx bxs-report" style="font-size: 48px;"></i>
                                                    </button>
                                                    <h4 class="mt-2">REPORT</h4>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                                    <div class="card box-myself">
                                        <a href="/tsm_medical/check_user.php" style="text-decoration: none; color:aliceblue">
                                            <div class="card-body text-center">
                                                <button type="button" class="btn btn-danger">
                                                    <i class="bx bx-user-circle" style="font-size: 48px;"></i>
                                                </button>
                                                <h4 class="mt-2">MYSELF</h4>
                                            </div>
                                        </a>
                                    </div>
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
        </script>
</body>

</html>