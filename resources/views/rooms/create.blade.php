<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">

  <title>create room</title>
</head>
<body>
  <div id="app">
    <create-media-component></create-media-component>
  </div>

  {{-- <script src="{{ asset('/js/roomContentsDragAndDrop.js')}}"></script> --}}
  <script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>