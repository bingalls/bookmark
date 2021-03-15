<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Bookmarks</title>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>
    <body>
        <div class="" id="app">
            <bookmark-component></bookmark-component>
        </div>
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
