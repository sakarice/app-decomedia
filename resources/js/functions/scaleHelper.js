// オブジェクトの拡大・縮小を行う処理群
// 拡大・縮小開始処理(scaleStart関数)が、
// コンポーネントからイベント、拡大・縮小対象DOM、初期サイズと位置を受け取り、
// 移動終了後にカスタムイベントを発行し引数にサイズ(width,height)と位置(left,top)を渡す

  let scale_target;
  let left;
  let top;
  let width;
  let height;

function targetInit(target){
  scale_target = target;
}
// 0. 拡大・縮小情報初期化
function scaleInfoInit(sizeInfo){
  left = scale_target.offsetLeft;
  top = scale_target.offsetTop;
  width = sizeInfo.width;
  height = sizeInfo.height;
}


function registEventStartToEnd(){
  document.body.addEventListener("mouseup", scaleEnd, false);
  document.body.addEventListener("touchend", scaleEnd, false);
}

// 1. 拡大・縮小開始メソッド
function scaleStart(type){

  registEventStartToEnd();
  switch(type){
    case "right":
      scaleRightStart();
      break;
    case "left":
      scaleLeftStart();
      break;
    case "bottom":
      scaleBottomStart();
      break;
    case "top":
      scaleTopStart();
      break;
  }
}

function scaleRightStart(e){
  // document.body.addEventListener("mousemove", scaleRight, false);
  // document.body.addEventListener("touchmove", scaleRight, false);
  document.body.addEventListener("mousemove", scaleX, false);
  document.body.addEventListener("touchmove", scaleX, false);
}
function scaleLeftStart(e){
  // document.body.addEventListener("mousemove", scaleLeft, false);
  // document.body.addEventListener("touchmove", scaleLeft, false);
  document.body.addEventListener("mousemove", scaleX, false);
  document.body.addEventListener("touchmove", scaleX, false);
}
function scaleBottomStart(e){
  // document.body.addEventListener("mousemove", scaleBottom, false);
  // document.body.addEventListener("touchmove", scaleBottom, false);
  document.body.addEventListener("mousemove", scaleY, false);
  document.body.addEventListener("touchmove", scaleY, false);
}
function scaleTopStart(e){
  // document.body.addEventListener("mousemove", scaleTop, false);
  // document.body.addEventListener("touchmove", scaleTop, false);
  document.body.addEventListener("mousemove", scaleY, false);
  document.body.addEventListener("touchmove", scaleY, false);
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


function scaleX(e){
  const diff = getPointerX(e) - left;
  const scale_x_event = new CustomEvent('scaleX', {detail:{diff:diff}});
  scale_target.dispatchEvent(scale_x_event);
  registEventMiddleToEnd();
}
function scaleY(e){
  const diff = getPointerY(e) - top;
  const scale_y_event = new CustomEvent('scaleY', {detail:{diff:diff}});
  scale_target.dispatchEvent(scale_y_event);
  registEventMiddleToEnd();
}



// 2. 拡大・縮小中メソッド
function scaleRight(e){
  // scale_target.style.transformOrigin = "0 0";
  // scaleInfoInit();
  const new_width = Math.floor(getPointerX(e) - left);
  const scale_right_event = new CustomEvent('scale', {detail:{width:new_width}});
  scale_target.dispatchEvent(scale_right_event);
  registEventMiddleToEnd();
}
function scaleLeft(e){
  // scale_target.style.transformOrigin = "100% 0";
  // scaleInfoInit();
  const new_left = getPointerX(e);
  const diff = left - new_left;
  const new_width = Math.floor(width + diff);
  const scale_left_event = new CustomEvent('scale', {detail:{width:new_width,left:new_left}});
  scale_target.dispatchEvent(scale_left_event);
  registEventMiddleToEnd();
}
function scaleBottom(e){
  // scale_target.style.transformOrigin = "0 0";
  // scaleInfoInit();
  const new_height = Math.floor(getPointerY(e) - top);
  const scale_bottom_event = new CustomEvent('scale', {detail:{height:new_height}});
  scale_target.dispatchEvent(scale_bottom_event);
  registEventMiddleToEnd();
}
function scaleTop(e){
  // scale_target.style.transformOrigin = "0 100%";
  // scaleInfoInit();
  const new_top = getPointerY(e);
  const diff = top - new_top;
  const new_height = Math.floor(height + diff);
  const scale_top_event = new CustomEvent('scale', {detail:{height:new_height,top:new_top}});
  scale_target.dispatchEvent(scale_top_event);
  registEventMiddleToEnd();
}
// 拡大・縮小中メソッドからフックする終了イベントの登録
function registEventMiddleToEnd(){ // マウス、タッチ解除時のイベントを設定
  document.body.addEventListener("mouseleave", scaleEnd, false);
  document.body.addEventListener("touchleave", scaleEnd, false);
}


// 3. 拡大・縮小終了メソッド。登録したイベントを解除する。
function scaleEnd(e){
  document.body.removeEventListener("mousemove", scaleRight, false);
  document.body.removeEventListener("mousemove", scaleLeft, false);
  document.body.removeEventListener("mousemove", scaleBottom, false);
  document.body.removeEventListener("mousemove", scaleTop, false);
  document.body.removeEventListener("touchmove", scaleRight, false);
  document.body.removeEventListener("touchmove", scaleLeft, false);
  document.body.removeEventListener("touchmove", scaleBottom, false);
  document.body.removeEventListener("touchmove", scaleTop, false);

  document.body.removeEventListener("mouseup", scaleEnd, false);
  document.body.removeEventListener("touchend", scaleEnd, false);
  scale_target.removeEventListener("mouseup", scaleEnd, false);
  scale_target.removeEventListener("touchend", scaleEnd, false);
}

// exportは、移動のトリガーとなるメソッドのみ
export {targetInit,scaleInfoInit,scaleStart};

