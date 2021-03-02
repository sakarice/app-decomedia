
$(function(){

  // track情報を配列に格納
  var track_ids = new Array();
  var track_titles = new Array();
  var track_img_urls = new Array();
  var track_sound_urls = new Array();
  $('.track-info').each(function(index, track_info){
    track_ids.push($(track_info).children(".track-id").first().text());
    track_titles.push($(track_info).children(".track-title").first().text());
    track_img_urls.push($(track_info).children(".track-img-small").first().attr('src'));
    track_sound_urls.push($(track_info).children(".track-sound-url").first().text());
  })

  // $('.track-sound-url').each(function(index, track_url_dom){
  //   track_sound_urls.push($(track_url_dom).text());
  // })

  // trackURLのDOMを削除
  $('.track-sound-url').remove();

  // audioオブジェクトを作成
  var audio = new Audio();  // audioオブジェクト
  var last_volume;  // ミュート前の音量
  var playing_track_index = 0;  // 再生中トラックのインデックス;
  audio.volume /= 3;  //再生音量を1/3にしておく
  audio.src = track_sound_urls[0];  // 最初のトラックのURLをセットしておく。

  // 初回の再生確認
  $('#start-play-img').on("click", function(){
    // 再生確認用ウィンドウを削除
    $("#first-play-confirm-wrapper").remove();
    $(".track-info").first().css("color", "dodgerblue");
    audio.play(); // 再生開始
  })

  // 前の曲が終わったら次の曲を自動で再生
  audio.addEventListener('ended', function(){
    if(playing_track_index + 1 < track_sound_urls.length){
      playing_track_index++;  // 次の曲へ
    } else {
      playing_track_index = 0;  // 先頭に戻る
    }
    changeTrack(playing_track_index);
  })

  // 一つ前のトラックを再生
  $("#previous-track-icon").on("click", function(){
    if(playing_track_index > 0){
      playing_track_index --; // 一つ前の曲へ
    } else {
      playing_track_index = track_sound_urls.length - 1;  // 最後の曲へ
    }
    changeTrack(playing_track_index);
  })

  // 次のトラックを再生
  $("#next-track-icon").on("click", function(){
    if(playing_track_index + 1 < track_sound_urls.length){
      playing_track_index ++; // 次の曲へ
    } else {
      playing_track_index = 0;  // 先頭へ戻る
    }
    changeTrack(playing_track_index);
    })

  // 【関数】曲を入れ替える
  function changeTrack(index){
    audio.src = track_sound_urls[index];
    $("#room-img").attr("src", track_img_urls[index]);
    $("#playing-track-title").text(track_titles[index]);
    $(".track-info").css("color", "slategray");
    $(".track-info").eq(index).css("color", "dodgerblue");
    audio.play();
  }

  // 音量アイコンがクリックされたらミュートにする
  $("#volume-icon").on("click", function(){
    last_volume = audio.volume; // 現在の音量を保存
    audio.volume = 0; // ミュートにする
    $(this).css({'display': 'none', 'z-index': -1})
    $("#mute-icon").css({'display': 'block', 'z-index': 1});
  })

  // ミュートアイコンがクリックされたらミュート解除する
  $("#mute-icon").on("click", function(){
    audio.volume = last_volume;
    $(this).css({'display': 'none', 'z-index': -1});
    $("#volume-icon").css({'display': 'block', 'z-index': 1});
  })





// // 再生トラック入れ替え
//   $(".room-and-track-wrapper").on("click", ".track-title", function(){
//     var track_id = $(this).siblings('.track-id').html();
//     // var track_title = $(this).text();

//     getTrackUrlByAjax(track_id);
//   })


  // // 指定したidのトラック情報をajaxで取得
  // function getTrackUrlByAjax(track_id){
  //   $.ajax({
  //     headers: {
  //       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  //     },
  //     type: 'POST',
  //     url: location.href + '/getTrackInfo',
  //     datatype: 'json',
  //     data: {
  //       'track_id': track_id,
  //     }
  //   }).done(function (track){
  //     // room画像、音楽、トラック名を入れ替え 
  //     $("#room-img").attr('src', track.img_url);
  //     $("#track-player").children('source').attr('src', track.sound_url);
  //     $("#play-track-title").html(track.title);
  //     track_player.load();
  //     // 再生可能になったら自動再生
  //     track_player.addEventListener("loadeddata", ()=>{
  //       track_player.play();
  //     })

  //   }).fail(function(){
  //     alert('(ajax)track情報取得失敗');
  //   })
  // }



})