<?php
session_start();
include_once "../configs/config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
if (isset($_SESSION['role_id'])) {
    switch ($_SESSION['role_id']) {
        case 1:
            header("Location: ../super_admin/dashboard.php");
            break;
        case 2:
            header("Location: ../admin/dashboard.php");
            break;
        case 3:
            break;
        case 4:
            header("Location: ../cao/dashboard.php");
            break;
        case 5:
            header("Location: ../collecting-officer/dashboard.php");
            break;
        case 6:
            header("Location: ../provincial/dashboard.php");
            break;
        default:
            break;
    }
}

$systemInfo = [];
$sql = "SELECT `app_name`, `title`, `front_title`, `favicon`, `app_logo` FROM `system_settings`";
$stmt = $conn->prepare($sql);
$stmt->execute();
$systemInfo = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

$sql = "SELECT 
    a.`name_of_applicant`, 
    po.`provincial_office`,
    CASE 
        WHEN 
            a.`date_received_by_po_from_so_applicant` IS NULL OR
            a.`type_of_application` IS NULL OR
            a.`date_of_payment` IS NULL OR
            a.`or_number` IS NULL OR
            a.`date_transmitted_to_ro` IS NULL OR
            a.`date_received_by_ro` IS NULL OR
            a.`ro_screener` IS NULL OR
            a.`date_forwarded_to_the_office_of_oic` IS NULL OR
            a.`date_reviewed_by_oic_crasd` IS NULL OR
            a.`feedbacks` IS NULL OR
            a.`date_forwarded_to_ord` IS NULL OR
            a.`date_application_approved_by_rd` IS NULL OR
            a.`for_issuance_of_crasm` IS NULL OR
            a.`for_transmittal_of_crasm` IS NULL OR
            a.`date_crasm_generated` IS NULL OR
            a.`date_forwarded_back_to_the_office_of_oic_cao` IS NULL OR
            a.`date_reviewed_and_initialed_by_oic_crasd` IS NULL OR
            a.`date_forwarded_back_to_ord` IS NULL OR
            a.`date_crasm_approved_by_rd` IS NULL OR
            a.`date_transmitted_back_to_po` IS NULL OR
            a.`date_received_by_po` IS NULL OR
            a.`date_released_to_so` IS NULL OR
            a.`remarks` IS NULL
        THEN 'Pending' 
        ELSE 'Complete' 
    END AS `status`,
    COUNT(*) OVER () AS `total_applications`,
    SUM(CASE WHEN 
        a.`date_received_by_po_from_so_applicant` IS NULL OR
        a.`type_of_application` IS NULL OR
        a.`date_of_payment` IS NULL OR
        a.`or_number` IS NULL OR
        a.`date_transmitted_to_ro` IS NULL OR
        a.`date_received_by_ro` IS NULL OR
        a.`ro_screener` IS NULL OR
        a.`date_forwarded_to_the_office_of_oic` IS NULL OR
        a.`date_reviewed_by_oic_crasd` IS NULL OR
        a.`feedbacks` IS NULL OR
        a.`date_forwarded_to_ord` IS NULL OR
        a.`date_application_approved_by_rd` IS NULL OR
        a.`for_issuance_of_crasm` IS NULL OR
        a.`for_transmittal_of_crasm` IS NULL OR
        a.`date_crasm_generated` IS NULL OR
        a.`date_forwarded_back_to_the_office_of_oic_cao` IS NULL OR
        a.`date_reviewed_and_initialed_by_oic_crasd` IS NULL OR
        a.`date_forwarded_back_to_ord` IS NULL OR
        a.`date_crasm_approved_by_rd` IS NULL OR
        a.`date_transmitted_back_to_po` IS NULL OR
        a.`date_received_by_po` IS NULL OR
        a.`date_released_to_so` IS NULL OR
        a.`remarks` IS NULL 
    THEN 1 ELSE 0 END) OVER () AS `total_pending`,
    SUM(CASE WHEN 
        NOT (
            a.`date_received_by_po_from_so_applicant` IS NULL OR
            a.`type_of_application` IS NULL OR
            a.`date_of_payment` IS NULL OR
            a.`or_number` IS NULL OR
            a.`date_transmitted_to_ro` IS NULL OR
            a.`date_received_by_ro` IS NULL OR
            a.`ro_screener` IS NULL OR
            a.`date_forwarded_to_the_office_of_oic` IS NULL OR
            a.`date_reviewed_by_oic_crasd` IS NULL OR
            a.`feedbacks` IS NULL OR
            a.`date_forwarded_to_ord` IS NULL OR
            a.`date_application_approved_by_rd` IS NULL OR
            a.`for_issuance_of_crasm` IS NULL OR
            a.`for_transmittal_of_crasm` IS NULL OR
            a.`date_crasm_generated` IS NULL OR
            a.`date_forwarded_back_to_the_office_of_oic_cao` IS NULL OR
            a.`date_reviewed_and_initialed_by_oic_crasd` IS NULL OR
            a.`date_forwarded_back_to_ord` IS NULL OR
            a.`date_crasm_approved_by_rd` IS NULL OR
            a.`date_transmitted_back_to_po` IS NULL OR
            a.`date_received_by_po` IS NULL OR
            a.`date_released_to_so` IS NULL OR
            a.`remarks` IS NULL
        ) 
    THEN 1 ELSE 0 END) OVER () AS `total_completed`
