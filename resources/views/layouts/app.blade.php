<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Authentification')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; background: #f8f8f8; margin: 0; padding: 0; }
        .container { max-width: 400px; margin: 40px auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; padding: 2rem; }
        label { display: block; margin-bottom: 0.2rem; font-weight: bold; }
        input, textarea, select { width: 100%; margin-bottom: 1rem; padding: 0.5rem; border-radius: 4px; border: 1px solid #ccc; }
        button { background: #f53003; color: #fff; border: none; padding: 0.7rem 1.5rem; border-radius: 4px; cursor: pointer; font-weight: bold; }
        button:hover { background: #c41c00; }
        h2 { text-align: center; color: #f53003; }
        .error { color: #c41c00; font-size: 0.95em; margin-bottom: 1rem; }
    </style>
</head>
<body>
    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 