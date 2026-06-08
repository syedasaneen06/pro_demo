<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>

<style>
body {
    margin:0;
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #ff7e5f, #feb47b);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.login-box {
    background:white;
    padding:30px;
    border-radius:12px;
    width:320px;
    box-shadow:0 8px 25px rgba(0,0,0,0.2);
    text-align:center;
}

h2 {
    margin-bottom:20px;
    color:#333;
}

input {
    width:100%;
    padding:10px;
    margin:10px 0;
    border-radius:6px;
    border:1px solid #ccc;
}

button {
    width:100%;
    padding:10px;
    background:#ff7e5f;
    color:white;
    border:none;
    border-radius:6px;
    font-size:16px;
    cursor:pointer;
}

button:hover {
    background:#e56a50;
}
</style>

</head>

<body>

<div class="login-box">

<h2>Admin Login</h2>

<form action="admin_check.php" method="POST">

<input type="text" name="username" placeholder="Enter Username" required>

<input type="password" name="password" placeholder="Enter Password" required>

<button type="submit">Login</button>

</form>

</div>

</body>
</html>