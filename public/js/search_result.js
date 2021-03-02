$(function(){

  // roomがクリックされたら、room-idを送信してRoomページへ遷移
  $(".room-list").on("click", function(){
    var room_id = $(this).children(".room-id").first().text();
    $("#room-id-input").val(room_id);
    $("#room-id-form").submit();
  })

})