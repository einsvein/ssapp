<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$language = $_SESSION['language'] ?? 'en';
$constantsFile = "constants_{$language}.php";
$config = include($constantsFile);

$dbConfig = $config['db'];
$display = $config['display'];
$questions = $config['questions'];

$servername = $dbConfig['servername'];
$username = $dbConfig['username'];
$password = $dbConfig['password'];
$dbname = $dbConfig['dbname'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$case_id = $_GET['case_id'] ?? $_SESSION['case_id'] ?? null;
if (!$case_id) {
    die("Invalid case ID.");
}

// Fetch case details
$sql = "SELECT * FROM cases WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $case_id);
$stmt->execute();
$result = $stmt->get_result();
$case = $result->fetch_assoc();
$stmt->close();

if (!$case) {
    die("Case not found.");
}

// Decode submitted answers from JSON
$submitted_answers = json_decode($case['answers'], true);

// Check if the answer to "residentTaxB" is "yes"
if ($submitted_answers['residentTaxB'] !== 'yes') {
    die(htmlspecialchars($display['access_denied']));
}

// Delete old answers for the current case if it's the first load
if (!isset($_SESSION['answers_deleted']) || $_SESSION['answers_deleted'] !== $case_id) {
    $sql = "DELETE FROM tax_residency_questions WHERE case_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $case_id);
    $stmt->execute();
    $stmt->close();
    $_SESSION['answers_deleted'] = $case_id;
}

// Get current step
$sql = "SELECT * FROM tax_residency_questions WHERE case_id = ? ORDER BY id DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $case_id);
$stmt->execute();
$result = $stmt->get_result();
$last_question = $result->fetch_assoc();
$stmt->close();

$current_step = $last_question ? array_search($last_question['question_key'], array_keys($questions)) + 1 : 0;

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question_key = array_keys($questions)[$current_step];
    $answer = $_POST['answer'];

    // Save the new answer
    $sql = "INSERT INTO tax_residency_questions (case_id, question_key, answer) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $case_id, $question_key, $answer);
    $stmt->execute();
    $stmt->close();

    // Determine residency if possible
    if ($answer === 'Country A' || $answer === 'Country B') {
        $sql = "UPDATE cases SET double_tax_residency = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $answer, $case_id);
        $stmt->execute();
        $stmt->close();
        unset($_SESSION['answers_deleted']); // Reset the session variable
        header("Location: result.php?case_id=$case_id");
        exit();
    } elseif ($current_step + 1 < count($questions)) {
        header("Location: double_tax_treaty.php?case_id=$case_id");
        exit();
    } else {
        // If all questions answered without determination, fallback conclusion
        $sql = "UPDATE cases SET double_tax_residency = 'Undetermined, requires manual review' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $case_id);
        $stmt->execute();
        $stmt->close();
        unset($_SESSION['answers_deleted']); // Reset the session variable
        header("Location: result.php?case_id=$case_id");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($display['double_tax_treaty_title']); ?></title>
</head>
<body>
    <h1><?php echo htmlspecialchars($display['determine_tax_residency']) . ' ' . htmlspecialchars($case['case_name']); ?></h1>
    
    <form method="post">
        <p><?php echo htmlspecialchars($questions[array_keys($questions)[$current_step]]); ?></p>
        <select name="answer" required>
            <option value="Country A"><?php echo htmlspecialchars($display['country_a']); ?></option>
            <option value="Country B"><?php echo htmlspecialchars($display['country_b']); ?></option>
            <option value="Both Countries"><?php echo htmlspecialchars($display['both']); ?></option>
            <option value="Neither"><?php echo htmlspecialchars($display['neither']); ?></option>
        </select>
        <button type="submit"><?php echo htmlspecialchars($display['next']); ?></button>
    </form>
</body>
</html>
<?php $conn->close(); ?>
