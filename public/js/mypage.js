
// プロフィール画像がクリックされたらプロフィール画面へ遷移
$("#profile-img").on("click", function(){
  window.location.href = "mypage/profile";
})

// // showかeditがクリックされた時、mediaIdを送信して画面遷移
// $(function(){
//   $(".cover-menu-link").on("click",function(){
//     var media_id = $(this).parents('.preview-media').attr('id');
//     var input = $("#media-post-input");
//     input.val(media_id);

//     if($(this).val() == "show"){
//       // $("#post-form").attr('action', "mypage/showmedia");
//       $("#post-form").attr('action', "/media/"+media_id);
//     } else {
//       $("#post-form").attr('action', "  ");
//     }
    
//   })
// })


// // 削除ボタン(ゴミ箱アイコン)がクリックされたら
// $(function(){
//   $(".del-icon").on("click", function(){
//     var media_id = $(this).parents('.preview-media').attr('id');
//     alert(media_id);

//     $.ajax({
//       headers: {
//         'X-CSRF-TOKEN': $('meta[id="csrf-token"]').attr('content')
//       },
//       url: '/ajax/media/delete',
//       type: 'POST',
//       data: {
//         'media_id' : media_id
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