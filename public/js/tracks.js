var selected_track_ids = new Array();

// 削除ボタンとRoom作成ボタンの背景色を濃いグレーにしておく。（非アクティブのつもり）
window.onload = toggleButtonColor();

// チェックボックスではなくトラックそのものがクリックされた時、クリックされたトラックの更新処理へ遷移させる。
function submitUpdateTrack(click_obj_id){
  var form = document.getElementById('update-form');
  var input = document.getElementById('update-input');
  input.value = click_obj_id;
  form.submit();
}

// 削除ボタンとRoom作成ボタンに対して、トラックが選択されていない時は背景色を濃いグレーに、選択されたら元に戻す。
function toggleButtonColor(){
  let delete_button = document.getElementById('delete-button');
  let room_create_button = document.getElementById('room-create-button');

  if(!selected_track_ids.length){
    delete_button.style.backgroundColor = "grey";
    room_create_button.style.backgroundColor = "grey";
  } else {
    delete_button.style.backgroundColor = "rgb(239, 239, 239)";
    room_create_button.style.backgroundColor = "rgb(239, 239, 239)";
  }
}

// チェック状態を更新(配列に記録)
function checkState(obj){
  if(selected_track_ids.some(value => value == obj.value)){ // トラックIDが配列要素にあったら
    selected_track_ids = selected_track_ids.filter(value => value !== obj.value) // トラックIDを配列から削除
  } else { // トラックIDが配列要素に存在しなかったら
    selected_track_ids.push(obj.value) // トラックIDを配列に追加
  }

  // 表示されているチェック順序を更新する
  const checkboxs = document.getElementsByClassName('track-select');
  for(i = 0; i < checkboxs.length; i++){
    // チェック済みボックスの配列インデックス = チェックされた順序を取得
    var index = selected_track_ids.findIndex(value => value == checkboxs[i].value);
    if(index != -1){
      checkboxs[i].previousElementSibling.innerHTML = index + 1;
    } else { 
      checkboxs[i].previousElementSibling.innerHTML = "";
    }
  }

  toggleButtonColor();
}


// トラックが選択されていない状態で削除ボタンが押されたら警告を表示。
$(function(){
  $("#delete-button").on("click", function(){
    if(!selected_track_ids.length){
      alert("トラックが選択されていません。")
      return false;
    }
  })
})

// Room作成ボタンがクリックされたら、選択済みトラックIDの配列をajaxで送る
$(function(){
  $("#room-create-button").on("click", function(){
    var room_title = $('#room-title').val();
    if(!selected_track_ids.length){
      alert("トラックが選択されていません。")
    } else if(!room_title){
      alert("room名を入力してください。");
    } else {
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[id="csrf-token"]').attr('content')
        },
        url: '/mypage/createroom',
        type: 'POST',
        data: {
          'room-title' : room_title,
          'selected-track-ids' : selected_track_ids
        },
        success: function(data) {
          alert("OK");
          $('.track-select').prop('checked', false);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
          alert("NG");
          console.log("ajax通信に失敗しました");
          console.log("XMLHttpRequest : " + XMLHttpRequest.status);
          console.log("textStatus     : " + textStatus);
          console.log("errorThrown    : " + errorThrown.message);
        }
      });
    }

  });
});

