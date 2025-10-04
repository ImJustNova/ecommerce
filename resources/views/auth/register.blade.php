<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Register - E-Commerce</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div id="register-app">
        <register-component></register-component>
    </div>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</body>
</html>