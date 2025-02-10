<?php
session_start();

include_once "configs/config.php";

$systemInfo = [];
$sql = "SELECT `app_name`, `title`, `front_title`, `favicon`, `app_logo` FROM `system_settings`";
$stmt = $conn->prepare($sql);
$stmt->execute();
$systemInfo = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
}
$errorInfo = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE username = :username LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['firstname'] = $user['first_name'];
        $_SESSION['middlename'] = $user['middle_name'];
        $_SESSION['lastname'] = $user['last_name'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role_id'] = $user['role_id'];
        $_SESSION['image'] = $user['image'];
        $_SESSION['provincial_office'] = (int)$user['provincial_office'];

        unset($user['password']);
        header("Location: index.php");
        exit;
    } else {
        $errorInfo = "Invalid username or password.";
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title><?php echo isset($systemInfo['app_name']) ? $systemInfo['app_name'] : "Application"; ?></title>

    <link rel="apple-touch-icon" sizes="180x180" href="vendors/images/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo isset($systemInfo['favicon']) ? $systemInfo['favicon'] : 'vendors/images/favicon-32x32.png'; ?>" />

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css" />
    <link rel="stylesheet" type="text/css" href="vendors/styles/style.css" />
</head>

<body class="login-page">
    <div class="login-header box-shadow">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="brand-logo">
                <a href="login.php">
                    <img src="<?php echo isset($systemInfo['app_logo']) && (!empty($systemInfo['app_logo'])) ? $systemInfo['app_logo'] : 'vendors/images/deskapp-logo.svg'; ?>" alt="App Logo" />
                </a>

            </div>
        </div>
    </div>
    <div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 col-lg-7">
                    <img src="vendors/images/login-page-img.png" alt="" />
                </div>
                <div class="col-md-6 col-lg-5">
                    <div class="login-box bg-white box-shadow border-radius-10">
                        <div class="login-title">
                            <h2 class="text-center text-primary">Login To <?php echo isset($systemInfo['app_name']) ? $systemInfo['app_name'] : "Application"; ?></h2>
                        </div>

                        <?php if ($errorInfo): ?>
                            <div class="alert alert-danger text-center"><?php echo $errorInfo; ?></div>
                        <?php endif; ?>

                        <form method="POST" action="login.php">
                            <div class="input-group custom">
                                <input type="text" name="username" class="form-control form-control-lg" placeholder="Username" required />
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                                </div>
                            </div>
                            <div class="input-group custom">
                                <input type="password" name="password" class="form-control form-control-lg" placeholder="**********" required />
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                                </div>
                            </div>
                            <div class="row pb-30">
                                <div class="col-6">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" />
                                        <label class="custom-control-label" for="customCheck1">Remember</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="forgot-password">
                                        <a href="forgot-password.html">Forgot Password</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="input-group mb-0">
                                        <input class="btn btn-primary btn-lg btn-block" type="submit" value="Sign In">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="vendors/scripts/core.js"></script>
    <script src="vendors/scripts/script.min.js"></script>
    <script src="vendors/scripts/process.js"></script>
    <script src="vendors/scripts/layout-settings.js"></script>
</body>

</html>