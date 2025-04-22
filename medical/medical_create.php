<?php
$servername = 'localhost';
$username = 'tsmmedicaldb';
$password = 'Wd719z$4c';
$dbname = 'tsm_medical_2023';

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $sql = "SELECT * FROM m_dates WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result_create = $stmt->get_result();

    $d_id = '';
    if ($result_create->num_rows > 0) {
        $row = $result_create->fetch_assoc();
        $d_id = htmlspecialchars($row['d_id']);

        $sql_create = "SELECT * FROM medical_creates WHERE d_id = ? AND status = 0 ORDER BY id DESC";
        $stmt_create = $conn->prepare($sql_create);
        $stmt_create->bind_param("s", $d_id);
        $stmt_create->execute();
        $result_create = $stmt_create->get_result();

        $stmt_create->close();

        $sql_date = "SELECT * FROM medical_creates WHERE d_id = ? AND status = 0 ORDER BY id DESC";
        $stmt_date = $conn->prepare($sql_date);
        $stmt_date->bind_param("s", $d_id);
        $stmt_date->execute();
        $result_date = $stmt_date->get_result();

        $stmt_date->close();
    } else {
        echo "No record found with the specified ID in m_dates.";
    }

    $stmt->close();
} else {
    echo "Invalid ID.";
}

$conn->close();
function response_chk($response)
{
    // $url = 'https://api.tsmolymer.co.th/share';
    // $data = [
    //     'type' => $response
    // ];

    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, $url);
    // curl_setopt($ch, CURLOPT_POST, 1);
    // curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    // curl_setopt($ch, CURLOPT_TIMEOUT, 0);
    // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    //     'Content-Type: application/json',
    //     'Content-Length: ' . strlen(json_encode($data))
    // ));

    // $response = curl_exec($ch);

    // if ($response === false) {
    //     die('Error occurred while fetching the data: ' . curl_error($ch));
    // }

    // curl_close($ch);

    // return json_decode($response, true);

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

//     $response = curl_exec($ch);

//     if ($response === false) {
//         die('Error occurred while fetching the data: ' . curl_error($ch));
//     }

//     curl_close($ch);

//     $response = json_decode($response, true);
//     return $response;
// }

