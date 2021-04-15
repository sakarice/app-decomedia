<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
  <title>chat</title>
</head>
<body>
  <div id="app">
    <test-parent-component></test-parent-component>
    <test-component v-model="message"></test-component>
    {{-- <button v-on:click="showModal()">画像を選択します</button> --}}
    <textarea v-model="message"></textarea>
    <button v-on:click="send()">送信</button>
    <div v-for="m in messages">
      <span v-text="m.created_at"></span>: &nbsp;
      <span v-text="m.user_id"></span>: &nbsp;
      <span v-text="m.message"></span>
    </div>
  </div>
  <script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>