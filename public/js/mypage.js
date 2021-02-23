
// ルーム画像にホバー時にメニューを表示
$(function(){
  $(".preview-room").hover(
    function(e){
      $(this).children('.cover-menu').css({'opacity':'90%' , 'z-index':1 });
    },
    function(e){
      $(this).children('.cover-menu').css({'opacity':'0%' , 'z-index':-10 });
    }
  );
});

// showかeditがクリックされた時、roomIdを送信して画面遷移
$(function(){
  $(".cover-menu-link").on("click",function(){
    var room_id = $(this).parents('.preview-room').attr('id');
    var input = $("#room-post-input");
    input.val(room_id);

    if($(this).val() == "show"){
      $("#post-form").attr('action', "mypage/showroom");
    } else {
      $("#post-form").attr('action', "mypage/editroom");
    }
    
  })
})


// 削除ボタン(ゴミ箱アイコン)がクリックされたら
$(function(){
  $(".del-icon").on("click", function(){
    var room_id = $(this).parents('.preview-room').attr('id');
    alert(room_id);

    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[id="csrf-token"]').attr('content')
      },
      url: '/home/mypage/deleteroom',
      type: 'POST',
      data: {
        'room_id' : room_id
      },
      success: function(data) {
        alert("OK");
        location.reload();
      },
      error: function(XMLHttpRequest, textStatus, errorThrown){
        alert("NG");
        console.log("ajax通信に失敗しました");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
      }
    })


  })
})