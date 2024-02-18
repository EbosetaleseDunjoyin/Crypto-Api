<!doctype html> <!-- Important: must specify -->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8"> <!-- Important: rapi-doc uses utf8 characters -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <script type="module" src="https://unpkg.com/rapidoc/dist/rapidoc-min.js"></script>
</head>

<body>
    <rapi-doc spec-url="{{ asset('documentation.json') }}"> </rapi-doc>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
</body>

</html>