<?php
session_start(); // ✅ start session
include 'db.php'; // ✅ database connection

// ✅ CHECK IF FORM SUBMITTED
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // ✅ DATABASE CHECK
    $sql = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    // ❌ CHECK QUERY ERROR
    if(!$result){
        die("Query Error: " . mysqli_error($conn));
    }

    // ✅ IF LOGIN SUCCESS
    if(mysqli_num_rows($result) > 0){

        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;

        header("Location: admin_dashboard.php");
        exit(); // 🔥 MUST

    } else {
        // ❌ WRONG LOGIN
        echo "<script>
                alert('Invalid Username or Password');
                window.location='admin_login.php';
              </script>";
    }

} else {
    // ❌ DIRECT ACCESS BLOCK
    header("Location: admin_login.php");
    exit();
}
?>