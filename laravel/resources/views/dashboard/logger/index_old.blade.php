<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Logs</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 28px;
            color: #007bff;
            margin-bottom: 20px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .logs {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .logs p {
            background: #ffffff;
            border-left: 5px solid #007bff;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            white-space: pre-wrap; /* Preserve line breaks */
            font-size: 14px;
            line-height: 1.6;
        }
        @media (max-width: 768px) {
            .container {
                width: 95%;
                padding: 15px;
            }
            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Application Logs</h1>
        @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif
    <form action="{{ route('dashboard.logs.clear') }}" method="GET">
        @csrf
        <button type="submit">Clear Logs</button>
    </form>
        <div class="logs">
            @foreach ($logs as $log)
                <p>{{ $log }}</p>
            @endforeach
        </div>
    </div>
</body>
</html>
