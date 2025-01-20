<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            background: #ffffff;
        }

        .register-container {
            display: flex;
            background: white;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 900px;
            height: 570px;
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

        .login-btn {
            padding: 10px 30px;
            border: 2px solid white;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            font-weight: 500;
        }

        .register-section {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }

        .register-section h2 {
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

        .register-btn {
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

        .social-register {
            text-align: center;
        }

        .social-register p {
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

        .terms {
            font-size: 0.85em;
            color: #666;
            margin-bottom: 20px;
        }

        .terms a {
            color: #4caf50;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="welcome-section">
            <h1>Welcome Back!</h1>
            <p>Already have an account?</p>
            <a href="login" class="login-btn">Login</a>
        </div>
        <div class="register-section">
            <h2>Register</h2>
            <form action="<?= base_url('register/auth') ?>" method="POST">
                <!-- <div class="form-group">
                    <input type="text" name="fullname" placeholder="Full Name" required>
                    <i class="fas fa-user"></i>
                </div> -->
                <div class="form-group">
                    <input type="text" name="nama_users" placeholder="Username" required>
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email Address" required>
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" required>
                    <i class="fas fa-lock"></i>
                </div>
                <div class="form-group">
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                    <i class="fas fa-lock"></i>
                </div>
                <?php if (session()->getFlashdata('error')): ?>
                    <div style="color: red;">
                        <?php 
                        $errors = session()->getFlashdata('error'); 
                        if (is_array($errors)) {
                            foreach ($errors as $error) {
                                echo "<p>$error</p>"; 
                            }
                        } else {
                            echo "<p>$errors</p>";
                        }
                        ?>
                    </div>
                <?php endif; ?>
                <div class="terms">
                    By registering, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                </div>
                <button type="submit" class="register-btn" value="daftar" name='btn_register'>Create Account</button>
            </form>
            <div class="social-register">
                <p>or register with social platforms</p>
                <div class="social-icons">
                    <a href="<?=$link; ?>" i class="fab fa-google"></i></a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Script untuk menampilkan SweetAlert dari session flashdata -->
    <script>
        // Cek apakah ada session flashdata 
        const errorMessage = "<?= session()->getFlashdata('error1') ?>";

        if (errorMessage) {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });

            Toast.fire({
                icon: "success",
                title: errorMessage // Tampilkan pesan dari session flashdata
            });
        }
    </script>
</body>
</html>