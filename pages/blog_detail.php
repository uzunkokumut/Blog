<?php
if (!isset($_GET['id'])) {
    echo "<p style='text-align:center'>Blog bulunamadı</p>";
    return;
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM blogs WHERE id = $id");

if ($result->num_rows === 0) {
    echo "<p style='text-align:center'>Blog bulunamadı</p>";
    return;
}

$blog = $result->fetch_assoc();
?>

<div class="blog-hero"
     style="background-image: url('images/<?= $blog['image'] ?>');">
    <div class="blog-overlay">
        <h1><?= $blog['title'] ?></h1>
        <p><?= nl2br($blog['content']) ?></p>
    </div>
</div>
