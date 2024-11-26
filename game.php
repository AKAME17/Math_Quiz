<?php 
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['start_game'])) {
    $_SESSION['operator'] = $_POST['operator'];
    $_SESSION['level'] = $_POST['level'];
    $_SESSION['test_count'] = $_POST['test_count'];
    $_SESSION['current_test'] = 0;
    $_SESSION['correct_answers'] = 0;
    $_SESSION['wrong_answers'] = 0;

    header("location: game.php");
    exit();
}

if ($_SERVER ['REQUEST_METHOD']=== 'POST' && isset ($_POST['submit_answer'])) {
    $correct_answer = $_SESSION['correct_answer'];
    $user_answer= $_POST['answer'];

    if($user_answer == $correct_answer){
        $_SESSION['correct_answers']++;
    } else {
        $_SESSION['wrong_answers']++;
    }
    $_SESSION['current_test']++;
}

if (isset($_SESSION['current_test']) && $_SESSION['current_test'] < $_SESSION['test_count']) {
    $level = $_SESSION['level'];
    $operator = $_SESSION['operator'];

    $range = match ($level) {
        1 => 10,
        2 => 50,
        3 => 100,
        default => 10,
    };

    $num1 = rand(1, $range);
    $num2 = rand(1, $range);
    $correct_answer = 0;

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
            while ($num2 == 0) $num2 = rand(1, $range);
            $correct_answer = round($num1 / $num2, 2);
            $question = "$num1 ÷ $num2 = ?";
            break;
    }

    $_SESSION['correct_answer'] = $correct_answer;


    $answers = [$correct_answer];
    while (count($answers) < 4) {
        $wrong_answer = rand($correct_answer - 10, $correct_answer + 10);
        if (!in_array($wrong_answer, $answers)) {
            $answers[] = $wrong_answer;
        }
    }

    shuffle($answers);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Simple Math Game</title>
</head>
<body>
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
</body>
</html>
<?php
} elseif (isset($_SESSION['current_test'])) {
    
    $correct = $_SESSION['correct_answers'];
    $wrong = $_SESSION['wrong_answers'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Simple Math Game</title>
</head>
<body>
    <h2>Game Over!</h2>
    <p>Correct Answers: <?php echo $correct; ?></p>
    <p>Wrong Answers: <?php echo $wrong; ?></p>
    <p>Score: <?php echo round(($correct / $_SESSION['test_count']) * 100, 2); ?>%</p>
    <form method="post" action="index.html">
        <button type="submit" name="reset_game">Play Again</button>
    </form>
</body>
</html>
<?php
    session_destroy();
}
?>