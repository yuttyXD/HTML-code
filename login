<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - CCIT4078A</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .topnav {
            background-color: rgb(255, 255, 119);
            overflow: hidden;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            border: black solid 1px;
        }
        .topnav a {
            float: left;
            color: black;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            font-size: 17px;
        }
        .topnav a:hover {
            background-color: #ffff99;
            color: black;
        }
        .topnav .brand {
            float: left;
            color: black;
            text-align: center;
            padding: 14px 20px;
            font-size: 17px;
            font-weight: bold;
            pointer-events: none;
        }
        .topnav .login {
            float: right;
        }
        .login-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 100px 20px 20px;
        }
        .login-box {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-box h2 {
            margin-bottom: 30px;
            color: black;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        .login-btn {
            background-color: rgb(255, 255, 119);
            color: black;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }
        .login-btn:hover {
            background-color: #ffff99;
        }
    </style>
</head>
<body>

    <div class="topnav">
        <div class="brand">CCIT4078A</div>
        <a href="login.html" class="login">Login</a>
    </div>

    <div class="login-container">
        <div class="login-box">
            <h2>Login</h2>
            <form>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="login-btn">Login</button>
            </form>
        </div>
    </div>

</body>
</html>
