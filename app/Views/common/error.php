<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f8f8f8;
        }
        .error-container {
            text-align: center;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #e74c3c;
        }
        p {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>An Error Occurred</h1>
        <p>We apologize, but an unexpected error has occurred. Please try again later.</p>
        <?php if (isset($message)): ?>
            <p>Details: <?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>