<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Smart Asset Management')</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="text-slate-800 antialiased min-h-screen flex flex-col items-center justify-center p-4">
    
    <div class="w-full max-w-lg">
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-indigo-500/30 mb-3">
                <i class="fas fa-layer-group text-white text-xl"></i>
            </div>
            <h1 class="text-2xl font-bold font-outfit tracking-wide">Smart<span class="text-indigo-600">Asset</span></h1>
        </div>

        @yield('content')
    </div>

</body>
</html>
