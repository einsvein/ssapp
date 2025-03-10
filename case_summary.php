<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$language = $_SESSION['language'] ?? 'en';
$constantsFile = "constants_{$language}.php";
$config = include($constantsFile);

$dbConfig = $config['db'];
$display = $config['display'];
$translated_questions = $config['translated_questions'];
$translated_answers = $config['translated_answers'];
$dynamic_form_questions = $config['dynamic_form_questions'];
$dynamic_form_answers = $config['dynamic_form_answers'];

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

// Fetch case details including answers
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

// Decode submitted answers from JSON if not null
$submitted_answers = $case['answers'] ? json_decode($case['answers'], true) : [];

// Fetch double tax treaty questions and answers
$sql = "SELECT question_key, answer FROM tax_residency_questions WHERE case_id = ? ORDER BY id ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $case_id);
$stmt->execute();
$result = $stmt->get_result();
$tax_treaty_answers = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Questions mapping
$questions = $config['questions'];

?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($display['case_summary_title']); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            color: #333;
        }
        
        h1 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        
        h2 {
            color: #2980b9;
            margin-top: 30px;
            border-left: 4px solid #3498db;
            padding-left: 10px;
        }
        
        .case-info {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #3498db;
        }
        
        .conclusion {
            background-color: #e8f4fc;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #2ecc71;
        }
        
        .data-section {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        ul {
            list-style-type: none;
            padding-left: 0;
        }
        
        li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        
        strong {
            color: #555;
            display: inline-block;
            width: 200px;
        }
        
        .answer {
            display: inline-block;
            font-weight: normal;
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>
    
    <h1><?php echo htmlspecialchars($display['case_summary_for']) . ' ' . htmlspecialchars($case['case_name']); ?></h1>
    
    <div class="case-info">
        <p><strong><?php echo htmlspecialchars($display['case_id']); ?></strong> <span class="answer"><?php echo htmlspecialchars($case['id']); ?></span></p>
        <p><strong><?php echo htmlspecialchars($display['initial_tax_liability']); ?></strong> <span class="answer"><?php echo htmlspecialchars($case['initial_liability']); ?></span></p>
        <p><strong><?php echo htmlspecialchars($display['tax_question']); ?></strong> <span class="answer"><?php echo htmlspecialchars($case['tax_question']); ?></span></p>
        <p><strong><?php echo htmlspecialchars($display['created_at']); ?></strong> <span class="answer"><?php echo htmlspecialchars($case['created_at']); ?></span></p>
    </div>

    <h2><?php echo htmlspecialchars($display['conclusion']); ?></h2>
    <div class="conclusion">
        <p><strong><?php echo htmlspecialchars($display['tax_residency']); ?></strong> <span class="answer"><?php echo ($case['conclusion'] ?? htmlspecialchars($display['pending'])); ?></span></p>
        <p><strong><?php echo htmlspecialchars($display['double_tax_residency']); ?></strong> <span class="answer"><?php echo htmlspecialchars($case['double_tax_residency'] ?? htmlspecialchars($display['pending'])); ?></span></p>
    </div>
    
    <h2><?php echo htmlspecialchars($display['submitted_answers']); ?></h2>
    <div class="data-section">
        <ul>
            <?php 
            if (!empty($submitted_answers)) {
                foreach ($submitted_answers as $question => $answer) {
                    $translated_question = $dynamic_form_questions[$question] ?? $translated_questions[$question] ?? $question;
                    $translated_answer = $dynamic_form_answers[$answer] ?? $translated_answers[$answer] ?? $answer;
                    echo "<li><strong>" . htmlspecialchars($translated_question) . ":</strong> <span class='answer'>" . htmlspecialchars($translated_answer) . "</span></li>";
                }
            } else {
                echo "<li>" . htmlspecialchars($display['no_answers_submitted']) . "</li>";
            }
            ?>
        </ul>
    </div>
    
    <h2><?php echo htmlspecialchars($display['double_tax_treaty_answers']); ?></h2>
    <div class="data-section">
        <ul>
            <?php foreach ($tax_treaty_answers as $entry) { 
                $translated_question = $translated_questions[$entry['question_key']] ?? $entry['question_key'];
                $translated_answer = $translated_answers[$entry['answer']] ?? $entry['answer'];
                ?>
                <li><strong><?php echo htmlspecialchars($translated_question); ?>:</strong> <span class="answer"><?php echo htmlspecialchars($translated_answer); ?></span></li>
            <?php } ?>
        </ul>
    </div>
</body>
</html>
<?php $conn->close(); ?>