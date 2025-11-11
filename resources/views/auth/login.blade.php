<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CoolPro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 440px;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            padding: 40px 35px;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(to right, #8a2be2, #4b0082);
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-icon {
            background: linear-gradient(135deg, #8a2be2, #4b0082);
            border-radius: 16px;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 0 8px 20px rgba(138, 43, 226, 0.3);
        }

        .logo-icon i {
            font-size: 28px;
            color: white;
        }

        .logo-text {
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(to right, #8a2be2, #4b0082);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 8px;
        }

        .tagline {
            color: #6c757d;
            font-size: 15px;
            margin-top: 5px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #495057;
            font-size: 14px;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 1.5px solid #e1e5e9;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s;
            background: #f8f9fa;
        }

        .form-input:focus {
            outline: none;
            border-color: #8a2be2;
            box-shadow: 0 0 0 3px rgba(138, 43, 226, 0.1);
            background: white;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }

        .checkbox-container input {
            margin-right: 10px;
            width: 18px;
            height: 18px;
            accent-color: #8a2be2;
        }

        .checkbox-container label {
            color: #495057;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(to right, #8a2be2, #4b0082);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(138, 43, 226, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 20px rgba(138, 43, 226, 0.4);
        }

        .btn-login i {
            margin-right: 8px;
            font-size: 18px;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background: #e1e5e9;
        }

        .divider-text {
            padding: 0 15px;
            color: #6c757d;
            font-size: 14px;
            font-weight: 500;
        }

        .btn-google {
            width: 100%;
            padding: 14px;
            background: white;
            color: #495057;
            border: 1.5px solid #e1e5e9;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-google:hover {
            border-color: #8a2be2;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .btn-google img {
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }

        .register-link {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: #6c757d;
        }

        .register-link a {
            color: #8a2be2;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }

        .register-link a:hover {
            color: #4b0082;
            text-decoration: underline;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
        }

        .error-message.show {
            display: block;
        }

        .success-message {
            background: #d1edff;
            color: #0c5460;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
        }

        .success-message.show {
            display: block;
        }

        @media (max-width: 480px) {
            .card {
                padding: 30px 25px;
            }

            .logo-icon {
                width: 60px;
                height: 60px;
            }

            .logo-icon i {
                font-size: 24px;
            }

            .logo-text {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="card">
            <!-- Header -->
            <div class="logo-container">
                <div class="logo-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h1 class="logo-text">CoolPro</h1>
                <p class="tagline">Masuk ke akun Anda</p>
            </div>

            <!-- Success Message -->
            <div id="successMessage" class="success-message">
                <i class="fas fa-check-circle"></i>
                <span id="successText">Login berhasil! Mengarahkan ke dashboard...</span>
            </div>

            <!-- Error Message -->
            <div id="errorMessage" class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <span id="errorText">
                    <?php
                    if (session('errors') && session('errors')->any()) {
                        echo e(session('errors')->first());
                    } else {
                        echo 'Email atau password salah. Silakan coba lagi.';
                    }
                    ?>
                </span>
            </div>

            <!-- Login Form -->
            <form id="loginForm" action="/login" method="POST">
                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">

                <!-- Email/Username Field -->
                <div class="form-group">
                    <label for="login" class="form-label">Email atau Username</label>
                    <input type="text" id="login" name="login" class="form-input" placeholder="Masukkan email atau username" required
                        value="<?php echo e(old('login')); ?>">
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Masukkan password" required>
                </div>

                <!-- Remember Me -->
                <div class="checkbox-container">
                    <input type="checkbox" id="remember" name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                    <label for="remember">Ingat saya</label>
                </div>

                <!-- Login Button -->
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </button>

                <!-- Divider -->
                <div class="divider">
                    <div class="divider-line"></div>
                    <div class="divider-text">ATAU</div>
                    <div class="divider-line"></div>
                </div>

                <!-- Google Login Button -->
                <a href="/auth/google/redirect" class="btn-google">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg" alt="Google">
                    Lanjutkan dengan Google
                </a>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const login = document.getElementById('login').value;
            const password = document.getElementById('password').value;
            const errorMessage = document.getElementById('errorMessage');
            const successMessage = document.getElementById('successMessage');
            const errorText = document.getElementById('errorText');
            const successText = document.getElementById('successText');

            // Reset messages
            errorMessage.classList.remove('show');
            successMessage.classList.remove('show');

            // Simple validation
            if (!login || !password) {
                errorText.textContent = 'Email/username dan password harus diisi.';
                errorMessage.classList.add('show');
                return;
            }

            if (password.length < 6) {
                errorText.textContent = 'Password harus minimal 6 karakter.';
                errorMessage.classList.add('show');
                return;
            }

            // Show loading message
            successText.textContent = 'Login berhasil! Mengarahkan ke dashboard...';
            successMessage.classList.add('show');

            // Submit the form - Laravel will handle the redirect based on role
            this.submit();
        });

        // Add focus effects to form inputs
        const inputs = document.querySelectorAll('.form-input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });

            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.classList.remove('focused');
                }
            });
        });

        // Show error message if there are errors from server
        window.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const error = urlParams.get('error');
            const errorMessage = document.getElementById('errorMessage');

            // Show error message if there are Laravel errors
            <?php if (session('errors') && session('errors')->any()): ?>
                errorMessage.classList.add('show');
            <?php endif; ?>

            if (error) {
                const errorText = document.getElementById('errorText');
                errorText.textContent = decodeURIComponent(error);
                errorMessage.classList.add('show');
            }
        });
    </script>
</body>

</html>