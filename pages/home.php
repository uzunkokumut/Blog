<?php
$result = $conn->query("SELECT * FROM blogs ORDER BY created_at DESC");
?>

<p style="text-align:center">
Arabalar hakkında saçmaladığım siteme hoş geldiniz! <br>
Burada kimsenin takmadığı şeyler hakkında yazıyorum.
</p>

<div class="bloglar">
<?php while ($blog = $result->fetch_assoc()): ?>
    <a href="index.php?page=blog&id=<?= $blog['id'] ?>">
        <div class="blog">
            <?php if ($blog['image']): ?>
                <img src="images/<?= $blog['image'] ?>" alt="">
            <?php endif; ?>
            <h2><?= $blog['title'] ?></h2>
        </div>
    </a>
<?php endwhile; ?>
</div>
