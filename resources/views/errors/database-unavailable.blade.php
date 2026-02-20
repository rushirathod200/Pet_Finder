<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Unavailable</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #0f172a;
            color: #e2e8f0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
        }
        .card {
            width: min(680px, 100%);
            background: #111827;
            border: 1px solid #1f2937;
            border-radius: 12px;
            padding: 24px;
        }
        h1 {
            margin: 0 0 12px;
            font-size: 1.5rem;
        }
        p {
            margin: 0;
            line-height: 1.5;
            color: #cbd5e1;
        }
        a {
            display: inline-block;
            margin-top: 16px;
            color: #60a5fa;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Service Temporarily Unavailable</h1>
        <p>{{ $message ?? 'Please try again in a few minutes.' }}</p>
        <a href="/">Go to Home</a>
    </div>
</body>
</html>
