<!DOCTYPE html>
<html>  

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title><?php echo $systemInfo['app_name'] ?></title>

    <!-- Site favicon -->
    <link
        rel="apple-touch-icon"
        sizes="180x180"
        href="../vendors/images/apple-touch-icon.png" />
    <link
        rel="icon"
        type="image/png"
        sizes="32x32"
        href="../vendors/images/favicon-32x32.png" />
    <link
        rel="icon"
        type="image/png"
        sizes="16x16"
        href="../vendors/images/favicon-16x16.png" />

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


</head>

<body>
    <!-- <div class="pre-loader">
        <div class="pre-loader-box">
            <div class="loader-logo">
                <img src="../vendors/images/deskapp-logo.svg" alt="" />
            </div>
            <div class="loader-progress" id="progress_div">
                <div class="bar" id="bar1"></div>
            </div>
            <div class="percent" id="percent1">0%</div>
            <div class="loading-text">Loading...</div>
        </div>
    </div> -->

    <div class="header">
        <div class="header-left">
            <div class="menu-icon bi bi-list"></div>
            <div
                class="search-toggle-icon bi bi-search"
                data-toggle="header_search"></div>
            <div class="header-search">
                <form>
                    <div class="form-group mb-0">
                        <i class="dw dw-search2 search-icon"></i>
                        <input
                            type="text"
                            class="form-control search-input"
                            placeholder="Search Here" />
                        <div class="dropdown">
                            <a
                                class="dropdown-toggle no-arrow"
                                href="#"
                                role="button"
                                data-toggle="dropdown">
                                <i class="ion-arrow-down-c"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <div class="form-group row">
                                    <label class="col-sm-12 col-md-2 col-form-label">From</label>
                                    <div class="col-sm-12 col-md-10">
                                        <input
                                            class="form-control form-control-sm form-control-line"
                                            type="text" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-12 col-md-2 col-form-label">To</label>
                                    <div class="col-sm-12 col-md-10">
                                        <input
                                            class="form-control form-control-sm form-control-line"
                                            type="text" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-12 col-md-2 col-form-label">Subject</label>
                                    <div class="col-sm-12 col-md-10">
                                        <input
                                            class="form-control form-control-sm form-control-line"
                                            type="text" />
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button class="btn btn-primary">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
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
                            <img src="vendors/images/photo1.jpg" alt="" />
                        </span>
                        <span class="user-name">Dominic S. Kionisala</span>
                    </a>
                    <div
                        class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                        <a class="dropdown-item" href="profile.html"><i class="dw dw-user1"></i> Profile</a>
                        <a class="dropdown-item" href="profile.html"><i class="dw dw-settings2"></i> Setting</a>
                        <a class="dropdown-item" href="faq.html"><i class="dw dw-help"></i> Help</a>
                        <a class="dropdown-item" href="login.html"><i class="dw dw-logout"></i> Log Out</a>
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
                <img src="../vendors/images/deskapp-logo.svg" alt="" class="dark-logo" />
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
                        <a href="application.php" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-person"></span><span class="mtext">Application</span>
                        </a>
                    </li>
                    <li>
                        <a href="dashboard.php" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-person-lines-fill"></span><span class="mtext">User Control</span>
                        </a>
                    </li>
                    <li>
                        <a href="settings.php" class="dropdown-toggle no-arrow">
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
            <div class="card-box pb-10">
                <div class="h5 pd-20 mb-0 d-flex justify-content-between align-items-center">
                    <span>Application</span>
                    <button class="btn btn-primary" class="btn-block"
                        data-toggle="modal"
                        data-target="#userModal"
                        type="button">
                        <i class="icon-copy dw dw-add"></i> Add Applicant
                    </button>
                </div>
                <table class="data-table table nowrap">
                    <thead>
                        <tr>
                            <th class="table-plus">Firstname</th>
                            <th>Middlename</th>
                            <th>Lastname</th>
                            <th>Suffix</th>
                            <th>Provincial Office</th>
                            <th>Status</th>
                            <th class="datatable-nosort">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="table-plus">
                                <div class="name-avatar d-flex align-items-center">
                                    <div class="avatar mr-2 flex-shrink-0">
                                        <img
                                            src="vendors/images/photo4.jpg"
                                            class="border-radius-100 shadow"
                                            width="40"
                                            height="40"
                                            alt="" />
                                    </div>
                                    <div class="txt">
                                        <div class="weight-600">Yves</div>
                                    </div>
                                </div>
                            </td>
                            <td>Owen</td>
                            <td>Bonita</td>
                            <td></td>
                            <td>Bukidnon</td>
                      
                            <td>
                                <span
                                    class="badge badge-pill"
                                    data-bgcolor="#ff3a3a"
                                    data-color="#fff">Ongoing</span>
                            </td>
                            <td>
                                 <div class="table-actions">
                                     <a href="#" data-color="#265ed7" style="margin-right: 10px;" data-toggle="modal" data-target="#editUserModal"><i class="icon-copy dw dw-edit2"></i></a>
                                     <a href="#" data-color="#e95959" onclick="deleteUser(this)"><i class="icon-copy dw dw-delete-3"></i></a>
                                 </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="table-plus">
                                <div class="name-avatar d-flex align-items-center">
                                    <div class="avatar mr-2 flex-shrink-0">
                                        <img
                                            src="vendors/images/photo5.jpg"
                                            class="border-radius-100 shadow"
                                            width="40"
                                            height="40"
                                            alt="" />
                                    </div>
                                    <div class="txt">
                                        <div class="weight-600">Shan</div>
                                    </div>
                                </div>
                            </td>
                            <td>Maghopoy</td>
                            <td>Gorra</td>
                            <td></td>
                            <td>Misamis Oriental</td>
                        
                            <td>
                                <span
                                    class="badge badge-pill"
                                    data-bgcolor=" #28a745"
                                    data-color="#fff">Done</span>
                            </td>
                            <td>
                                 <div class="table-actions">
                                     <a href="#" data-color="#265ed7" style="margin-right: 10px;" data-toggle="modal" data-target="#editUserModal"><i class="icon-copy dw dw-edit2"></i></a>
                                     <a href="#" data-color="#e95959" onclick="deleteUser(this)"><i class="icon-copy dw dw-delete-3"></i></a>
                                 </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="editFirstname" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="editFirstname" name="firstname" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="editMiddlename" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" id="editMiddlename" name="middlename">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="editLastname" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="editLastname" name="lastname" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="editSuffix" class="form-label">Suffix</label>
                        <div class="col-md-6 mb-3">
                            <label for="editSuffix" class="form-label">Suffix</label>
                            <input type="text" class="form-control" id="editSuffix" name="suffix">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editProvincialOffice" class="form-label">Provincial Office</label>
                            <input type="text" class="form-control" id="editProvincialOffice" name="provincial_office">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editDateReceivedPO" class="form-label">Date Received by PO from the SO Applicant</label>
                            <input type="date" class="form-control" id="editDateReceivedPO" name="date_received_po">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editTypeOfApplication" class="form-label">Type of Application</label>
                            <input type="text" class="form-control" id="editTypeOfApplication" name="type_of_application">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editDateOfPayment" class="form-label">Date of Payment</label>
                            <input type="date" class="form-control" id="editDateOfPayment" name="date_of_payment">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editORNumber" class="form-label">O.R. Number</label>
                            <input type="text" class="form-control" id="editORNumber" name="or_number">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editDateTransmittedRO" class="form-label">Date Transmitted to RO</label>
                            <input type="date" class="form-control" id="editDateTransmittedRO" name="date_transmitted_ro">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editDateReceivedRO" class="form-label">Date Received by RO</label>
                            <input type="date" class="form-control" id="editDateReceivedRO" name="date_received_ro">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editDateReviewedRO" class="form-label">Date Reviewed by RO Screener/SOIS Focal</label>
                            <input type="date" class="form-control" id="editDateReviewedRO" name="date_reviewed_ro">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editDateForwardedOIC" class="form-label">Date Forwarded to the Office of OIC / CAO</label>
                            <input type="date" class="form-control" id="editDateForwardedOIC" name="date_forwarded_oic">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editDateReviewedOIC" class="form-label">Date Reviewed by OIC CRASD</label>
                            <input type="date" class="form-control" id="editDateReviewedOIC" name="date_reviewed_oic">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editFeedbacks" class="form-label">FEEDBACKS</label>
                            <textarea class="form-control" id="editFeedbacks" name="feedbacks"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editDateForwardedORD" class="form-label">Date Forwarded to ORD</label>
                            <input type="date" class="form-control" id="editDateForwardedORD" name="date_forwarded_ord">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editDateApprovedRD" class="form-label">Date Application Approved by Regional Director</label>
                            <input type="date" class="form-control" id="editDateApprovedRD" name="date_approved_rd">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editDateReturnedRO" class="form-label">Date Returned to RO Focal for Issuance of CRASM</label>
                            <input type="date" class="form-control" id="editDateReturnedRO" name="date_returned_ro">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editDateTransmittalCRASM" class="form-label">Date Returned to RO Focal for Transmittal of CRASM</label>
                            <input type="date" class="form-control" id="editDateTransmittalCRASM" name="date_transmittal_crasm">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editDateGeneratedCRASM" class="form-label">Date CRASM Generated</label>
                            <input type="date" class="form-control" id="editDateGeneratedCRASM" name="date_generated_crasm">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editDateForwardedBackOIC" class="form-label">Date Forwarded Back to the Office of OIC / CAO</label>
                            <input type="date" class="form-control" id="editDateForwardedBackOIC" name="date_forwarded_back_oic">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editDateReviewedInitialedOIC" class="form-label">Date Reviewed & Initialed by OIC-CRASD</label>
                            <input type="date" class="form-control" id="editDateReviewedInitialedOIC" name="date_reviewed_initialed_oic">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editDateForwardedBackORD" class="form-label">Date Forwarded Back to ORD</label>
                            <input type="date" class="form-control" id="editDateForwardedBackORD" name="date_forwarded_back_ord">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editDateApprovedCRASM" class="form-label">Date CRASM Approved by Regional Director</label>
                            <input type="date" class="form-control" id="editDateApprovedCRASM" name="date_approved_crasm">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editDateTransmittedPO" class="form-label">Date Transmitted back to PO</label>
                            <input type="date" class="form-control" id="editDateTransmittedPO" name="date_transmitted_po">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editDateReceivedPO" class="form-label">Date Received by PO</label>
                            <input type="date" class="form-control" id="editDateReceivedPO" name="date_received_po">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editDateReleasedSO" class="form-label">Date Released to SO</label>
                            <input type="date" class="form-control" id="editDateReleasedSO" name="date_released_so">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="editRemarks" class="form-label">REMARKS</label>
                            <textarea class="form-control" id="editRemarks" name="remarks"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
    <!-- modal -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="userForm">
                        <div class="mb-3">
                            <label for="firstname" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="firstname" name="firstname" required>
                        </div>
                        <div class="mb-3">
                            <label for="middlename" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" id="middlename" name="middlename">
                        </div>
                        <div class="mb-3">
                            <label for="lastname" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="lastname" name="lastname" required>
                        </div>
                        <div class="mb-3">
                            <label for="suffix" class="form-label">Suffix</label>
                            <input type="text" class="form-control" id="suffix" name="suffix">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveUser">Save User</button>
                </div>
            </div>
        </div>
    </div>


    <!-- js -->
    <script src="../vendors/scripts/core.js"></script>
    <script src="../vendors/scripts/script.min.js"></script>
    <script src="../vendors/scripts/process.js"></script>
    <script src="../vendors/scripts/layout-settings.js"></script>
    <script src="../src/plugins/apexcharts/apexcharts.min.js"></script>
    <script src="../src/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
    <script src="../src/plugins/datatables/js/dataTables.responsive.min.js"></script>
    <script src="../src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
    <script src="../vendors/scripts/dashboard3.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function deleteUser(element) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Add your delete logic here
                    Swal.fire(
                        'Deleted!',
                        'The user has been deleted.',
                        'success'
                    )
                }
            })
        }
    </script>

</body>

</html>