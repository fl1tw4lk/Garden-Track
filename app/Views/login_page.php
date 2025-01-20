<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #f5f5f5;
        }

        .login-container {
            display: flex;
            background: white;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 900px;
            height: 500px;
        }

        .welcome-section {
            flex: 1;
            background: #4caf50;
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
        }

        .welcome-section h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .welcome-section p {
            margin-bottom: 20px;
            opacity: 0.8;
        }

        .register-btn {
            padding: 10px 30px;
            border: 2px solid white;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            font-weight: 500;
        }

        .login-section {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }

        .login-section h2 {
            font-size: 1.8em;
            margin-bottom: 30px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
            text-align: left;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }

        .form-group i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }

        .forgot-password {
            text-align: right;
            margin-bottom: 20px;
        }

        .forgot-password a {
            color: #4caf50;
            text-decoration: none;
            font-size: 0.9em;
        }

        .login-btn {
            background: #4caf50;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            width: 100%;
            font-size: 1em;
            cursor: pointer;
            margin-bottom: 20px;
        }

        .social-login {
            text-align: center;
        }

        .social-login p {
            color: #666;
            margin-bottom: 15px;
            font-size: 0.9em;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .social-icons a {
            width: 35px;
            height: 35px;
            border: 1px solid #ddd;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="welcome-section">
            <h1>Hello, Welcome!</h1>
            <p>Don't have an account?</p>
            <a href="register/index" class="register-btn">Register</a>
        </div>
        <div class="login-section">
            <h2>Login</h2>
            <form action="<?= base_url('login/auth') ?>" method="POST">
                <div class="form-group">
                    <input type="text" name="username" placeholder="Username" required>
                    <i class="fas fa-user"></i>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" required>
                    <i class="fas fa-lock"></i>
                </div>
                <div class="forgot-password">
                    <a href="#">Forgot password?</a>
                </div>
                <button type="submit" class="login-btn">Login</button>
            </form>
            <div class="social-login">
                <p>or login with social platforms</p>
                <div class="social-icons">
                    <a href="<?=$link; ?>" i class="fab fa-google"></i></a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>