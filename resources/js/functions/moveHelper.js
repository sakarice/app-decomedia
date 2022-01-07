// オブジェクトの移動を行う処理群
// 移動開始処理(moveStart関数)が、コンポーネントからイベントと移動対象DOMを受け取り、
// そのDOMをドラッグ/タッチスライドで移動。
// 移動終了後にカスタムイベントを発行し引数に位置(left,top)を渡す

  let move_target;
  let left;
  let top;
  let distance_from_target_left;
  let distance_from_target_top;

function init(target){
  move_target = target;
  left = Number(target.style.left.replace("px",""));
  top = Number(target.style.top.replace("px",""));
  distance_from_target_left = 0;
  distance_from_target_top = 0;
}

// 位置操作用
function moveStart(e,target){
  init(target);
  let event;
  if(e.type==="mousedown"){
    event = e;
    distance_from_target_left = event.clientX - move_target.offsetLeft;
    distance_from_target_top = event.clientY - move_target.offsetTop;
  } else {
    event = e.changedTouches[0];
    distance_from_target_left = event.pageX - move_target.offsetLeft;
    distance_from_target_top = event.pageY - move_target.offsetTop;
  }

  // ムーブイベントにコールバック
  document.body.addEventListener("mousemove", moving, false);
  document.body.addEventListener("mouseup", moveEnd, false);
  move_target.addEventListener("mouseup", moveEnd, false);
  document.body.addEventListener("touchmove", moving, false);
  document.body.addEventListener("touchend", moveEnd, false);
  move_target.addEventListener("touchend", moveEnd, false);
}

function moving(e){
  e.preventDefault();
  let event;
  if(e.type==="mousemove"){
    event = e;
    left = event.clientX - distance_from_target_left;
    top = event.clientY - distance_from_target_top;
    } else {
    event = e.changedTouches[0];
    left = event.pageX - distance_from_target_left;
    top = event.pageY - distance_from_target_top;
  }
  move_target.style.left = left + "px";
  move_target.style.top = top + "px";

  const moving_event = new CustomEvent('moving',{detail:{left:left, top:top}})
  move_target.dispatchEvent(moving_event);

  // マウス、タッチ解除時のイベントを設定
  document.body.addEventListener("mouseleave", moveEnd, false);
  document.body.addEventListener("touchleave", moveEnd, false);
}

function moveEnd(e){
  document.body.removeEventListener("mousemove", moving, false);
  document.body.removeEventListener("mouseleave", moveEnd, false);
  document.body.removeEventListener("touchmove", moving, false);
  document.body.removeEventListener("touchleave", moveEnd, false);
  document.body.removeEventListener("mouseup", moveEnd, false);
  document.body.removeEventListener("touchend", moveEnd, false);

  move_target.removeEventListener("mouseup", moveEnd, false);
  move_target.removeEventListener("touchend", moveEnd, false);
  
  // 移動終了カスタムイベント。終了後の位置を引数で渡す。
  const event = new CustomEvent('moveFinish',{detail:{left:left, top:top}})
  move_target.dispatchEvent(event);
}

// exportは、移動のトリガーとなるmoveStartのみ
export {moveStart};

