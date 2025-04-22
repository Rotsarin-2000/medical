<?php
include './db/conn.php';
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
if ($response_chk === null) {
    die('Error: API response is null.');
}

// $sql = "SELECT m_dates.id, m_dates.date, m_dates.d_id,  COUNT(CASE WHEN medical_creates.status = 0 THEN medical_creates.d_id END) AS item_count, 
//             SUM(JSON_EXTRACT(medical_creates.medical, '$.price')) AS total_price
//         FROM m_dates
//         LEFT JOIN medical_creates ON m_dates.id = medical_creates.d_id
//         WHERE m_dates.status = 0
//         GROUP BY m_dates.id
//         ORDER BY m_dates.id DESC";
// $result = mysqli_query($conn, $sql);
$selectedYear = isset($_GET['year']) ? $_GET['year'] : date('Y');

$sql = "SELECT m_dates.id, m_dates.date, m_dates.d_id,  
            COUNT(CASE WHEN medical_creates.status = 0 THEN medical_creates.d_id END) AS item_count, 
            SUM(JSON_EXTRACT(medical_creates.medical, '$.price')) AS total_price
        FROM m_dates
        LEFT JOIN medical_creates ON m_dates.id = medical_creates.d_id
        WHERE m_dates.status = 0 AND YEAR(m_dates.date) = ?
        GROUP BY m_dates.id
        ORDER BY m_dates.id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $selectedYear);
$stmt->execute();
$result = $stmt->get_result();

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
    <link rel="stylesheet" href="../tsm_medical/css/styles.css">
    <link rel="shortcut icon" href="#">
    <title>Create date</title>
</head>

