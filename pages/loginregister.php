<?php
/* =========================
   SESSION ZATEN AÇIK
========================= */

$errors = [
    'login' => $_SESSION['login_error'] ?? '',
    'register' => $_SESSION['register_error'] ?? ''
];
$activeForm = $_SESSION['active_form'] ?? 'login';

/* mesajlar gösterildikten sonra temizle */
unset($_SESSION['login_error'], $_SESSION['register_error'], $_SESSION['active_form']);

function showError($error) {
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}

function isActiveForm($formName, $activeForm) {
    return $formName === $activeForm ? 'active' : '';
}
?>

<div class="form-box <?= isActiveForm('login', $activeForm); ?>" id="login-form">
    <form class="form-group" action="loginregisterbackend.php" method="post">
        <h2>Giriş Yap</h2>
        <?= showError($errors['login']); ?>
        <input type="text" name="name" placeholder="Kullanıcı Adı" required>
        <input type="password" name="password" placeholder="Şifre" required>
        <button type="submit" name="login">Giriş Yap</button>
        <p>Hesabınız yok mu?
            <a href="#" onclick="showForm('register-form')">Kayıt Olun!</a>
        </p>
    </form>
</div>

<div class="form-box <?= isActiveForm('register', $activeForm); ?>" id="register-form">
    <form class="form-group" action="loginregisterbackend.php" method="post">
        <h2>Kayıt Ol</h2>
        <?= showError($errors['register']); ?>
        <input type="text" name="name" placeholder="Kullanıcı Adı" required>
        <input type="password" name="password" placeholder="Şifre" required>
        <select name="role" required>
            <option value="">--Rol Seçin--</option>
            <option value="user">Kullanıcı</option>
            <option value="editor">Editör</option>
            <option value="admin">Admin</option>
        </select>
        <button type="submit" name="register">Kayıt Ol</button>
        <p>Hesabınız var mı?
            <a href="#" onclick="showForm('login-form')">Giriş Yapın!</a>
        </p>
    </form>
</div>

<script src="script.js"></script>
