<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ffffff; /* white background */
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-box {
            background: linear-gradient(135deg, #0f0f1a, #1e3a5f); /* dark blue-black gradient */
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.25);
            width: 360px;
            color: #ffffff;
        }

        .register-box h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .register-box label {
            display: block;
            margin-bottom: 8px;
            margin-top: 15px;
        }

        .register-box input[type="text"],
        .register-box input[type="email"],
        .register-box input[type="password"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 6px;
            margin-bottom: 10px;
        }

        .register-box button {
            width: 100%;
            padding: 12px;
            background-color: #4f83ff;
            border: none;
            border-radius: 6px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            margin-top: 15px;
        }

        .register-box button:hover {
            background-color: #3f6fe0;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #f0f0f0;
            text-decoration: none; /* Removes underline */
            font-size: 14px;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: #87cefa;
        }
    </style>
</head>
<body>

    <div class="register-box">
        <h2>Register</h2>
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <label for="name">Name</label>
            <input id="name" type="text" name="name" required autofocus>
            
             <label for="username">Username</label>
            <input type="text" name="username" id="username" required>

            <label for="email">Email</label>
            <input id="email" type="email" name="email" required>

            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>

            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>

            <button type="submit">Register</button>
        </form>

        <a href="{{ route('login') }}" class="back-link">Back to Login</a>

    </div>

</body>
</html>
