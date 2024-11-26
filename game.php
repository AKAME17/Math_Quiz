<?php 
session_start();

if ($SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['start_game'])) {
    $_SESSION['operator'] = $_POST['operator'];
    $_SESSION['level'] = $_POST['level'];
    $_SESSION['test_count'] = $_POST['test_count'];
    $_SESSION['current_test'] = 0;
    $_SESSION['correct_answer'] = 0;
    $_SESSION['wrong_answer'] = 0;

    header("locatio: game.php");
    exit();
}

if ($_SERVER ['REQUEST_METHOD']=== 'post' && isset ($_POST['submit_answer'])) {
    $correct_answer = $_SESSION['correct_answer'];
    $user_name = $_POST['answer'];

    if($user_answer == $correct_answer){
        $_SESSION['correct_answer']++;
    } else {
        $_SESSION['wrong_answer']++;
    }
    $_SESSION['current_test']++;
}

if (isset($_SESSION['current_test']) && $_SESSION['current_test'] < $_SESSION['test_count']) {
    $level = $_SESSION['level'];
    $operator = $_SESSION['operator'];

    // Define ranges for each level
    $range = match ($level) {
        1 => 10,
        2 => 50,
        3 => 100,
        default => 10,
    };

    $num1 = rand(1, $range);
    $num2 = rand(1, $range);
    $correct_answer = 0;

    // Generate correct answer based on selected operator
    switch ($operator) {
        case 'add':
            $correct_answer = $num1 + $num2;
            $question = "$num1 + $num2 = ?";
            break;
        case 'subtract':
            $correct_answer = $num1 - $num2;
            $question = "$num1 - $num2 = ?";
            break;
        case 'multiply':
            $correct_answer = $num1 * $num2;
            $question = "$num1 × $num2 = ?";
            break;
        case 'divide':
            while ($num2 == 0) $num2 = rand(1, $range); // Avoid division by zero
            $correct_answer = round($num1 / $num2, 2);
            $question = "$num1 ÷ $num2 = ?";
            break;
    }

    $_SESSION['correct_answer'] = $correct_answer;

    // Generate multiple-choice answers
    $answers = [$correct_answer];
    while (count($answers) < 4) {
        $wrong_answer = rand($correct_answer - 10, $correct_answer + 10);
        if (!in_array($wrong_answer, $answers)) {
            $answers[] = $wrong_answer;
        }
    }

    shuffle($answers);
?>
    <h2>Question <?php echo $_SESSION['current_test'] + 1; ?> of <?php echo $_SESSION['test_count']; ?></h2>
    <p><?php echo $question; ?></p>
    <form method="post">
        <?php foreach ($answers as $key => $value): ?>
            <label>
                <input type="radio" name="answer" value="<?php echo $value; ?>" required> 
                <?php echo chr(65 + $key) . ". " . $value; ?>
            </label><br>
        <?php endforeach; ?>
        <button type="submit" name="submit_answer">Submit Answer</button>
    </form>

    <?php
} elseif (isset($_SESSION['current_test'])) {
    
    $correct = $_SESSION['correct_answers'];
    $wrong = $_SESSION['wrong_answers'];
?>
    <h2>Game Over!</h2>
    <p>Correct Answers: <?php echo $correct; ?></p>
    <p>Wrong Answers: <?php echo $wrong; ?></p>
    <p>Score: <?php echo round(($correct / $_SESSION['test_count']) * 100, 2); ?>%</p>
    <form method="post" action="index.html">
        <button type="submit" name="reset_game">Play Again</button>
    </form>
<?php
    session_destroy();
}
?>