<?php
session_start();
include 'config.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

$conn->query("DELETE FROM users WHERE id = $id");

header("Location: admin_page.php");
exit();
