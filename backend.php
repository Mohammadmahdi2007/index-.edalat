<?php
// backend.php

// تنظیمات پایگاه داده
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wood_services";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// تعریف کدنویسی UTF-8
$conn->set_charset("utf8");

// تابع برای ارسال پاسخ JSON
function sendResponse($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// API برای ثبت نام (Register)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'register') {
    $data = json_decode(file_get_contents("php://input"), true);

    $username = $conn->real_escape_string($data['username']);
    $email = $conn->real_escape_string($data['email']);
    $password = password_hash($data['password'], PASSWORD_BCRYPT);

    // بررسی وجود کاربر قبلی
    $checkUserQuery = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($checkUserQuery);

    if ($result->num_rows > 0) {
        sendResponse(['error' => 'This email is already registered.']);
    }

    // ثبت کاربر جدید
    $insertQuery = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
    if ($conn->query($insertQuery) === TRUE) {
        sendResponse(['message' => 'Registration successful!']);
    } else {
        sendResponse(['error' => 'Error: ' . $conn->error]);
    }
}

// API برای ورود (Login)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'login') {
    $data = json_decode(file_get_contents("php://input"), true);

    $email = $conn->real_escape_string($data['email']);
    $password = $conn->real_escape_string($data['password']);

    $selectQuery = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($selectQuery);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            sendResponse(['message' => 'Login successful!', 'user' => $user]);
        } else {
            sendResponse(['error' => 'Invalid credentials.']);
        }
    } else {
        sendResponse(['error' => 'User not found.']);
    }
}

// API برای نمایش محصولات
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'products') {
    $selectQuery = "SELECT * FROM products";
    $result = $conn->query($selectQuery);

    if ($result->num_rows > 0) {
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        sendResponse(['products' => $products]);
    } else {
        sendResponse(['message' => 'No products found.']);
    }
}

// API برای ارسال نظرات و سوالات
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'submit_feedback') {
    $data = json_decode(file_get_contents("php://input"), true);

    $name = $conn->real_escape_string($data['name']);
    $email = $conn->real_escape_string($data['email']);
    $message = $conn->real_escape_string($data['message']);

    $insertQuery = "INSERT INTO feedback (name, email, message) VALUES ('$name', '$email', '$message')";
    if ($conn->query($insertQuery) === TRUE) {
        sendResponse(['message' => 'Feedback submitted successfully!']);
    } else {
        sendResponse(['error' => 'Error: ' . $conn->error]);
    }
}

// اگر هیچ یک از API‌ها فراخوانی نشده باشد
sendResponse(['error' => 'Invalid request.']);
?>
<?php
include("index.html");

$username=$_POST["username"];
$password=$_POST["password"];

$link=mysqli_connect("localhost","root","","wood_services");
$result=mysqli_query($link,"SELECT * FROM `user` WHERE `username`='$username' and `password`='$password';");
mysqli_close($link);
$row=mysqli_fetch_array($result);

if($row==true){
    $_SESSION["login"]=true;
    $_SESSION["name"]=$row["name"];
    if($row["admin"]==true){
        $_SESSION["manager"]=true;
    }
    ?>
<script>
    location.replace("index.html");
</script>
    <?php

}else{
    echo("ورود  نشد");
}

?>


include("them-footer.html");
<?php
include("index.html");
?>

<div class="row">
</div>
<form action="login_action.php" method="post"  class="row m-2">

    <input type="text" class="col-12 col-md card m-1" 
    name="username" placeholder="نام کاربری">

    <input type="text" class="col-12 col-md card m-1" 
    name="password" placeholder="رمز">

    <input type="submit" class="col-12 col-md card m-1" 
    value="ورود">
</form>


include("them-footer.html");
<?php
include("index.html");
$name=$_POST["name"];
$username=$_POST["username"];
$password=$_POST["password"];

$link=mysqli_connect("localhost","root","","onenewsdb");
$result=mysqli_query($link,"INSERT INTO `user`(`name`, `username`, `password`) 
                    VALUES ('$name','$username','$password')");
mysqli_close($link);

if($result===true){
    ?>
<script>
    location.replace("index.html");
</script>
<?php
    
}else{
    echo("ثبت نام  نشد");
}

?>

<?php
include("index.html");

include("index.html");
?>

<div class="row">
</div>
<form action="register_action.php" method="post"  class="row m-2">

    <input type="text" class="col-12 col-md card m-1" 
    name="name" placeholder="نام">

    <input type="text" class="col-12 col-md card m-1" 
    name="username" placeholder="نام کاربری">

    <input type="text" class="col-12 col-md card m-1" 
    name="password" placeholder="رمز">

    <input type="submit" class="col-12 col-md card m-1" 
    value="ثبت نام">
</form>

<?php
include("index.html");
?>