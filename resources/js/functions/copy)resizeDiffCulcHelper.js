// オブジェクトの拡大・縮小を行う処理群
// 拡大・縮小開始処理(resizeStart関数)が、
// コンポーネントからイベント、拡大・縮小対象DOM、初期サイズと位置を受け取り、
// 移動終了後にカスタムイベントを発行し引数にサイズ(width,height)と位置(left,top)を渡す

  let resize_target;
  let left;
  let top;
  let width;
  let height;

function targetInit(target){
  resize_target = target;
}
// 0. 拡大・縮小情報初期化
function resizeInfoInit(sizeInfo){
  left = resize_target.getBoundingClientRect().left + window.pageXOffset;
  top = resize_target.getBoundingClientRect().top + window.pageYOffset;
  width = sizeInfo.width;
  height = sizeInfo.height;
}


function registEventStartToEnd(){
  document.body.addEventListener("mouseup", resizeEnd, false);
  document.body.addEventListener("touchend", resizeEnd, false);
}

// 1. 拡大・縮小開始メソッド
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
  // document.body.addEventListener("mousemove", resizeX, false);
  // document.body.addEventListener("touchmove", resizeX, false);
}
function resizeLeftStart(e){
  document.body.addEventListener("mousemove", resizeLeft, false);
  document.body.addEventListener("touchmove", resizeLeft, false);
  // document.body.addEventListener("mousemove", resizeX, false);
  // document.body.addEventListener("touchmove", resizeX, false);
}
function resizeBottomStart(e){
  document.body.addEventListener("mousemove", resizeBottom, false);
  document.body.addEventListener("touchmove", resizeBottom, false);
  // document.body.addEventListener("mousemove", resizeY, false);
  // document.body.addEventListener("touchmove", resizeY, false);
}
function resizeTopStart(e){
  document.body.addEventListener("mousemove", resizeTop, false);
  document.body.addEventListener("touchmove", resizeTop, false);
  // document.body.addEventListener("mousemove", resizeY, false);
  // document.body.addEventListener("touchmove", resizeY, false);
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



// 2. 拡大・縮小中メソッド
// X軸方向の拡大・縮小
function resizeRight(e){
  const diff = Math.floor(getPointerX(e) - (left+width));
  console.log(diff);
  setScaleXEvent(diff,"right");
  width = width + diff;
}
function resizeLeft(e){
  const diff = Math.floor(left - getPointerX(e));
  setScaleXEvent(diff,"left");
  width = Math.floor(width + diff);
  left = Math.floor(left - diff);
}
function setScaleXEvent(diff,direction){
  const resize_x_event = new CustomEvent('resizeX', {detail:{diff:diff,direction:direction}});
  resize_target.dispatchEvent(resize_x_event);
  registEventMiddleToEnd();
}

// y軸方向の拡大・縮小
function resizeBottom(e){
  const diff = Math.floor(getPointerY(e) - (top+height));
  console.log(diff);
  setScaleYEvent(diff,"bottom");
  height = height + diff;
}
function resizeTop(e){
  const diff = Math.floor(top - getPointerY(e));
  console.log(diff);
  setScaleYEvent(diff,"top");
  height = height + diff;
  top = Math.floor(top - diff);
}
function setScaleYEvent(diff,direction){
  const resize_y_event = new CustomEvent('resizeY', {detail:{diff:diff,direction:direction}});
  resize_target.dispatchEvent(resize_y_event);
  registEventMiddleToEnd();
}



// 拡大・縮小中メソッドからフックする終了イベントの登録
function registEventMiddleToEnd(){ // マウス、タッチ解除時のイベントを設定
  document.body.addEventListener("mouseleave", resizeEnd, false);
  document.body.addEventListener("touchleave", resizeEnd, false);
}


// 3. 拡大・縮小終了メソッド。登録したイベントを解除する。
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
export {targetInit,resizeInfoInit,resizeStart};

