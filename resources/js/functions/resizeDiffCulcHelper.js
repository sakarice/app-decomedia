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
  left = scale_target.getBoundingClientRect().left + window.pageXOffset;
  top = scale_target.getBoundingClientRect().top + window.pageYOffset;
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
  document.body.addEventListener("mousemove", scaleRight, false);
  document.body.addEventListener("touchmove", scaleRight, false);
  // document.body.addEventListener("mousemove", scaleX, false);
  // document.body.addEventListener("touchmove", scaleX, false);
}
function scaleLeftStart(e){
  document.body.addEventListener("mousemove", scaleLeft, false);
  document.body.addEventListener("touchmove", scaleLeft, false);
  // document.body.addEventListener("mousemove", scaleX, false);
  // document.body.addEventListener("touchmove", scaleX, false);
}
function scaleBottomStart(e){
  document.body.addEventListener("mousemove", scaleBottom, false);
  document.body.addEventListener("touchmove", scaleBottom, false);
  // document.body.addEventListener("mousemove", scaleY, false);
  // document.body.addEventListener("touchmove", scaleY, false);
}
function scaleTopStart(e){
  document.body.addEventListener("mousemove", scaleTop, false);
  document.body.addEventListener("touchmove", scaleTop, false);
  // document.body.addEventListener("mousemove", scaleY, false);
  // document.body.addEventListener("touchmove", scaleY, false);
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
function scaleRight(e){
  const diff = Math.floor(getPointerX(e) - (left+width));
  console.log(diff);
  setScaleXEvent(diff,"right");
  width = width + diff;
}
function scaleLeft(e){
  // console.log('scacleLeft left:');
  // console.log(left);
  const diff = Math.floor(left - getPointerX(e));
  // console.log(diff);
  setScaleXEvent(diff,"left");
  width = Math.floor(width + diff);
  left = Math.floor(left - diff);
  // console.log('scacleLeft left:');
  // console.log(left);

}
function setScaleXEvent(diff,direction){
  const scale_x_event = new CustomEvent('scaleX', {detail:{diff:diff,direction:direction}});
  scale_target.dispatchEvent(scale_x_event);
  registEventMiddleToEnd();
}

// y軸方向の拡大・縮小
function scaleBottom(e){
  const diff = Math.floor(getPointerY(e) - (top+height));
  console.log(diff);
  setScaleYEvent(diff,"bottom");
  height = height + diff;
}
function scaleTop(e){
  const diff = Math.floor(top - getPointerY(e));
  console.log(diff);
  setScaleYEvent(diff,"top");
  height = height + diff;
  top = Math.floor(top - diff);
}
function setScaleYEvent(diff,direction){
  const scale_y_event = new CustomEvent('scaleY', {detail:{diff:diff,direction:direction}});
  scale_target.dispatchEvent(scale_y_event);
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

