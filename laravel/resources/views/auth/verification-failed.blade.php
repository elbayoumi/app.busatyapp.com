<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification Failed</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
        }
        .container {
            margin-top: 100px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: inline-block;
        }
        h1 {
            color: #FF0000;
        }
        p {
            color: #555;
        }
        a {
            color: #FF0000;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Email Verification Failed</h1>
        <p>There was an issue verifying your email. The link might be expired or invalid.</p>
        {{-- <a href="{{ route('dashboard.login') }}">Go to Login</a> --}}
    </div>
</body>
</html>
