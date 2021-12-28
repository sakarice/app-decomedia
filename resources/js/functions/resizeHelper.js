// オブジェクトのリサイズを行う処理群
// リサイズ開始処理(resizeStart関数)が、
// コンポーネントからイベント、リサイズ対象DOM、初期サイズと位置を受け取り、
// 移動終了後にカスタムイベントを発行し引数にサイズ(width,height)と位置(left,top)を渡す

  let resize_target;
  let initial_left;
  let initial_top;
  let initial_width;
  let initial_height;
  let contents_area_left;
  let contents_area_top;

// 0. リサイズ情報初期化
function resizeInfoInit(target,sizeAndPositionInfos){
  resize_target = target;
  initial_width = sizeAndPositionInfos.width;
  initial_height = sizeAndPositionInfos.height;
  initial_left = sizeAndPositionInfos.left;
  initial_top = sizeAndPositionInfos.top;
  const contents_area = document.getElementById('media-contents-field');
  contents_area_left = contents_area.getBoundingClientRect().left;
  contents_area_top = contents_area.getBoundingClientRect().top;
}


function registEventStartToEnd(){
  document.body.addEventListener("mouseup", resizeEnd, false);
  document.body.addEventListener("touchend", resizeEnd, false);
}

// 1. リサイズ開始メソッド
function resizeStart(type){
  registEventStartToEnd();
  switch(type){
    case "right":
      resizeRightStart();
      break;
    case "left":
      resizeLeftStart();
      break;
    case "bottom":
      resizeBottomStart();
      break;
    case "top":
      resizeTopStart();
      break;
  }
}

function resizeRightStart(e){
  document.body.addEventListener("mousemove", resizeRight, false);
  document.body.addEventListener("touchmove", resizeRight, false);
}
function resizeLeftStart(e){
  document.body.addEventListener("mousemove", resizeLeft, false);
  document.body.addEventListener("touchmove", resizeLeft, false);
}
function resizeBottomStart(e){
  document.body.addEventListener("mousemove", resizeBottom, false);
  document.body.addEventListener("touchmove", resizeBottom, false);
}
function resizeTopStart(e){
  document.body.addEventListener("mousemove", resizeTop, false);
  document.body.addEventListener("touchmove", resizeTop, false);
}

// マウスポインターかタッチ箇所の座標を取得する
function getPointerX(event){
  if(event.type==="mousemove"){ // マウス操作
    return event.clientX;
  } else { // タッチ操作
    return event.changedTouches[0].pageX;
  }
}

function getPointerY(event){
  if(event.type==="mousemove"){ // マウス操作
    return event.clientY;
  } else { // タッチ操作
    return event.changedTouches[0].pageY;
  }
}

// 2. リサイズ中メソッド
function resizeRight(e){
  const new_width = Math.floor(getPointerX(e) - (contents_area_left + initial_left));
  const resizing_width_event = new CustomEvent('resizingWidth', {detail:{width:new_width,left:initial_left}});
  resize_target.dispatchEvent(resizing_width_event);
  registEventMiddleToEnd();
}
function resizeLeft(e){
  const diff = (contents_area_left + initial_left) - getPointerX(e);
  const new_width = Math.floor(initial_width + diff);
  const new_left = Math.floor(initial_left - diff);
  const resizing_width_event = new CustomEvent('resizingWidth', {detail:{width:new_width,left:new_left}});
  resize_target.dispatchEvent(resizing_width_event);
  registEventMiddleToEnd();
}
function resizeBottom(e){
  const new_height = Math.floor(getPointerY(e) - (contents_area_top + initial_top));
  const resizing_height_event = new CustomEvent('resizingHeight', {detail:{height:new_height,top:initial_top}});
  resize_target.dispatchEvent(resizing_height_event);
  registEventMiddleToEnd();
}
function resizeTop(e){
  const diff = (contents_area_top + initial_top) - getPointerY(e);
  const new_height = Math.floor(initial_height + diff);
  const new_top = Math.floor(initial_top - diff);
  const resizing_height_event = new CustomEvent('resizingHeight', {detail:{height:new_height,top:new_top}});
  resize_target.dispatchEvent(resizing_height_event);  
  registEventMiddleToEnd();
}
// リサイズ中メソッドからフックする終了イベントの登録
function registEventMiddleToEnd(){ // マウス、タッチ解除時のイベントを設定
  document.body.addEventListener("mouseleave", resizeEnd, false);
  document.body.addEventListener("touchleave", resizeEnd, false);
}


// 3. リサイズ終了メソッド。登録したイベントを解除する。
function resizeEnd(e){
  document.body.removeEventListener("mousemove", resizeRight, false);
  document.body.removeEventListener("mousemove", resizeLeft, false);
  document.body.removeEventListener("mousemove", resizeBottom, false);
  document.body.removeEventListener("mousemove", resizeTop, false);
  document.body.removeEventListener("touchmove", resizeRight, false);
  document.body.removeEventListener("touchmove", resizeLeft, false);
  document.body.removeEventListener("touchmove", resizeBottom, false);
  document.body.removeEventListener("touchmove", resizeTop, false);

  document.body.removeEventListener("mouseup", resizeEnd, false);
  document.body.removeEventListener("touchend", resizeEnd, false);
  resize_target.removeEventListener("mouseup", resizeEnd, false);
  resize_target.removeEventListener("touchend", resizeEnd, false);
}

// exportは、移動のトリガーとなるメソッドのみ
export {resizeInfoInit,resizeStart};

