<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postman File Content</title>
</head>
<body>
    <h1>Postman File Content</h1>
    <pre>{{ json_encode($data, JSON_PRETTY_PRINT) }}</pre>
    <a href="{{ route('postman.upload.form') }}">Upload Another File</a>
</body>
</html>
