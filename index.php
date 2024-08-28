<?php
session_start();
// Check if the session variable 'completed' is set and not expired
if (!isset($_SESSION['completed']) || (isset($_SESSION['expire_time']) && time() > $_SESSION['expire_time'])) {
    // Redirect to checkpoint.php
    header("Location: checkpoint.php");
    exit();
}
$servername = "localhost";
$username = "root";
$password = "wjZXc9dOUWbtgk2";
$database = "feedback_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["content"])) {
    // Prepare the data for insertion
    $content = $_POST["content"];

    // Check if a file was uploaded
    if (isset($_FILES["fileUpload"]) && $_FILES["fileUpload"]["error"] == UPLOAD_ERR_OK) {
        // Define the upload directory
        $uploadDir = "uploads/";

        // Get the filename and extension
        $filename = basename($_FILES["fileUpload"]["name"]);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        // Generate a unique filename
        $uniqueFilename = uniqid() . "." . $extension;

        // Move the uploaded file to the upload directory
        $targetFilePath = $uploadDir . $uniqueFilename;
        if (move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $targetFilePath)) {
            // File upload successful, insert data into database
            $sql = "INSERT INTO feedback (content, path) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $content, $targetFilePath);
            if ($stmt->execute()) {
                $_SESSION["upload_message"] = "Gửi thành công!";
            } else {
                echo "Error inserting data: " . $conn->error;
            }
        } else {
            echo "Error uploading file.";
        }
    } else {
        // File not uploaded, insert data without file path
        $sql = "INSERT INTO feedback (content) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $content);
        if ($stmt->execute()) {
            $_SESSION["upload_message"] = "Gửi thành công!";
        } else {
            echo "Error inserting data: " . $conn->error;
        }
    }
}
//START Lang
if (isset($_GET["lang"]))
    $lang = $_GET["lang"];
else {
    if (isset($_SESSION["tsco_lang"]))
        $lang = $_SESSION["tsco_lang"];
    else
        $lang = 'vi'; // Set default language to Vietnamese
}

if ($lang != 'vi' and $lang != 'en' and $lang != 'jp')
    $lang = 'en';

$_SESSION["tsco_lang"] = $lang;
//END Lang

// Set the default language to Vietnamese if not set
if (!isset($_SESSION['tsco_lang'])) {
    $_SESSION['tsco_lang'] = 'vi';
}

// Set the $lang variable based on session
$lang = $_SESSION['tsco_lang'];

// Define titles and language strings based on the selected language
if ($lang == 'vi') {
    $home = "Trang chủ";
    $title = 'Hòm Thư Góp ý điện tử';
    $ngonNgu = "Ngôn Ngữ";
    $privacy = "Tất cả thông tin đều được bảo vệ tuyệt đối";
    $goToHome = "Trở về trang chủ sau 5 giây...";
    $content =  "Nội dung góp ý:";
    $attachment = "Đính kèm hình ảnh (nếu có)";
    $submitForm = "Gửi";
    $contentPlaceholder = "Nhập nội dung...";
    $selectFile = "Chọn tệp";
} else if ($lang == 'en') {
    $home = "Home";
    $title = 'Suggestion Box';
    $ngonNgu = "Language";
    $privacy = "All information is absolutely protected";
    $goToHome = "Return Home page after 5 seconds...";
    $content =  "Content:";
    $attachment = "Attach images (if any)";
    $submitForm = "Send";
    $contentPlaceholder = "Import content...";
    $selectFile = "Select file";
} else {
    $home = "ホームページ";
    $title = '提案箱';
    $ngonNgu = "言語";
    $privacy = "すべての情報は絶対に保護されます";
    $goToHome = "5 秒後にホーム ページに戻ります...";
    $content =  "コンテンツ：";
    $attachment = "画像を添付します（あれば）";
    $submitForm = "送信";
    $contentPlaceholder = "コンテンツをインポート...";
    $selectFile = "ファイルを選ぶ";
}
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
    <meta property="og:title" content="TSUCHIYA TSCO VIET NAM e-FeedBack"/> 
    <meta property="og:description" content="TSUCHIYA TSCO VIET NAM e-FeedBack Develop By IT Dept" />
    <title>TSCOVN | e-FeedBack</title>
    <meta property="og:image" content="./uploads/6641c160bf0f6.png" />
    <link href="https://fonts.cdnfonts.com/css/impact" rel="stylesheet">
    <link rel="icon" href="./uploads/favicon.ico" type="image/png"> 
    <title>e-FeedBack</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        .cusTextArea {
            color: #fff;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .cusTextArea::placeholder {
            color: #fff;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-dark bg-primary">
        <a class="navbar-brand" href="#">TSCOVN</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="http://sotay.tscovn.com:5173"><?=$home?><span class="sr-only">(current)</span></a>
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
    <div style="
        background-image: url('1.webp');
        background-size: cover;
        background-position: 80%;
        height: calc(100vh - 56px);
    ">
        <div class="container" style="padding-top:100px;">
            <div class="card p-2" style="background-color: rgba(0, 0, 0, 0.4);">
                <h2 class="mb-4 text-light"><?= $title ?></h2>
                <h5 class="mb-4 text-light"><?= $privacy ?></h5>

                <?php
                if (isset($_SESSION["upload_message"])) {
                    echo '<meta http-equiv="refresh" content="5; url=http://sotay.tscovn.com:5173">';
                    echo "<div class='alert alert-success' role='alert'>" . $_SESSION["upload_message"] . "<br>" . $goToHome . "</div>";

                    // Remove the upload message from session
                    unset($_SESSION["upload_message"]);
                }
                ?> <form method="POST" action="?action=add" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="textArea" class="text-light"> <?= $content ?></label>
                        <textarea style="color: white; background-color: rgba(0, 0, 0, 0.4);" class="form-control cusTextArea" name="content" id="content" placeholder="<?php echo $contentPlaceholder; ?>" rows="4" required></textarea>

                    </div> <label for="textArea" class="text-light"> <?= $attachment ?></label>

                    <div class="input-group mb-3">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" accept="image/*" name="fileUpload" id="fileUpload" aria-describedby="fileUpload" onchange="displayFileName()">
                            <label class="custom-file-label" for="inputGroupFile01"><?= $selectFile ?></label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block"><?= $submitForm ?></button>
                </form>
                <span class="text-right"><small>IT Department © 2023 TSCOVN All rights reserved. </small></span>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        function displayFileName() {
            // Get the input element and its label
            var input = document.getElementById('fileUpload');
            var label = input.nextElementSibling;

            // Update the label text with the selected file name
            label.innerText = input.files[0].name;
        }
    </script>
</body>

</html>