FROM `applications` a
LEFT JOIN `provincial_office` po ON a.`provincial_office` = po.`province_id`
ORDER BY a.`date_created` DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);


if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: ../index.php");
    exit();
}

?>

<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title><?php echo $systemInfo['app_name'] ?></title>

    <!-- Site favicon -->

    <link
        rel="icon"
        type="image/png"
        sizes="32x32"
        href=<?php echo "../uploads/" . $systemInfo['favicon'] ?> />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Mobile Specific Metas -->
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- Google Font -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../vendors/styles/core.css" />
    <link
        rel="stylesheet"
        type="text/css"
        href="../vendors/styles/icon-font.min.css" />
    <link
        rel="stylesheet"
        type="text/css"
        href="../src/plugins/datatables/css/dataTables.bootstrap4.min.css" />
    <link
        rel="stylesheet"
        type="text/css"
        href="../src/plugins/datatables/css/responsive.bootstrap4.min.css" />
    <link rel="stylesheet" type="text/css" href="../vendors/styles/style.css" />


    <script src="process.js" defer></script>

</head>

<body>
    <div class="pre-loader">
        <div class="pre-loader-box">
            <div class="loader-logo">
                <img src=<?php echo $systemInfo['app_logo'] ?> alt="" class="dark-logo" />
            </div>
            <div class="loader-progress" id="progress_div">
                <div class="bar" id="bar1"></div>
            </div>
            <div class="percent" id="percent1">0%</div>
            <div class="loading-text">Loading...</div>
        </div>
    </div>

    <div class="header">
        <div class="header-left">
            <div class="menu-icon bi bi-list"></div>
            <div
                class="search-toggle-icon bi bi-search"
                data-toggle="header_search"></div>
        </div>
        <div class="header-right">
            <div class="dashboard-setting user-notification">
                <div class="dropdown">
                    <a
                        class="dropdown-toggle no-arrow"
                        href="javascript:;"
                        data-toggle="right-sidebar">
                        <i class="dw dw-settings2"></i>
                    </a>
                </div>
            </div>
            <div class="user-info-dropdown">
                <div class="dropdown">
                    <a
                        class="dropdown-toggle"
                        href="#"
                        role="button"
                        data-toggle="dropdown">
                        <span class="user-icon">
                            <img src=<?php echo $_SESSION['image'] ?? "https://img.freepik.com/free-vector/blue-circle-with-white-user_78370-4707.jpg"; ?> alt="Profile Image" />
                        </span>
                        <span class="user-name"><?php echo ($_SESSION['firstname'] . " " . (isset($_SESSION['middlename']) ? $_SESSION['middlename'] : "") . " " . $_SESSION['lastname']); ?></h3></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                        <form method="POST" action="../logout.php">
                            <button type="submit" name="logout" class="dropdown-item">
                                <i class="dw dw-logout"></i> Log Out
                            </button>
                        </form>
                    </div>

                </div>
            </div>
            <div class="github-link">
                <a href="https://github.com/dropways/deskapp" target="_blank"><img src="vendors/images/github.svg" alt="" /></a>
            </div>
        </div>
    </div>

    <div class="right-sidebar">
        <div class="sidebar-title">
            <h3 class="weight-600 font-16 text-blue">
                Layout Settings
                <span class="btn-block font-weight-400 font-12">User Interface Settings</span>
            </h3>
            <div class="close-sidebar" data-toggle="right-sidebar-close">
                <i class="icon-copy ion-close-round"></i>
            </div>
        </div>
        <div class="right-sidebar-body customscroll">
            <div class="right-sidebar-body-content">
                <h4 class="weight-600 font-18 pb-10">Header Background</h4>
                <div class="sidebar-btn-group pb-30 mb-10">
                    <a
                        href="javascript:void(0);"
                        class="btn btn-outline-primary header-white active">White</a>
                    <a
                        href="javascript:void(0);"
                        class="btn btn-outline-primary header-dark">Dark</a>
                </div>

                <h4 class="weight-600 font-18 pb-10">Sidebar Background</h4>
                <div class="sidebar-btn-group pb-30 mb-10">
                    <a
                        href="javascript:void(0);"
                        class="btn btn-outline-primary sidebar-light">White</a>
                    <a
                        href="javascript:void(0);"
                        class="btn btn-outline-primary sidebar-dark active">Dark</a>
                </div>

                <h4 class="weight-600 font-18 pb-10">Menu Dropdown Icon</h4>
                <div class="sidebar-radio-group pb-10 mb-10">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input
                            type="radio"
                            id="sidebaricon-1"
                            name="menu-dropdown-icon"
                            class="custom-control-input"
                            value="icon-style-1"
                            checked="" />
                        <label class="custom-control-label" for="sidebaricon-1"><i class="fa fa-angle-down"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input
                            type="radio"
                            id="sidebaricon-2"
                            name="menu-dropdown-icon"
                            class="custom-control-input"
                            value="icon-style-2" />
                        <label class="custom-control-label" for="sidebaricon-2"><i class="ion-plus-round"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input
                            type="radio"
                            id="sidebaricon-3"
                            name="menu-dropdown-icon"
                            class="custom-control-input"
                            value="icon-style-3" />
                        <label class="custom-control-label" for="sidebaricon-3"><i class="fa fa-angle-double-right"></i></label>
                    </div>
                </div>

                <h4 class="weight-600 font-18 pb-10">Menu List Icon</h4>
                <div class="sidebar-radio-group pb-30 mb-10">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input
                            type="radio"
                            id="sidebariconlist-1"
                            name="menu-list-icon"
                            class="custom-control-input"
                            value="icon-list-style-1"
                            checked="" />
                        <label class="custom-control-label" for="sidebariconlist-1"><i class="ion-minus-round"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input
                            type="radio"
                            id="sidebariconlist-2"
                            name="menu-list-icon"
                            class="custom-control-input"
                            value="icon-list-style-2" />
                        <label class="custom-control-label" for="sidebariconlist-2"><i class="fa fa-circle-o" aria-hidden="true"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input
                            type="radio"
                            id="sidebariconlist-3"
                            name="menu-list-icon"
                            class="custom-control-input"
                            value="icon-list-style-3" />
                        <label class="custom-control-label" for="sidebariconlist-3"><i class="dw dw-check"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input
                            type="radio"
                            id="sidebariconlist-4"
                            name="menu-list-icon"
                            class="custom-control-input"
                            value="icon-list-style-4"
                            checked="" />
                        <label class="custom-control-label" for="sidebariconlist-4"><i class="icon-copy dw dw-next-2"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input
                            type="radio"
                            id="sidebariconlist-5"
                            name="menu-list-icon"
                            class="custom-control-input"
                            value="icon-list-style-5" />
                        <label class="custom-control-label" for="sidebariconlist-5"><i class="dw dw-fast-forward-1"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input
                            type="radio"
                            id="sidebariconlist-6"
                            name="menu-list-icon"
                            class="custom-control-input"
                            value="icon-list-style-6" />
                        <label class="custom-control-label" for="sidebariconlist-6"><i class="dw dw-next"></i></label>
                    </div>
                </div>

                <div class="reset-options pt-30 text-center">
                    <button class="btn btn-danger" id="reset-settings">
                        Reset Settings
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="left-side-bar">
        <div class="brand-logo">
            <a href="index.html">
                <img src=<?php echo $systemInfo['app_logo'] ?> alt="" class="dark-logo" />
                <img
                    src="../vendors/images/deskapp-logo-white.svg"
                    alt=""
                    class="light-logo" />
            </a>
            <div class="close-sidebar" data-toggle="left-sidebar-close">
                <i class="ion-close-round"></i>
            </div>
        </div>
        <div class="menu-block customscroll">
            <div class="sidebar-menu">
                <ul id="accordion-menu">
                    <li>
                        <a href="dashboard.php" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-house-door"></span><span class="mtext">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="applications.php" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-person-lines-fill"></span><span class="mtext">Applications</span>
                        </a>
                    </li>
                    <li>
                        <a href="account_settings.php" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-gear"></span><span class="mtext">Account Settings</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="xs-pd-20-10 pd-ltr-20">
            <div class="title pb-20">
                <h2 class="h3 mb-0">Applications Overview</h2>
            </div>

            <div class="row pb-10">

                <div class="col-xl-4 col-lg-4 col-md-7 mb-20">
                    <div class="card-box height-100-p widget-style3">
                        <div class="d-flex flex-wrap">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark"> <?php echo !empty($applications) ? $applications[0]['total_applications'] : 0; ?></div>
                                <div class="font-14 text-secondary weight-500">
                                    Total Applications
                                </div>
                            </div>
                            <div class="widget-icon">
                                <div class="icon" data-color="#00eccf">
                                    <i class="icon-copy dw dw-calendar1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-4 col-md-7 mb-20">
                    <div class="card-box height-100-p widget-style3">
                        <div class="d-flex flex-wrap">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark"><?php echo !empty($applications) ? $applications[0]['total_completed'] : 0;  ?></div>
                                <div class="font-14 text-secondary weight-500">
                                    Completed Applications
                                </div>
                            </div>
                            <div class="widget-icon">
                                <div class="icon" data-color="#ff5b5b">
                                    <span class="icon-copy bi bi-person-check"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-4 col-md-7 mb-20">
                    <div class="card-box height-100-p widget-style3">
                        <div class="d-flex flex-wrap">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark"><?php echo !empty($applications) ? $applications[0]['total_pending'] : 0;  ?></div>
                                <div class="font-14 text-secondary weight-500">
                                    Pending Applications
                                </div>
                            </div>
                            <div class="widget-icon">
                                <div class="icon">
                                    <i
                                        class="icon-copy bi bi-x-square"
                                        aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-box pb-10">
                <div class="h5 pd-20 mb-0">Recent Patient</div>
                <table class="data-table table nowrap">
                    <thead>
                        <tr>
                            <th class="table-plus">Name</th>
                            <th>Provincial Office</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $application): ?>
                            <tr>
                                <td class="table-plus">
                                    <div class="name-avatar d-flex align-items-center">
                                        <div class="avatar mr-2 flex-shrink-0">
                                            <img
                                                src="https://cdn-icons-png.flaticon.com/512/10307/10307911.png"
                                                class="border-radius-100 shadow"
                                                width="40"
                                                height="40"
                                                alt="User Avatar" />
                                        </div>
                                        <div class="txt">
                                            <div class="weight-600"><?php echo htmlspecialchars($application['name_of_applicant']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="txt">
                                        <div class="weight-600"><?php echo htmlspecialchars($application['provincial_office']); ?></div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-pill"
                                        data-bgcolor="<?php echo ($application['status'] == 'Complete') ? '#28a745' : '#ffc107'; ?>"
                                        data-color="#ffffff">
                                        <?php echo htmlspecialchars($application['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>


        </div>
    </div>



    <!-- js -->
    <script src="../vendors/scripts/core.js"></script>
    <script src="../vendors/scripts/script.min.js"></script>
    <script src="../vendors/scripts/process.js"></script>
    <script src="../vendors/scripts/layout-settings.js"></script>
    <script src="../src/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
    <script src="../src/plugins/datatables/js/dataTables.responsive.min.js"></script>
    <script src="../src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
    <script src="../vendors/scripts/dashboard3.js"></script>
    <script src="../vendors/scripts/datatable-setting.js"></script>

</body>

</html>