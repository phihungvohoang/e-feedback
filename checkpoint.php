<?php
session_start();

// Redirect if the user has completed the questionnaire
if (isset($_SESSION['completed']) && time() <= $_SESSION['expire_time']) {
    header("Location: index.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "wjZXc9dOUWbtgk2";
$dbname = "feedback_db";

// Set language
if (isset($_GET["lang"]))
    $lang = $_GET["lang"];
else {
    if (isset($_SESSION["tsco_lang"]))
        $lang = $_SESSION["tsco_lang"];
    else
        $lang = 'vi'; // Default language is Vietnamese
}

// Validate language
if ($lang != 'vi' and $lang != 'en' and $lang != 'jp')
    $lang = 'en';

$_SESSION["tsco_lang"] = $lang;

// Set language variables
if ($lang == 'vi') {
    $title = 'Bạn phải trả lời 2 câu hỏi để xác nhận là nhân viên của công ty.';
    $ngonNgu = "Ngôn Ngữ";
    $wait = "Bạn đã trả lời sai, vui lòng đợi 5 phút trước khi thử lại.";
    $timeRemaining = "Thời gian còn lại:";
} else if ($lang == 'en') {
    $title = 'You must answer 2 questions to confirm that you are an employee of the company.';
    $ngonNgu = "Language";
    $wait = "You must wait before trying again.";
    $timeRemaining = "Time remaining:";
} else {
    $title = 'あなたが会社の従業員であることを確認するには、2 つの質問に答える必要があります。';
    $ngonNgu = "言語";
    $wait = "再試行する前に待つ必要があります。";
    $timeRemaining = "残り時間:";
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch random questions
function fetch_questions($conn)
{
    $sql = "SELECT id, QuestionText, AnswerA, AnswerB, AnswerC, CorrectAnswer FROM questions ORDER BY RAND() LIMIT 2";
    return $conn->query($sql);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $answers = $_POST['answers'];
    $correctCount = 0;

    foreach ($answers as $id => $answer) {
        // Fetch the correct answer from the database
        $stmt = $conn->prepare("SELECT CorrectAnswer FROM questions WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($correctAnswer);
        $stmt->fetch();
        $stmt->close();

        if ($answer == $correctAnswer) {
            $correctCount++;
        }
    }

    if ($correctCount == count($answers)) {
        // Set session variable to indicate successful completion
        $_SESSION['completed'] = true;
        // Set session expiration time to 20 minutes
        $_SESSION['expire_time'] = time() + (20 * 60);
        // Redirect to index.php
        header("Location: index.php");
        exit();
    } else {
        // Set a session variable to handle the wait time
        $_SESSION['wait_time'] = time() + (5 * 60); // 5 minutes from now
    }
}

// Fetch random questions if no active wait time
if (!isset($_SESSION['wait_time']) || time() >= $_SESSION['wait_time']) {
    $result = fetch_questions($conn);
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="title" content="TSCOVN e-FeedBack">
        <meta http-equiv="content-language" content="en" />
        <meta name="language" content="English">
        <meta name="Description" content="TSCOVN e-FeedBack Develop By IT Dept">
        <meta name="author" content="Hung IT aka Hùng Đẹp Trai">
        <meta name="Keywords" content="TSUCHIYA TSCO VIET NAM e-FeedBack, TSCOVN e-FeedBack" />
        <meta property="og:image" content="./uploads/logo.png" />
        <meta property="og:title" content="TSUCHIYA TSCO VIET NAM e-FeedBack" />
        <meta property="og:description" content="TSUCHIYA TSCO VIET NAM e-FeedBack Develop By IT Dept" />
        <title>TSCOVN | e-FeedBack</title>
        <link rel="icon" href="./uploads/favicon.ico" type="image/png">
        <!-- Include Bootstrap CSS and JavaScript -->
        <link href="./css/bootstrap.min.css" rel="stylesheet">
        <script src="./js/jquery.min.js"></script>
        <script src="./js/popper.min.js"></script>
        <script src="./js/bootstrap.min.js"></script>
        <script>
            document.addEventListener('contextmenu', event => event.preventDefault())

            document.onkeydown = function(e) {
                if (e.ctrlKey &&
                    (e.keyCode === 85)) {
                    return false
                }

                if ((e.ctrlKey && e.shiftKey) &&
                    (e.keyCode === 73)) {
                    return false
                }

                if (event.keyCode === 123) {
                    return false
                }
            }
        </script>
    </head>

    <body>
        <nav class="navbar navbar-dark bg-tscovn">
            <a class="navbar-brand" href="#">TSCOVN</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="http://sotay.tscovn.com:5173">Home <span class="sr-only">(current)</span></a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?= $ngonNgu ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="?lang=vi">Vietnamese</a>
                            <a class="dropdown-item" href="?lang=en">English</a>
                            <a class="dropdown-item" href="?lang=jp">Japanese</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="container mt-5">
            <h2><?= $title ?></h2>
            <form method="post" action="">
                <?php
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo '
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">' . $row["QuestionText"] . '</h5>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="answers[' . $row["id"] . ']" id="answerA' . $row["id"] . '" value="A">
                            <label class="form-check-label" for="answerA' . $row["id"] . '">
                                ' . $row["AnswerA"] . '
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="answers[' . $row["id"] . ']" id="answerB' . $row["id"] . '" value="B">
                            <label class="form-check-label" for="answerB' . $row["id"] . '">
                                ' . $row["AnswerB"] . '
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="answers[' . $row["id"] . ']" id="answerC' . $row["id"] . '" value="C">
                            <label class="form-check-label" for="answerC' . $row["id"] . '">
                                ' . $row["AnswerC"] . '
                            </label>
                        </div>
                    </div>
                </div>
                ';
                    }
                    echo '<button type="submit" class="btn btn-primary">Submit</button>';
                } else {
                    echo "0 results";
                }
                ?>
            </form>
        </div>
    </body>

    </html>

<?php
} else {
    // Active wait time
    $remainingTime = $_SESSION['wait_time'] - time();
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="title" content="TSCOVN e-FeedBack">
        <meta http-equiv="content-language" content="en" />
        <meta name="language" content="English">
        <meta name="Description" content="TSCOVN e-FeedBack Develop By IT Dept">
        <meta name="author" content="Hung IT aka Hùng Đẹp Trai">
        <meta name="Keywords" content="TSUCHIYA TSCO VIET NAM e-FeedBack, TSCOVN e-FeedBack" />
        <meta property="og:image" content="http://monitor.tscovn.com:9001/images/tsco.png" />
        <meta property="og:title" content="TSUCHIYA TSCO VIET NAM e-FeedBack" />
        <meta property="og:description" content="TSUCHIYA TSCO VIET NAM e-FeedBack Develop By IT Dept" />
        <title>TSCOVN | e-FeedBack</title>
        <!-- Include Bootstrap CSS -->
        <link href="./css/bootstrap.min.css" rel="stylesheet">
        <script>
            function startTimer(duration, display) {
                var timer = duration,
                    minutes, seconds;
                setInterval(function() {
                    minutes = parseInt(timer / 60, 10);
                    seconds = parseInt(timer % 60, 10);

                    minutes = minutes < 10 ? "0" + minutes : minutes;
                    seconds = seconds < 10 ? "0" + seconds : seconds;

                    display.textContent = minutes + ":" + seconds;

                    if (--timer < 0) {
                        timer = duration;
                        location.href = 'index.php';

                    }
                }, 1000);
            }

            window.onload = function() {
                var remainingTime = <?= $remainingTime ?>,
                    display = document.querySelector('#time');

                startTimer(remainingTime, display);
            };
        </script>
    </head>

    <body>
        <nav class="navbar navbar-dark bg-tscovn">
            <a class="navbar-brand" href="#">TSCOVN</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="http://sotay.tscovn.com:5173">Home <span class="sr-only">(current)</span></a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?= $ngonNgu ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="?lang=vi">Vietnamese</a>
                            <a class="dropdown-item" href="?lang=en">English</a>
                            <a class="dropdown-item" href="?lang=jp">Japanese</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="container d-flex justify-content-center align-items-center min-vh-90">
            <div class="text-center">
                <div class="alert alert-danger" role="alert">
                    <?= $wait ?>
                </div>
                <div> <?= $timeRemaining ?> <h2 id="time"></h2>
                </div>
                <video src="http://113.161.161.246:9001/images/TeamBuilding%20TSCOVN.mp4" width="100%" height="550" controls="" preload="metadata" autoplay="true"></video>
            </div>
        </div>

        <!-- Include Bootstrap JS and dependencies -->
        <script src="./js/jquery.min.js"></script>
        <script src="./js/popper.min.js"></script>
        <script src="./js/bootstrap.min.js"></script>
        <script>
            document.addEventListener('contextmenu', event => event.preventDefault())

            document.onkeydown = function(e) {
                if (e.ctrlKey &&
                    (e.keyCode === 85)) {
                    return false
                }

                if ((e.ctrlKey && e.shiftKey) &&
                    (e.keyCode === 73)) {
                    return false
                }

                if (event.keyCode === 123) {
                    return false
                }
            }
        </script>
    </body>

    </html>

<?php
}

$conn->close();
?>