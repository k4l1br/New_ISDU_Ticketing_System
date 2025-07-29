<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - CEISSAFP Ticketing System</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: linear-gradient(to bottom right, #1b2a38, #0f172a);
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            color: white;
        }

        .login-container h2 {
            text-align: center;
            color: #ffffff;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .login-container h3 {
            text-align: center;
            font-weight: normal;
            margin-bottom: 20px;
            color: #ffffff;
            font-size: 16px;
        }

        label {
            font-weight: 600;
            display: block;
            margin-bottom: 6px;
            color: #ddd;
        }

        .input-group {
            position: relative;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 40px 12px 10px;
            margin-bottom: 15px;
            border: 1px solid #334155;
            border-radius: 6px;
            background-color: #0f172a;
            color: white;
            font-size: 15px;
            box-sizing: border-box;
        }

        input::placeholder {
            color: #94a3b8;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            width: 20px;
            height: 20px;
            fill: #94a3b8;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #2563eb;
            border: none;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #1d4ed8;
        }

        .error {
            color: #ff6b6b;
            text-align: center;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
        }

        .register-link a {
            color: #60a5fa;
            text-decoration: none;
            font-size: 14px;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2><strong>CEISSAFP</strong></h2>
        <h3>Ticketing System</h3>

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <label for="login">Username or Email</label>
            <input type="text" name="login" id="login" required placeholder="Enter your username or email">

            <label for="password">Password</label>
            <div class="input-group">
                <input type="password" name="password" id="password" required placeholder="Enter your password">
                <svg class="toggle-password" onclick="togglePassword()" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path id="eye" d="M12 5c-7 0-11 7-11 7s4 7 11 7 11-7 11-7-4-7-11-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10z"/>
                    <circle id="eye-pupil" cx="12" cy="12" r="3"/>
                </svg>
            </div>

            <button type="submit">Login</button>
        </form>

        <div class="register-link">
            <p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const eyeIcon = document.querySelector(".toggle-password");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.innerHTML = `<path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12zm11-5a5 5 0 1 0 0 10 5 5 0 0 0 0-10z"/><circle cx="12" cy="12" r="3"/>`;
            } else {
                passwordInput.type = "password";
                eyeIcon.innerHTML = `<path d="M12 5c-7 0-11 7-11 7s4 7 11 7 11-7 11-7-4-7-11-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10z"/><circle cx="12" cy="12" r="3"/>`;
            }
        }
    </script>
</body>
</html>