<body class="sidebar-mini sidebar-closed sidebar-collapse">
    <div class="wrapper">
        <!-- {*ส่วน Navber *} -->
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

        <!-- {*ส่วน SildBar*} -->
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
                            <div class="card-tools">
                                <form class="row d-flex align-items-center" method="GET" action="" id="filterForm">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <select class="form-select form-select-lg js-example-basic-multiple-limit" name="year" id="year" onchange="document.getElementById('filterForm').submit()">
                                            <?php
                                            $currentYear = date('Y');
                                            $startYear = $currentYear - 10;
                                            $endYear = $currentYear + 10;

                                            $selectedYear = isset($_GET['year']) ? $_GET['year'] : $currentYear;

                                            for ($year = $startYear; $year <= $endYear; $year++) {
                                                echo "<option value=\"$year\" " . ($year == $selectedYear ? 'selected' : '') . "> &nbsp;&nbsp;&nbsp; $year &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                                <div class="card box-create">
                                    <div class="card-body text-center" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        <button type="button" class="btn btn-primary">
                                            <i class="bx bx-plus-medical" style="font-size: 46px;"></i>
                                        </button>
                                        <h3 class="mt-2">Create</h3>
                                    </div>
                                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="/tsm_medical/inserdata/medical.php" method="POST">
                                                    <div class="modal-header" style="color:white; background-color: green;">
                                                        <p class="modal-title fs-5" id="exampleModalLabel">
                                                            <i class='bx bx-notepad' style="font-size: 24px;"></i> Create Date
                                                        </p>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <!-- <select class="form-control" type="date" name="date">
                                                            <?php
                                                            $currentMonth = date('m');
                                                            $currentYear = date('Y');
                                                            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);

                                                            $dateToCheck = sprintf('%04d-%02d-05', $currentYear, $currentMonth);
                                                            $query = $conn->prepare("SELECT COUNT(*) FROM m_dates WHERE date = ?");
                                                            $query->bind_param("s", $dateToCheck);
                                                            $query->execute();
                                                            $query->bind_result($count);
                                                            $query->fetch();
                                                            $query->close();

                                                            $defaultDay = ($count > 0) ? 20 : 5;

                                                            for ($day = 1; $day <= $daysInMonth; $day++) {
                                                                $dateValue = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $day);
                                                                $dateDisplay = sprintf('%02d-%02d-%04d', $day, $currentMonth, $currentYear);

                                                                $selected = ($day == $defaultDay) ? 'selected' : '';

                                                                echo "<option value=\"$dateValue\" $selected>$dateDisplay</option>";
                                                            }
                                                            ?>
                                                        </select> -->
                                                        <div class="text-start">
                                                            <!-- <label for="date" class="form-label">date</label> -->
                                                            <input type="date" id="date" class="form-control" name="date" required>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer d-flex justify-content-center">
                                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                                            <i class='fa fa-window-close' style='color:white'></i> Close
                                                        </button>
                                                        <button type="submit" class="btn btn-success">
                                                            <i class='bx bxs-save'></i> Add
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-12 p-2">
                    <div class="card">
                        <div class="card-header">
                            <p>Items</p>
                        </div>
                        <div class="card-body">
                            <div class="table table-responsive-md">
                                <table id="questionTable" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">No.</th>
                                            <!-- <th scope="col" class="text-center">id.</th>
                                            <th scope="col" class="text-center">No.</th> -->
                                            <th scope="col" class="text-center">Date</th>
                                            <th scope="col" class="text-center">List Item</th>
                                            <th scope="col" class="text-center">Total price</th>
                                            <th class="text-center">Tools</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $id = 1;
                                        if ($result && $result->num_rows > 0) :
                                            while ($row = $result->fetch_assoc()) : ?>
                                                <tr>
                                                    <th scope="row" class="text-center"><?php echo $id++; ?></th>
                                                    <!-- <th scope="row" class="text-center"><?php echo $row['id'] ?></th>
                                                    <th scope="row" class="text-center"><?php echo $row['d_id'] ?></th> -->
                                                    <td class="text-center">ค่ารักษาพยาบาลรอบ วันที่ <?php echo date("d-m-Y", strtotime($row['date'])); ?></td>
                                                    <td class="text-center"><?php echo $row['item_count']; ?></td>
                                                    <td class="text-center"><?php echo number_format($row['total_price'], 2); ?></td>
                                                    <td class="text-center">
                                                        <a href="/tsm_medical/medical/medical_create.php?id=<?php echo $row['id']; ?>" class="btn btn" style=" background: linear-gradient(270deg, #00CCCC, #0066CC); color: #fff;"><i class='bx bx-plus-medical'></i></a>
                                                        <!-- <form action="/tsm_medical/inserupdate/up_status_medical.php" method="POST">
                                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                            <button type="submit" onclick="return confirm('Are you sure you want to delete this record?');">
                                                                Delete
                                                            </button>
                                                        </form> -->
                                                        <button type="button" class="btn btn" style="background: linear-gradient(270deg, #DAA520, #CD853F); color: #fff;" data-bs-toggle="modal"
                                                            data-bs-target="#modal_<?php echo ($row['id']); ?>"
                                                            data-id="<?php echo ($row['id']); ?>"
                                                            data-d_id="<?php echo ($row['d_id']); ?>"
                                                            data-date="<?php echo ($row['date']); ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </button>

                                                        <div class='modal fade' id='modal_<?php echo ($row['id']); ?>' tabindex='-1' aria-labelledby='exampleModalLabel_<?php echo $row["id"]; ?>' aria-hidden='true'>
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <form id="barcodeForm" action="/tsm_medical/inserupdate/up_edit_medical.php" method="POST">
                                                                        <input type="hidden" class="form-control form-control-lg" name="id" value="<?php echo ($row['id']); ?>">
                                                                        <div class="modal-header" style="background: linear-gradient(270deg, #DAA520, #CD853F); color: #fff;">
                                                                            <h5 class="modal-title fs-5 " id="exampleModalLabel"><i class='bx bxs-calendar-edit' style="font-size: 24px; color:#fff;"></i> Edit Date</h5>
                                                                            <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="mb-3">
                                                                                <input type="hidden" class="form-control form-control-lg" name="d_id" value="<?php echo ($row['d_id']); ?>" />
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <input type="text" class="form-control form-control-lg" name="date" value="<?php echo ($row['date']); ?>">
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer d-flex justify-content-center">
                                                                            <button type="button" class="btn btn btn-lg" style="background: linear-gradient(270deg, #CC0000, #FF6633); color: #fff;" data-bs-dismiss="modal"><i class='bx bx-x-circle'></i> Close</button>
                                                                            <button type="submit" class="btn btn btn-lg" style=" background: linear-gradient(270deg, #009900, #006600); color: #fff;"><i class='bx bx-check-circle'></i> Confirm</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <form id="deleteForm<?php echo $row['id']; ?>" action='/tsm_medical/inserupdate/up_status_medical.php' method='POST' style='display:inline;' onsubmit="return showModal('<?php echo $row['id']; ?>');">
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
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="5" class="text-center">No records found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
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
    <script>
        $(document).ready(function() {
            $('#questionTable').DataTable({
                pageLength: 50
            });
        });

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
    </script>
</body>

</html>