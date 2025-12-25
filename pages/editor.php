<?php
/* =========================
   GÜVENLİK (index.php zaten session açtı)
========================= */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'editor') {
    header("Location: index.php");
    exit();
}

/* =========================
   BLOG SİLME
========================= */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    $res = $conn->query("SELECT image FROM blogs WHERE id = $id");
    if ($row = $res->fetch_assoc()) {
        if (!empty($row['image']) && file_exists("images/" . $row['image'])) {
            unlink("images/" . $row['image']);
        }
    }

    $conn->query("DELETE FROM blogs WHERE id = $id");
    header("Location: index.php?page=editor");
    exit();
}

/* =========================
   BLOG DÜZENLEME MODU
========================= */
$editBlog = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $res = $conn->query("SELECT * FROM blogs WHERE id = $id");
    $editBlog = $res->fetch_assoc();
}

/* =========================
   BLOG EKLEME
========================= */
if (isset($_POST['add_blog'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $imageName = null;
    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "images/" . $imageName);
    }

    $stmt = $conn->prepare(
        "INSERT INTO blogs (title, content, image) VALUES (?, ?, ?)"
    );
    $stmt->bind_param("sss", $title, $content, $imageName);
    $stmt->execute();

    header("Location: index.php?page=editor");
    exit();
}

/* =========================
   BLOG GÜNCELLEME
========================= */
if (isset($_POST['update_blog'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "images/" . $imageName);

        $stmt = $conn->prepare(
            "UPDATE blogs SET title=?, content=?, image=? WHERE id=?"
        );
        $stmt->bind_param("sssi", $title, $content, $imageName, $id);
    } else {
        $stmt = $conn->prepare(
            "UPDATE blogs SET title=?, content=? WHERE id=?"
        );
        $stmt->bind_param("ssi", $title, $content, $id);
    }

    $stmt->execute();
    header("Location: index.php?page=editor");
    exit();
}

/* =========================
   BLOG LİSTESİ
========================= */
$blogs = $conn->query("SELECT * FROM blogs ORDER BY created_at DESC");
?>

<!-- =========================
     HTML BAŞLIYOR
========================= -->

<h2><?= $editBlog ? 'Blog Düzenle' : 'Yeni Blog Ekle' ?></h2>

<form class="form-box-editor" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $editBlog['id'] ?? '' ?>">

    <input type="text" name="title"
        placeholder="Blog başlığı"
        value="<?= $editBlog['title'] ?? '' ?>" required>

    <textarea name="content" rows="6"
        placeholder="Blog içeriği" required><?= $editBlog['content'] ?? '' ?></textarea>

    <input type="file" name="image" accept="image/*">

    <?php if (!empty($editBlog['image'])): ?>
        <p>Mevcut resim:</p>
        <img src="images/<?= $editBlog['image'] ?>" width="150">
    <?php endif; ?>

    <button type="submit" name="<?= $editBlog ? 'update_blog' : 'add_blog' ?>">
        <?= $editBlog ? 'Güncelle' : 'Ekle' ?>
    </button>
</form>

<h2>Bloglarım</h2>

<table class="user-table">
<tr>
    <th>Başlık</th>
    <th>Tarih</th>
    <th>İşlem</th>
</tr>

<?php while ($blog = $blogs->fetch_assoc()): ?>
<tr>
    <td><?= $blog['title'] ?></td>
    <td><?= $blog['created_at'] ?></td>
    <td>
        <a href="index.php?page=editor&edit=<?= $blog['id'] ?>">Düzenle</a> |
        <a href="index.php?page=editor&delete=<?= $blog['id'] ?>"
           onclick="return confirm('Silmek istiyor musun?')">Sil</a>
    </td>
</tr>
<?php endwhile; ?>
</table>
