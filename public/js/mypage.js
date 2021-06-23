
// プロフィール画像がクリックされたらプロフィール画面へ遷移
$("#profile-img").on("click", function(){
  window.location.href = "mypage/profile";
})

// // showかeditがクリックされた時、roomIdを送信して画面遷移
// $(function(){
//   $(".cover-menu-link").on("click",function(){
//     var room_id = $(this).parents('.preview-room').attr('id');
//     var input = $("#room-post-input");
//     input.val(room_id);

//     if($(this).val() == "show"){
//       // $("#post-form").attr('action', "mypage/showroom");
//       $("#post-form").attr('action', "/home/room/"+room_id);
//     } else {
//       $("#post-form").attr('action', "  ");
//     }
    
//   })
// })


// // 削除ボタン(ゴミ箱アイコン)がクリックされたら
// $(function(){
//   $(".del-icon").on("click", function(){
//     var room_id = $(this).parents('.preview-room').attr('id');
//     alert(room_id);

//     $.ajax({
//       headers: {
//         'X-CSRF-TOKEN': $('meta[id="csrf-token"]').attr('content')
//       },
//       url: '/ajax/room/delete',
//       type: 'POST',
//       data: {
//         'room_id' : room_id
//       },
//       success: function(data) {
//         alert("OK");
//         location.reload();
//       },
//       error: function(XMLHttpRequest, textStatus, errorThrown){
//         alert("NG");
//         console.log("ajax通信に失敗しました");
//         console.log("XMLHttpRequest : " + XMLHttpRequest.status);
//         console.log("textStatus     : " + textStatus);
//         console.log("errorThrown    : " + errorThrown.message);
//       }
//     })


//   })
// })