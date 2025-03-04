<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['form_type']) && $_POST['form_type'] === 'language_form' && isset($_POST['language'])) {
    $_SESSION['language'] = $_POST['language'];
    $redirect_url = $_POST['redirect_url'] ?? 'index.php';
    header("Location: $redirect_url");
    exit();
}

$language = $_SESSION['language'] ?? 'en';
$constantsFile = "constants_{$language}.php";
$config = include($constantsFile);
?>

<nav>
    <form method="post" style="display: inline;">
        <input type="hidden" name="form_type" value="language_form">
        <input type="hidden" name="redirect_url" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
        <label for="language">Language:</label>
        <select name="language" id="language" onchange="this.form.submit()">
            <option value="en" <?php echo $language === 'en' ? 'selected' : ''; ?>>English</option>
            <option value="no" <?php echo $language === 'no' ? 'selected' : ''; ?>>Norwegian</option>
        </select>
    </form>
    <!-- Add other navigation items here -->
    <a href="index.php">Home</a>
</nav>
