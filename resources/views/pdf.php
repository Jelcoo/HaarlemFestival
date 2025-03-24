<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title) ?></title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { color: #333; }
    </style>
</head>
<body>
    <h1 class="header"><?= htmlspecialchars($title) ?></h1>
    <p><?= htmlspecialchars($content) ?></p>
</body>
</html>
