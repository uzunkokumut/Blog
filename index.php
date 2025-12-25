<?php
session_start();
include 'config.php';

$page = $_GET['page'] ?? 'home';
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <link rel="icon" type="image/svg+xml" sizes="32x32" href="images/logoipsum-393.svg">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Sayfası</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="darkmode">

<nav class="navbar">
    <a href="index.php">
        <img src="images/logoipsum-393.svg" alt="logo">
    </a>
    <div class="section">
        <?php if (isset($_SESSION['name'])): ?>
            <p>Hoş geldiniz, <?= $_SESSION['name'] ?></p>
            <a href="logout.php">Çıkış Yap</a>

        <?php elseif (($page ?? '') !== 'loginregister'): ?>
            <a href="index.php?page=loginregister">Giriş Yap</a>

        <?php endif; ?>
    </div>
</nav>

<?php
/* ================= ROUTER ================= */

switch ($page) {

    case 'blog':
        include 'pages/blog_detail.php';
        break;

    case 'admin':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php");
            exit();
        }
        include 'pages/admin.php';
        break;

    case 'editor':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'editor') {
            header("Location: index.php");
            exit();
        }
        include 'pages/editor.php';
        break;

    case 'user':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
            header("Location: index.php");
            exit();
        }
        include 'pages/user.php';
        break;

    case 'loginregister':
        include 'pages/loginregister.php';
        break;

    default:
        include 'pages/home.php';
        break;
}

?>

</body>
</html>
