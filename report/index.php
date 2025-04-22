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
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="shortcut icon" href="#">
    <style>
        .form-control {
            height: 45px;
            border-radius: 5px;
        }

        .form-select {
            height: 45px;
            border-radius: 5px;

        }

        /* .card-Filter {
            border-style: solid;
            border-color:  #99CCFF;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            background-color: #fff;
        } */
    </style>
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
                    <div class="home-section">
                        <div class="home-content">
                            <div class="profile">
                                <img src="https://fs.tsmolymer.co.th/default/img/748669.png" alt="profile_picture" width="50" height="50" style="border-radius: 50px;">
                                <!-- <span class="text-name" style=" font-weight: bold; font-size: 16px; color: white;"><?php echo $response_chk["department_name"] . "_" . $response_chk["name"]; ?></span> -->
                            </div>
                        </div>
                    </div>
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

                                <li class="search-box <?php if ($current_page == 'check/index.php') echo 'active'; ?>">
                                    <a href="/check/index.php" class="d-flex justify-content-start align-items-center nav-link" style="color: #000;">
                                        <div class="d-flex align-items-center">
                                            <i class='bx bxs-user-check icon'></i>
                                            <span class="ms-auto">Check</span>
                                        </div>
                                    </a>
                                </li>

                                <li class="search-box <?php if ($current_page == 'report/index.php') echo 'active'; ?>">
                                    <a href="/report/index.php" class="d-flex justify-content-start align-items-center nav-link" style="color: #000;">
                                        <div class="d-flex align-items-center">
                                            <i class='bx bxs-report icon'></i>
                                            <span class="ms-auto">Report</span>
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
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6"></div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item active"></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
            <div class="container-fluid">
                <div class="col-md-12 p-1">
                    <div class="card">
                        <div class="card-header">
                            <i class='bx bx-filter-alt'></i> Filter
                        </div>
                        <div class="card-body">
                            <div class="row p-2">
                                <div class="employee">
                                    <form class="row g-3">
                                        <div class="col-md-4">
                                            <label for="name" class="form-label">Year</label>
                                            <select id="inputState" class="form-select">
                                                <option selected>2024</option>
                                                <option>2021</option>
                                                <option>2022</option>
                                                <option>2023</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="department" class="form-label">Department</label>
                                            <select id="inputState" class="form-select">
                                                <option selected>All</option>
                                                <option>PM</option>
                                                <option>PN</option>
                                                <option>DC</option>
                                            </select>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 p-1">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-list-ul" style="font-size: 22px;"></i>
                            <div class="card-tools">
                                <a href="#" class="btn btn-outline-success">
                                    <i class='bx bxs-file-export'></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">No.</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Employee id</th>
                                        <th scope="col">Employee name</th>
                                        <th scope="col">Remark Memo</th>
                                        <th scope="col">Price</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>05-10-2024</td>
                                        <td>100000</td>
                                        <td>test one</td>
                                        <td>อุบัติเหตุ</td>
                                        <td>500</td>

                                    </tr>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>05-10-2024</td>
                                        <td>110000</td>
                                        <td>test two</td>
                                        <td>อุบัติเหตุ</td>
                                        <td>500</td>

                                    </tr>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>05-10-2024</td>
                                        <td>120000</td>
                                        <td>test three</td>
                                        <td>อุบัติเหตุ</td>
                                        <td>500</td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="sidebar-overlay"></div>
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