// $response_chk = response_user();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../css/LOGOTSMOLYMER01.png">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://adminlte.io/themes/v3/dist/css/adminlte.min.css?v=3.2.0" />
    <link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/fontawesome-free/css/all.min.css" />
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" /> -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="shortcut icon" href="#">
    <title>Create Medical</title>
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
                        <!-- <br /> -->
                        <img src="https://fs.tsmolymer.co.th/default/img/748669.png" alt="profile_picture" width="50" height="50" style="border-radius: 50px;">
                        <span class="text-name" style=" font-weight: bold; font-size: 16px; color: white;"><?php echo $response_chk["department_name"] . "_" . $response_chk["name"]; ?></span>
                    </div>
                </div>
            </div>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <header style="display: flex; justify-content: center; align-items: center; padding: 10px;">
                <li class="search-box" style="list-style: none; display: flex; justify-content: center;">
                    <span class="image">
                        <img src="../css/logo.jpg" width="50" height="50" style="border-radius: 10px; margin-right: 10px;">
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

                                <li class="search-box <?php if ($current_page == 'report.php') echo 'active'; ?>">
                                    <a href="/tsm_medical/report.php" class="d-flex justify-content-start align-items-center nav-link" style="color: #000;">
                                        <div class="d-flex align-items-center">
                                            <i class='bx bxs-report icon'></i>
                                            <span class="ms-auto">Report</span>
                                        </div>
                                    </a>
                                </li>

                                <li class="search-box <?php if ($current_page == 'check_manager.php') echo 'active'; ?>">
                                    <a href="/tsm_medical/check_manager.php" class="d-flex justify-content-start align-items-center nav-link" style="color: #000;">
                                        <div class="d-flex align-items-center">
                                            <i class='bx bxs-user-detail icon'></i>
                                            <span class="ms-auto">Department</span>
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
                            ค่ารักษาพยาบาลรอบ วันที่ <?php echo date("d-m-Y", strtotime($row['date'])); ?>
                            <div class="card-tools">
                                <i class='bx bx-list-ol' style="font-size: 28px;"></i>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row p-2">
                                <div id="employee-form" class="employee" style="display: block;">
                                    <form class="row g-3" action="/tsm_medical/inserdata/medical_create.php" method="POST" id="myForm">
                                        <input type="hidden" class="form-control" name="d_id" value="<?php echo ($row['d_id']); ?>">
                                        <input type="hidden" class="form-control" name="date" value="<?php echo ($row['date']); ?>">
                                        <div class="col-md-2">
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
                                                                    data-employee_name="' . ($value['employee name']) . '"
                                                                    data-department="' . ($value['department']) . '" 
                                                                    data-startwork="' . substr($value['start work'], 0, -8) . '" 
                                                                    data-birthday="' . ($value['birthday']) . '">' .
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
                                        <div class="col-md-8"></div>
                                        <div class="col-md-2 position-relative" style="display: none;">
                                            <label for="inputPassword6" class="col-form-label">Year</label>
                                            <select class="form-control" name="year">
                                                <?php
                                                $currentYear = date('Y');
                                                $startYear = $currentYear - 10;
                                                $endYear = $currentYear + 10;

                                                for ($year = $startYear; $year <= $endYear; $year++) {

                                                    if ($year == $currentYear) {
                                                        echo "<option value=\"$year\" selected>$year (ปัจจุบัน)</option>";
                                                    } else {
                                                        echo "<option value=\"$year\">$year</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="employee_name" class="form-label">Employee Name</label>
                                            <input type="text" id="employee_name" class="form-control" name="employee_name" required readonly>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="department" class="form-label">Department</label>
                                            <input type="text" id="department" class="form-control" name="department" required readonly>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="birthday" class="form-label">Birthday</label>
                                            <input type="date" id="birthday" class="form-control" name="birthday" required readonly>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="start_work" class="form-label">Start Work</label>
                                            <input type="text" id="start_work" class="form-control" name="start_work" required readonly>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="duo_work" class="form-label">Duo Work</label>
                                            <input type="text" id="duo_work" class="form-control" name="duo_work" required readonly>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="age_year" class="form-label">Age Year</label>
                                            <input type="text" class="form-control" id="totalDurationInput" name="workage_year" required readonly />
                                            <input type="hidden" id="age_year" class="form-control" name="age_year" required readonly>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="amount" class="form-label">Budget in year</label>
                                            <div style="display: flex; align-items: center;">
                                                <input type="text" id="amount" class="form-control" name="amount" value="0.00" required readonly style="margin-right: 5px; font-weight: bold;">
                                                <span>Baht</span>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="value" class="form-label">Total expenses</label>
                                            <div style="display: flex; align-items: center;">
                                                <input type="text" id="value" class="form-control" name="value" value="0.00" required readonly style="margin-right: 5px; font-weight: bold;"> <!-- เบิกไปแล้ว -->
                                                <span>Baht</span>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="balance" class="form-label">Balance</label>
                                            <div style="display: flex; align-items: center;">
                                                <input type="text" id="balance" class="form-control" name="balance" value="" required readonly style="margin-right: 5px; font-weight: bold;"> <!-- ยอดคงเหลือที่ใช้ได้ -->
                                                <span>Baht</span>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <label for="remark_memo" class="form-label">Datail</label>
                                            <input type="text" class="form-control" name="remark_memo" required>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="owner" class="form-label">Owner</label>
                                            <select id="owner" class="form-control" name="owner">
                                                <option value="" selected>--- All ---</option>
                                                <option value="พนักงาน" selected>พนักงาน</option>
                                                <option value="บิดา">บิดา</option>
                                                <option value="มารดา">มารดา</option>
                                                <option value="สามี (ที่ไม่ได้ทำงาน)">สามี(ที่ไม่ได้ทำงาน)</option>
                                                <option value="ภรรยา (ที่ไม่ได้ทำงาน)">ภรรยา(ที่ไม่ได้ทำงาน)</option>
                                                <option value="บุตร (อายุไม่เกิน 15 ปี)">บุตร(อายุไม่เกิน 15 ปี)</option>
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="price" class="form-label">Spent</label>
                                            <div style="display: flex; align-items: center;">
                                                <input type="number" id="price" class="form-control" name="price" style="margin-right: 5px;" required oninput="updateBalance()">
                                                <span>Baht</span>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label class="form-label">
                                                <input type="checkbox" class="ngCheckbox" id="showFormng"> Additional Notes
                                            </label>
                                        </div>

                                        <div class="col-md-12">
                                            <div id="formContainerng" style="display: none;">
                                                <label for="note" class="form-label">Note</label>
                                                <textarea class="form-control" name="note" rows="3" value="-"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="d-flex justify-content-center">
                                                <a href="/tsm_medical/medical.php" class="btn btn me-1 back"><i class='bx bx-arrow-back'></i> Back</a>
                                                <button type="button" id="clearBtn" class="btn btn me-1 clear"><i class='bx bx-trash'></i> Clear</button>
                                                <button type="submit" class="btn btn me-1 add"><i class='bx bxs-user-check'></i> Add</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 p-2">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-list-ul" style="font-size: 22px;"></i>
                            <div class="card-tools">
                                <button class="btn btn me-1 excel" id="downloadExcel">
                                    <i class='bx bxs-file-export' style="font-size: 20px;"></i>
                                </button>
                                <a href="pdf.php?id=<?php echo $row['id']; ?>" class="btn btn me-1 pdf" target="_blank">
                                    <i class='bx bxs-file-pdf' style="font-size: 20px;"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table table-responsive-md">
                                <table id="example" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">No.</th>
                                            <!-- <th scope="col">No.</th> -->
                                            <th scope="col">Date</th>
                                            <th scope="col">Employee name</th>
                                            <th scope="col">Employee id</th>
                                            <th scope="col">Department</th>
                                            <th scope="col">Budget in year</th>
                                            <th scope="col">Detail</th>
                                            <th scope="col">Spent</th>
                                            <th scope="col">Total expenses</th>
                                            <th scope="col">Balance</th>
                                            <th scope="col">Note</th>
                                            <th class="text-center">Tools</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $id = 1;
                                        $totalPrice = 0;

                                        if ($result_create && $result_create->num_rows > 0) :
                                            while ($row = $result_create->fetch_assoc()) :
                                                $medical_data = json_decode($row['medical'], true);

                                                if (isset($medical_data['price']) && is_numeric($medical_data['price'])) {
                                                    $totalPrice += $medical_data['price'];
                                                }
                                        ?>
                                                <tr>
                                                    <td scope="row"><?php echo $id++; ?></td>
                                                    <!-- <td><?php echo ($row['d_id']); ?></td> -->
                                                    <td><?php echo date("d-m-Y", strtotime($medical_data['date'])); ?></td>
                                                    <td><?php echo ($medical_data['employee_name']); ?></td>
                                                    <td><?php echo ($medical_data['employee_id']); ?></td>
                                                    <td><?php echo ($medical_data['department']); ?></td>
                                                    <td><?php echo number_format($medical_data['amount'], 2); ?></td>
                                                    <td><?php echo ($medical_data['remark_memo']); ?></td>
                                                    <td><?php echo number_format($medical_data['value'], 2); ?></td>
                                                    <td><?php echo number_format($medical_data['price'], 2); ?></td>
                                                    <td><?php echo number_format($medical_data['balance'], 2); ?></td>
                                                    <td><?php echo ($medical_data['owner']); ?></td>
                                                    <td class="text-center">

                                                        <button type="button" class="btn btn-primary" style="background: linear-gradient(270deg, #DAA520, #CD853F); color: #fff;" data-bs-toggle="modal" data-bs-target="#editFormModal-<?php echo $row['id']; ?>">
                                                            <i class='bx bx-edit-alt'></i>
                                                        </button>

                                                        <form id="deleteForm<?php echo $row['id']; ?>" action='/tsm_medical/inserupdate/up_status_create.php' method='POST' style='display:inline;' onsubmit="return showModal('<?php echo $row['id']; ?>');">
                                                            <input type='hidden' name='id' value='<?php echo $row['id']; ?>'>
                                                            <input type='hidden' name='status' value='3'>
                                                            <button type="submit" class="btn btn" style="background: linear-gradient(270deg, #CC0000, #FF6633); color: #fff;"><i class='bx bx-trash'></i></button>
                                                        </form>

                                                        <div id="confirmationModal" class="modal delete">
                                                            <div class="modal-content delete">
                                                                <span class="modal-close delete" onclick="closeModal()">&times;</span>
                                                                <div class="modal-body delete">
                                                                    <i class="bx bx-trash delete"></i>
                                                                    <p>Are you sure you want to delete this item?</p>
                                                                    <button class="yes-button" onclick="confirmDelete()">Yes</button>
                                                                    <button class="no-button" onclick="closeModal()">No</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <!-- Modal -->
                                                <div class="modal fade" id="editFormModal-<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="editFormModalLabel-<?php echo $row['id']; ?>" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header" style="background: linear-gradient(270deg, #DAA520, #CD853F); color: #fff;">
                                                                <h5 class="modal-title" id="editFormModalLabel-<?php echo $row['id']; ?>"> <i class='bx bx-edit-alt'></i> Edit Item</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div id="edit-form-<?php echo $row['id']; ?>" class="edit-form">
                                                                    <div class="row p-3">
                                                                        <form id="form_<?php echo $row['id']; ?>" action="../inserupdate/up_edit_medical_create.php" method="POST">
                                                                            <input type="hidden" class="form-control" name="d_id" value="<?php echo $row['d_id']; ?>">
                                                                            <input type="hidden" name="status" value="<?php echo $row['status']; ?>">
                                                                            <input type="hidden" name="date" value="<?php echo $medical_data['date']; ?>">
                                                                            <input type="hidden" name="year" value="<?php echo $medical_data['year']; ?>">
                                                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                                                                            <div class="col-md-6">
                                                                                <label for="employee_id" class="form-label">Employee Id</label>
                                                                                <input type="text" id="employee_id" class="form-control" name="employee_id" value="<?php echo $medical_data['employee_id']; ?>" required readonly>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <label for="employee_name" class="form-label">Employee Name</label>
                                                                                <input type="text" id="employee_name" class="form-control" name="employee_name" value="<?php echo $medical_data['employee_name']; ?>" required readonly>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <label for="department" class="form-label">Department</label>
                                                                                <input type="text" id="department" class="form-control" name="department" value="<?php echo $medical_data['department']; ?>" required readonly>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <label for="birthday" class="form-label">Birthday</label>
                                                                                <input type="date" id="birthday" class="form-control" name="birthday" value="<?php echo $medical_data['birthday']; ?>" required readonly>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <label for="start_work" class="form-label">Start Work</label>
                                                                                <input type="text" id="start_work" class="form-control" name="start_work" value="<?php echo $medical_data['start_work']; ?>" required readonly>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <label for="duo_work" class="form-label">Duo Work</label>
                                                                                <input type="text" id="duo_work" class="form-control" name="duo_work" value="<?php echo $medical_data['duo_work']; ?>" required readonly>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <label for="age_year" class="form-label">Age Year</label>
                                                                                <input type="hidden" id="age_year" class="form-control" name="age_year" value="<?php echo $medical_data['age_year']; ?>" required readonly>
                                                                                <input type="text" id="workage_year" class="form-control" name="workage_year" value="<?php echo $medical_data['workage_year']; ?>" required readonly>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <label for="amount" class="form-label">Budget in year</label>
                                                                                <div style="display: flex; align-items: center;">
                                                                                    <input type="text" id="amount" class="form-control" name="amount" value="<?php echo $medical_data['amount']; ?>" style="margin-right: 5px;" required readonly>
                                                                                    <span>Baht</span>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <label for="value" class="form-label">Total spent</label>
                                                                                <div style="display: flex; align-items: center;">
                                                                                    <input type="text" class="form-control value-input" name="value" value="<?php echo $medical_data['value']; ?>" data-original-value="<?php echo $medical_data['value']; ?>" style="margin-right: 5px;" required>
                                                                                    <span>Baht</span>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <label for="balance" class="form-label">Balance</label>
                                                                                <div style="display: flex; align-items: center;">
                                                                                    <input type="text" class="form-control balance-input" name="balance" value="<?php echo $medical_data['balance']; ?>" data-original-balance="<?php echo $medical_data['balance']; ?>" style="margin-right: 5px;" required>
                                                                                    <span>Baht</span>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-8">
                                                                                <label for="remark_memo" class="form-label">Detail</label>
                                                                                <input type="text" id="remark_memo" class="form-control" name="remark_memo" value="<?php echo $medical_data['remark_memo']; ?>" required>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label for="price" class="form-label">Spent</label>
                                                                                <div style="display: flex; align-items: center;">
                                                                                    <input type="text" class="form-control price-input" name="price" value="<?php echo $medical_data['price']; ?>" style="margin-right: 5px;" required oninput="updateCalculations(event)">
                                                                                    <span>Baht</span>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-12">
                                                                                <label for="owner" class="form-label">Owner</label>
                                                                                <select id="owner" class="form-control" name="owner">
                                                                                    <option value="" selected>--- All ---</option>
                                                                                    <option value="พนักงาน" selected>พนักงาน</option>
                                                                                    <option name="owner" selected><?php echo $medical_data['owner']; ?></option>
                                                                                    <option value="บิดา">บิดา</option>
                                                                                    <option value="มารดา">มารดา</option>
                                                                                    <option value="สามี (ที่ไม่ได้ทำงาน)">สามี(ที่ไม่ได้ทำงาน)</option>
                                                                                    <option value="ภรรยา (ที่ไม่ได้ทำงาน)">ภรรยา(ที่ไม่ได้ทำงาน)</option>
                                                                                    <option value="บุตร (อายุไม่เกิน 15 ปี)">บุตร(อายุไม่เกิน 15 ปี)</option>
                                                                                </select>
                                                                            </div>

                                                                            <div class="col-md-12">
                                                                                <label for="note" class="form-label">Additional Notes</label>
                                                                                <textarea class="form-control" name="note" rows="3" value="-"><?php echo $medical_data['note']; ?></textarea>
                                                                            </div>

                                                                            <div class="col-12">
                                                                                <div class="d-flex justify-content-center">
                                                                                    <button type="button" class="btn btn me-1" style="background-color: #000; color: #fff; border-radius: 25px; padding: 9px 20px; margin: 10px;" data-bs-dismiss="modal">
                                                                                        <i class='bx bx-x-circle'></i> Close
                                                                                    </button>
                                                                                    <button type="submit" class="btn btn me-1 add"><i class='bx bx-check-circle'></i> Confirm</button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="11" class="text-center">No records found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td style="font-weight: bold; font-size: 16px;">รวมเป็นเงินทั้งสิ้น</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td style="font-weight: bold; font-size: 16px;"><?php echo number_format($totalPrice, 2); ?></td>
                                            <td></td>
                                            <td></td>
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
        <div id="sidebar-overlay"></div>
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

        document.getElementById('clearBtn').addEventListener('click', function() {
            document.getElementById('myForm').reset();
        });

        let formToSubmit = null;

        function showModal(formId) {
            document.getElementById("confirmationModal").style.display = "block";
            formToSubmit = document.getElementById("deleteForm" + formId);
            return false;
        }

        function closeModal() {
            document.getElementById("confirmationModal").style.display = "none";
            formToSubmit = null;
        }

        function confirmDelete() {
            if (formToSubmit) {
                formToSubmit.submit();
            }
            closeModal();
        }

        // new DataTable('#example', {
        //     pageLength: 25,
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
    </script>

    <script>
        var employeeSelect = document.getElementById('employee_id');
        var employeeNameInput = document.getElementById('employee_name');
        var departmentInput = document.getElementById('department');
        var startWorkInput = document.getElementById('start_work');
        var duoWorkInput = document.getElementById('duo_work');
        var birthdayInput = document.getElementById('birthday');
        var ageYearInput = document.getElementById('age_year');
        var amountInput = document.getElementById('amount');
        var balanceInput = document.getElementById('balance');

        $(document).ready(function() {
            $('#employee_id').select2();

            $('#employee_id').on('change', function() {

                var selectedOption = $(this).find('option:selected');
                var employee_name = selectedOption.data('employee_name');
                var department = selectedOption.data('department');
                var startWork = selectedOption.data('startwork');
                var birthday = selectedOption.data('birthday');

                employeeNameInput.value = employee_name ? employee_name : '';
                departmentInput.value = department ? department : '';
                startWorkInput.value = startWork ? startWork : '';
                birthdayInput.value = birthday ? birthday : '';

                $('#value').val('0.00');
                $('#balance').val('5000.00');

                if (startWork) {
                    var startDate = new Date(startWork);

                    if (startDate.getFullYear() > 2500) {
                        startDate.setFullYear(startDate.getFullYear() - 543);
                    }

                    startDate.setDate(startDate.getDate() + 119);
                    duoWorkInput.value = startDate.toISOString().split('T')[0];

                    // นับตามวันที่ที่ผ่านโปร
                    var currentDate = new Date();

                    var currentYear = new Date().getFullYear();
                    var endOfYear = new Date(currentYear, 11, 31);

                    let amount = 0;

                    if (currentDate < startDate) {
                        // ยังไม่ถึงวันผ่านโปร 
                        amount = 0;
                        ageYearInput.value = 'Not yet passed probation';
                    } else if (startDate.getFullYear() < currentYear) {

                        var yearsDifference = currentYear - startDate.getFullYear();
                        amount = 5000;

                        var monthsDifference = endOfYear.getMonth() - startDate.getMonth();
                        if (monthsDifference < 0) {
                            monthsDifference += 12;
                        }

                        ageYearInput.value = `${yearsDifference} Year${yearsDifference > 1 ? 's' : ''} ${monthsDifference} Month${monthsDifference > 1 ? 's' : ''}`;

                    } else if (startDate.getFullYear() === currentYear) {

                        var monthsDifference = endOfYear.getMonth() - startDate.getMonth();
                        if (monthsDifference < 0) {
                            monthsDifference += 12;
                        }

                        switch (monthsDifference) {
                            case 0:
                                amount = 0;
                                break;
                            case 1:
                                amount = 250;
                                break;
                            case 2:
                                amount = 500;
                                break;
                            case 3:
                                amount = 750;
                                break;
                            case 4:
                                amount = 1000;
                                break;
                            case 5:
                                amount = 1500;
                                break;
                            case 6:
                                amount = 2000;
                                break;
                            case 7:
                                amount = 2500;
                                break;
                            case 8:
                                amount = 3000;
                                break;
                            case 9:
                                amount = 3500;
                                break;
                            case 10:
                                amount = 4000;
                                break;
                            case 11:
                                amount = 4500;
                                break;
                            default:
                                amount = 5000;
                        }

                        ageYearInput.value = `${monthsDifference} Month${monthsDifference > 1 ? 's' : ''}`;

                    } else {

                        ageYearInput.value = '0';
                        amount = 0;
                    }
                    amountInput.value = amount.toFixed(2);

                    let expenses = 0;
                    let balance = amount - expenses;
                    balanceInput.value = balance.toFixed(2);

                    var currentDate = new Date();
                    var startDateWithAddedDays = new Date(startDate);
                    startDateWithAddedDays.setDate(startDateWithAddedDays.getDate() - 119);

                    var yearsDifference = currentDate.getFullYear() - startDateWithAddedDays.getFullYear();
                    var monthsDifference = currentDate.getMonth() - startDateWithAddedDays.getMonth();

                    if (monthsDifference < 0) {
                        yearsDifference--;
                        monthsDifference += 12;
                    }

                    if (yearsDifference <= 0) {

                        totalDurationInput.value = `${monthsDifference} Month${monthsDifference > 1 ? 's' : ''}`;
                    } else {

                        totalDurationInput.value = `${yearsDifference} Year${yearsDifference > 1 ? 's' : ''}, ${monthsDifference} Month${monthsDifference > 1 ? 's' : ''}`;
                    }


                } else {
                    duoWorkInput.value = '';
                    ageYearInput.value = '';
                    amountInput.value = '';
                    // balanceInput.value = '';
                    totalDurationInput.value = '';
                }

                console.log('Employee Name: ' + employee_name);
                console.log('Department: ' + department);
                console.log('Start Work: ' + startWork);
                console.log('Duo Work: ' + duoWorkInput.value);
                console.log('Age Year: ' + ageYearInput.value);
                console.log('Amount: ' + amountInput.value);
                console.log('Balance: ' + balanceInput.value);
                console.log('Birthday: ' + birthday);
            });
        });

        var valueInput = document.getElementById('value');
        var priceInput = document.getElementById('price');

        function calculateValue() {
            var currentValue = parseFloat(valueInput.value) || 0;
            var price = parseFloat(priceInput.value) || 0;

            var newValue = currentValue + price;

            valueInput.value = newValue.toFixed(2);
        }

        priceInput.addEventListener('change', calculateValue);

        var employeeSelect = $('#employee_id');
        var amountInput = document.getElementById('amount');
        var valueInput = document.getElementById('value');
        var balanceInput = document.getElementById('balance');
        var priceInput = document.getElementById('price');


        function calculateBalanceWithAmount() {
            var amount = parseFloat(amountInput.value) || 0;
            var price = parseFloat(priceInput.value) || 0;
            var balance = amount - price;
            balanceInput.value = balance.toFixed(2);
        }

        function calculateBalanceWithDatabase(balanceFromDB) {
            var price = parseFloat(priceInput.value) || 0;
            balanceInput.value = (balanceFromDB - price).toFixed(2);
        }

        employeeSelect.on('change', function() {
            var employee_id = employeeSelect.val();

            if (employee_id !== "" && employee_id !== "--- All ---") {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "/tsm_medical/medical/fetch_employee_data.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);

                        if (response.balance !== null) {
                            amountInput.value = response.amount;
                            valueInput.value = response.value;
                            calculateBalanceWithDatabase(parseFloat(response.balance));

                            priceInput.addEventListener('input', function() {
                                calculateBalanceWithDatabase(parseFloat(response.balance));
                            });
                        } else {
                            calculateBalanceWithAmount();
                            priceInput.addEventListener('input', calculateBalanceWithAmount);
                        }
                    }
                };

                xhr.send("employee_id=" + encodeURIComponent(employee_id));
            } else {
                amountInput.value = '';
                valueInput.value = '0';
                balanceInput.value = '';
            }
        });

        window.onload = calculateBalanceWithAmount;
    </script>

    <!-- ====================== function edit ====================== -->
    <script>
        const checkboxNG = document.getElementById('showFormng');
        const formContainerNG = document.getElementById('formContainerng');
        checkboxNG.addEventListener('change', function() {
            formContainerNG.style.display = checkboxNG.checked ? 'block' : 'none';
        });

        function toggleForms(id) {
            var form = document.getElementById('edit-form-' + id);

            if (form.style.display === "none" || form.style.display === "") {
                form.style.display = "block";
            } else {
                form.style.display = "none";
            }
        }

        function toggleEditForm(id) {
            var form = document.getElementById('edit-form-' + id);
            form.style.display = "none";
        }

        function updateCalculations(event) {
            const row = event.target.closest('.col-md-4');
            const valueInput = row.parentElement.querySelector('.value-input');
            const balanceInput = row.parentElement.querySelector('.balance-input');
            const priceInput = event.target;

            const originalBalance = parseFloat(balanceInput.dataset.originalBalance) || 0;
            const originalValue = parseFloat(valueInput.dataset.originalValue) || 0;

            let price = parseFloat(priceInput.value) || 0;

            const newBalance = originalBalance - price;
            const newValue = originalValue + price;

            balanceInput.value = newBalance.toFixed(2);
            valueInput.value = newValue.toFixed(2);
        }

        document.getElementById('downloadExcel').addEventListener('click', function() {
            var table = document.getElementById('example');
            var data = [];

            for (var i = 0; i < table.rows.length; i++) {
                var row = table.rows[i];
                var rowData = [];

                if (row.style.display === 'none') continue;

                for (var j = 0; j < row.cells.length; j++) {

                    if (j === 7 || j === 11 || j === 5 || j === row.cells.length - 1) {
                        continue;
                    }

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
                    wch: 15
                },
                {
                    wch: 25
                },
                {
                    wch: 10
                },
                {
                    wch: 10
                },
                {
                    wch: 40
                },
                {
                    wch: 10
                },
                {
                    wch: 10
                },
            ];

            XLSX.utils.book_append_sheet(wb, ws, "Sheet1");

            var today = new Date();
            var filename = today.getDate() + '-' + (today.getMonth() + 1) + '-' + today.getFullYear() + '.xlsx';

            XLSX.writeFile(wb, filename);
        });
    </script>

</body>

</html>