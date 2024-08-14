<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Admin | Appointment Details</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
<?php
    require_once "../Backend/connection.php";
    if (!isset($_GET["id"])) {
        echo "Something went wrong!";
    } else {
        $id = $_GET["id"];
        $rs = Database::search(
            "SELECT `appointment`.*, `treatment`  FROM `appointment`
            INNER JOIN `treatment` ON `appointment`.`treatment_id` = `treatment`.`id`
            WHERE `appointment`.`id` = ?",
            "s",
            $id
        );
        if (!$rs) {
            echo "Invalid appointment ID";
        } else {
            $row = $rs->fetch_assoc();
    ?>
    <div class="container-fluid position-relative d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner"
            class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-secondary navbar-dark">
                <a href="dashboard.php" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary"><i class="fa fa-user me-2"></i>&nbsp;ADMIN</h3>
                </a>
                <!-- <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">Hasitha Tharaka</h6>
                        <span>Admin</span>
                    </div>
                </div> -->
                <div class="navbar-nav w-100">
                    <a href="dashboard.php" class="nav-item nav-link"><i
                            class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
                    <a href="appointments.php" class="nav-item nav-link"><i
                            class="fa fa-pen me-2"></i>Appointments</a>
                    <a href="history.php" class="nav-item nav-link"><i class="fa fa-history me-2"></i>History</a>
                    <a href="calendar.php" class="nav-item nav-link"><i class="fa fa-calendar me-2"></i>Calendar</a>
                    <a href="profile.php" class="nav-item nav-link"><i class="fa fa-user me-2"></i>Profile</a>
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->


        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-secondary navbar-dark sticky-top px-4 py-0">
                <a href="dashboard.php" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-user-edit"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <div class="navbar-nav align-items-center ms-auto">
                    <!-- <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-envelope me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Message</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-secondary border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                        <small>15 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                        <small>15 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                        <small>15 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item text-center">See all message</a>
                        </div>
                    </div> -->
                    <!-- <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-bell me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Notificatin</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-secondary border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">Profile updated</h6>
                                <small>15 minutes ago</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">New user added</h6>
                                <small>15 minutes ago</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">Password changed</h6>
                                <small>15 minutes ago</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item text-center">See all notifications</a>
                        </div>
                    </div> -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-lg-2" src="img/user.jpg" alt=""
                                style="width: 40px; height: 40px;">
                            <span class="d-none d-lg-inline-flex">Hasitha Tharaka</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-secondary border-0 rounded-0 rounded-bottom m-0">
                            <a href="profile.php" class="dropdown-item">My Profile</a>
                            <a href="#" class="dropdown-item" onclick="signout();">Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Navbar End -->


            <!-- Blank Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row vh-100 bg-secondary rounded  justify-content-center mx-0">


                    <!-- Typography Start -->
                    <div class="container-fluid pt-4 px-4">
                        <div class="row g-4">

                            <div class="col-12 col-xl-12">
                                <div class="bg-secondary rounded h-100 p-4">
                                    <h6 class="mb-5 fs-2">Appointment Details</h6>
                                    <dl class="row mb-0">
                                        <div class="col-12 col-lg-6">
                                            <div class="row">
                                                <dt class="col-sm-4 text-white">Date</dt>
                                                <dd class="col-sm-8"><?php echo $row['appt_date']; ?></dd>
                                            </div>

                                            <div class="row">
                                                <dt class="col-sm-4 text-white">Name</dt>
                                                <dd class="col-sm-8"><?php echo $row['fname'] . " " . $row["lname"]; ?></dd>
                                            </div>

                                            <div class="row">
                                                <dt class="col-sm-4 text-white">Email</dt>
                                                <dd class="col-sm-8"><?php echo $row['email']; ?></dd>
                                            </div>

                                            <dt class="col-sm-4 text-white">Address</dt>
                                            <dd class="col-sm-8 ms-4"><?php echo $row['line1']; ?></dd>
                                            <dd class="col-sm-8 ms-4"><?php echo $row['line2']; ?></dd>
                                            <dd class="col-sm-8 ms-4"><?php echo $row['city']; ?></dd>
                                            <dd class="col-sm-8 ms-4"><?php echo $row['pcode']; ?></dd>

                                        </div>

                                        <div class="col-12 col-lg-6">
                                            <div class="row">
                                                <dt class="col-sm-4 text-white">Treatment</dt>
                                                <dd class="col-sm-8"><?php echo $row['treatment']; ?></dd>
                                            </div>

                                            <div class="row">
                                                <dt class="col-sm-4 text-white">Message</dt>
                                                <dd class="col-sm-8"><?php echo $row['msg']; ?></dd>
                                            </div>

                                        </div>
                                        <hr/>
                                        <div class="col-12 d-flex flex-row align-items-center justify-content-center g-2" id="bottom">
                                            <?php
                                            if ($row["status_id"] == 1) {
                                            ?>
                                                <button class="col-3 btn btn-danger mx-1" onclick="decline(<?php echo $row['id']; ?>);">Decline</button>
                                                <button class="col-3 btn btn-success mx-1" onclick="event.preventDefault();promptTimeslot(<?php echo $row['id']; ?>);">Accept</button>
                                            <?php
                                            } else if ($row["status_id"] == 2) {
                                            ?>
                                                <span class="text-success font-bold italic">Accepted</span>
                                            <?php
                                            } else if ($row["status_id"] == 3) {
                                            ?>
                                                <span class="text-danger font-bold italic">Declined</span>
                                            <?php
                                            }
                                            ?>
                                        </div>

                                    </dl>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- Typography End -->


                </div>
            </div>
            <!-- Blank End -->


            <!-- Footer Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-secondary rounded-top p-4">
                    <div class="row">
                        <div class="col-12 text-center text-sm-start">
                            &copy; <a href="https://evotechsoftwaresolutions.com">Copyright 2024 @ Evo Tech Software
                                Solutions</a>, All Right Reserved.
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer End -->
        </div>
        <!-- Content End -->


        <!-- Back to Top -->
        <!-- <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a> -->
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <script src="forms.js"></script>
    <?php
        }
    }
    ?>
</body>

</html>