
$(function(){

  // 再生プレイヤーDOMの取得 & 音量を半分にしておく。
  const track_player = document.getElementById("track-player");
  track_player.volume /= 3;

  // 更新ボタンが押されたら、現在のRoomトラックのIDをPOSTする。
  $("#update-button").on("click", function(){
    var room_id = $("#room-id").text();
    var room_title = $("#room-title").text();

    // トラックIDの配列を作成
    var track_ids = new Array();
    var track_id_doms = $("#room-track-list").find(".track-id");
    track_id_doms.each(function(index, track_id_dom){
      var track_id = $(track_id_dom).text();
      track_ids.push(track_id);
    });

    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type: 'POST',
      url: location.href + '/updateRoom',
      datatype: 'json',
      data: {
        'room_id' : room_id,
        'room_title' : room_title,
        'track_ids': track_ids,
      }
    }).done(function(){
      // room画像、音楽、トラック名を入れ替え
      alert("Room更新完了");
    }).fail(function(){
      alert('Room更新失敗');
    })


  })

  // Roomタイトルがクリックされたら編集モードにする。
  $("#room-title").on("click", function(){
    if(!$(this).hasClass("on")){
      $(this).addClass("on");
      var txt = $(this).text();
      $(this).html("<input type=text value=" +txt+ ">");

      // 編集ボックスからフォーカスが外れたら編集終了
      $("#room-title > input").focus().blur(function(){
        judgeAndSetDefaultVal($(this));
      });


      function judgeAndSetDefaultVal(obj){
        var inputVal = obj.val();
        if(inputVal === ""){
          inputVal = txt;
        };
        obj.parent().removeClass("on").text(inputVal);
      }    
    };
  });

  


  // 再生トラック入れ替え
  $(".room-and-track-wrapper").on("click", ".track-title", function(){
    var track_id = $(this).siblings('.track-id').html();
    var track_title = $(this).text();
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type: 'POST',
      url: location.href + '/getTrackInfo',
      datatype: 'json',
      data: {
        'track_id': track_id,
      }
    }).done(function (track){
      // room画像、音楽、トラック名を入れ替え 
      $("#media-img").attr('src', track.img_url);
      $("#track-player").children('source').attr('src', track.sound_url);
      $("#play-track-title").html(track_title);
      track_player.load();
      // 再生可能になったら自動再生
      track_player.addEventListener("loadeddata", ()=>{
        track_player.play();
      })

    }).fail(function(){
      alert('ajaxしっぱい');
    })
  })


  // +ボタンが押されたトラックをRoomトラックに追加
  $(".room-and-track-wrapper").on("click", ".track-add-icon", function(){
    var minus_icon = $(".track-remove-icon").first().attr('src');
    var img_url = $(this).siblings('.track-img-small').attr('src');
    var id = $(this).siblings('.track-id').text();
    var title = $(this).siblings('.track-title').text();

    var track_info_cover = $("<div></div>").attr(
      "class" , "track-info-cover"
    );
    var icon_and_index = $("<div></div>").attr(  // アイコンとインデックスを囲うdiv
      "class" , "icon-and-index"
    );
    var track_remove_icon = $("<img>").attr({ // マイナスアイコン
      "class" : 'track-remove-icon',
      "src" : minus_icon,
      alt : "-"
    });  
    var track_index = $("<span></span>").attr({ // トラックの順番
      "class" : "track-index",
    });

    icon_and_index
    .append(track_remove_icon)
    .append(track_index);

    var track_img_small = $("<img>").attr({ // サムネイメージ
      "class" : 'track-img-small',
      src : img_url,
      alt : ""
    });
    var track_id = $("<span></span>").attr({ // id
      "class" : "track-id",
      style : "display: none"
    }).html(id);
    var track_title = $("<span></span>").attr(  // トラックタイトル
      "class", "track-title"
    ).html(title);

    var room_track_info = $('<li></li>')
      .attr("class","track-info")
      .append(track_info_cover)
      .append(icon_and_index)
      // .append(track_remove_icon)
      // .append(track_index)
      .append(track_img_small)
      .append(track_id)
      .append(track_title);
      
      // Roomトラックのリスト要素を作成
      $('#room-track-list').append(room_track_info);

      // $('#room-track-list').load();
      reNumberingTrack();
  
  })

  // -ボタンが押されたトラックをRoomトラックから削除
  $(".room-and-track-wrapper").on("click", ".track-remove-icon", function(){
    $(this).parents('.track-info').remove();
    reNumberingTrack();
  })

  
  // Roomトラックの順番を更新する関数
  function reNumberingTrack(){
    var track_infos = $("#room-track-list").children('.track-info');
    track_infos.each(function(index, track_info){
      $(track_info).find('.track-index').first().html(index+1);
    })
  }

  $(".room-and-track-wrapper").on(
    "mouseover", ".track-info", function(){
    $(this).children(".track-info-cover").css({"opacity":0.6});
    $(this).find(".track-remove-icon").css({"opacity":1});
    $(this).find(".track-index").css({"display":"none"});
  }).on(
    "mouseout",  ".track-info", function(){
    $(this).children(".track-info-cover").css({"opacity":0});
    $(this).find(".track-remove-icon").css({"opacity":0});
    $(this).find(".track-index").css({"display":"inline"});
  });

  // fキーが押されたら全画面表示に
  $(window).keydown(function(e){

    switch(e.keyCode){
      case 70: // f
        $(".media-img").css({"width":"1080px", "height":"720px"});
        break;
      case 27: // ESC
        $(".media-img").css({"width":"100%", "height":"480px"});
        break;
    }

  })

})