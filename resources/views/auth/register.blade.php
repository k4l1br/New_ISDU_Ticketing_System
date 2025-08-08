<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #334155;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-box {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.25);
            width: 360px;
            color: #1b2a38;
        }

        .register-box h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #1b2a38;
        }

        .register-box label {
            display: block;
            margin-bottom: 8px;
            margin-top: 15px;
            color: #1b2a38;
            font-weight: 600;
        }

        .register-box input[type="text"],
        .register-box input[type="email"],
        .register-box input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin-bottom: 10px;
            background-color: rgba(51, 65, 85, 0.1); /* Light #334155 with 10% opacity */
            color: #1b2a38;
            font-size: 15px;
            box-sizing: border-box;
            transition: background-color 0.3s;
        }

        .register-box input:focus {
            background-color: rgba(51, 65, 85, 0.15); /* Slightly darker on focus */
            outline: none;
            border-color: #2563eb;
        }

        .register-box input::placeholder {
            color: #94a3b8;
        }

        .register-box button {
            width: 100%;
            padding: 12px;
            background-color: #2563eb;
            border: none;
            border-radius: 6px;
            color: white;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            margin-top: 15px;
            transition: background-color 0.3s ease;
        }

        .register-box button:hover {
            background-color: #1d4ed8;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #2563eb;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .error {
            color: #ff6b6b;
            text-align: center;
            margin-bottom: 15px;
            font-size: 14px;
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
