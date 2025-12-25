<?php
/* =========================
   GÜVENLİK
========================= */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

/* =========================
   KULLANICI SİLME
========================= */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // Kendini silmeyi engelle (çok önemli)
    if ($id != $_SESSION['id'] ?? 0) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    header("Location: index.php?page=admin");
    exit();
}

/* =========================
   ROL GÜNCELLEME
========================= */
if (isset($_POST['update_role'])) {
    $userId = $_POST['user_id'];
    $newRole = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $newRole, $userId);
    $stmt->execute();

    header("Location: index.php?page=admin");
    exit();
}

/* =========================
   KULLANICILAR
========================= */
$result = $conn->query("SELECT id, name, role FROM users");
?>

<h2>Kullanıcı Yönetimi</h2>

<table class="user-table">
<tr>
    <th>ID</th>
    <th>İsim</th>
    <th>Rol</th>
    <th>Sil</th>
</tr>

<?php while ($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['name'] ?></td>
    <td>
        <form class="form-box active" method="post">
            <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
            <select name="role">
                <option value="user" <?= $row['role']=='user'?'selected':'' ?>>User</option>
                <option value="editor" <?= $row['role']=='editor'?'selected':'' ?>>Editor</option>
                <option value="admin" <?= $row['role']=='admin'?'selected':'' ?>>Admin</option>
            </select>
            <button type="submit" name="update_role">Güncelle</button>
        </form>
    </td>
    <td>
        <a href="index.php?page=admin&delete=<?= $row['id'] ?>"
        onclick="return confirm('Silmek istiyor musunuz?')">
        Sil
        </a>
    </td>
</tr>
<?php endwhile; ?>
</table>
