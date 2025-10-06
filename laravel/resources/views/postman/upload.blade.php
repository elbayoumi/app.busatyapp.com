<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Postman File</title>
</head>
<body>
    <h1>Upload Postman Collection File</h1>
    @if (session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif
    <form action="{{ route('postman.upload.handle') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" accept=".json" required>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
