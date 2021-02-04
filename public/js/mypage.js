
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