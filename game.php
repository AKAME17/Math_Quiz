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