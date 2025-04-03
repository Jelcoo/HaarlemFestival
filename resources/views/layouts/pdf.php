<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice PDF</title>
    <style>
        body {
            font-family: Helvetica, sans-serif;
            font-size: 12px;
            padding: 20px;
            color: #333;
        }
        h1 { font-size: 22px; margin-bottom: 0; }
        h3 { margin-top: 30px; font-size: 16px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
        }
        .section { margin-top: 30px; }
    </style>
</head>
<body>
    {{content}}
</body>
</html>
