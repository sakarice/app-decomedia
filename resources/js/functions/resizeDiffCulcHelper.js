// オブジェクトの拡大・縮小を行う処理群
// 拡大・縮小開始処理(resizeStart関数)が、
// コンポーネントからイベント、拡大・縮小対象DOM、初期サイズと位置を受け取り、
// 移動終了後にカスタムイベントを発行し引数にサイズ(width,height)と位置(left,top)を渡す

  let resize_target;
  let resize_start_infos = {
    "left" : 0,
    "top" : 0,
    "width" : 0,
    "height" : 0,
  }
  
  let resize_base_point = {"x":0, "y":0};
  let resize_side_vector;

function resizeTargetInit(target){
  resize_target = target;
}
function resizeBasePointInit(event){
  let x,y;
  if(event.type==="mousedown"){ // マウス操作
    x =  event.clientX;
    y =  event.clientY;
  } else { // タッチ操作
    x = event.changedTouches[0].pageX;
    y = event.changedTouches[0].pageY;
  }
  resize_base_point["x"] = x;
  resize_base_point["y"] = y;
}

function resizeStartInfosInit(){
  resize_start_infos["left"] = Number(resize_target.style.left.replace("px",""));
  resize_start_infos["top"] = Number(resize_target.style.top.replace("px",""));
  resize_start_infos["width"] = Number(resize_target.style.width.replace("px",""));
  resize_start_infos["height"] = Number(resize_target.style.height.replace("px",""));
}

function setResizeSideVector(resize_side_text){
// UI上で操作しているスケール用の辺or頂点をベクトルで表現
// <例>
// leftBottom ... ↙  =  ←↓  =  {"x":-1, "y":1}
  switch(resize_side_text){
    // 縦横(X or Y)
    case "right":
      resize_side_vector = {"x":1, "y":0};
      break;
    case "left":
      resize_side_vector = {"x":-1, "y":0};
      break;
    case "bottom":
      resize_side_vector = {"x":0, "y":1};
      break;
    case "top":
      resize_side_vector = {"x":0, "y":-1};
      break;

    // 斜め(X and Y)
    case "rightBottom":
      resize_side_vector = {"x":1, "y":1};
      break;
    case "rightTop":
      resize_side_vector = {"x":1, "y":-1};
      break;
    case "leftBottom":
      resize_side_vector = {"x":-1, "y":1};
      break;
    case "leftTop":
      resize_side_vector = {"x":-1, "y":-1};
      break;
    }
}


function registEventStartToEnd(){
  document.body.addEventListener("mouseup", resizeEnd, false);
  document.body.addEventListener("touchend", resizeEnd, false);
}

// 1. 拡大・縮小開始メソッド
function resizeStart(type){
  registEventStartToEnd();
  resizeStartInfosInit();
  setResizeSideVector(type);
  const move_x = resize_side_vector['x'];
  const move_y = resize_side_vector['y'];
  if(move_x==0 || move_y==0){
    resizeXorYStart(); // 縦横のリサイズ
  } else {
    resizeXandYStart(); // 斜め方向のリサイズ
  }
}

// 縦横
function resizeXorYStart(){
  document.body.addEventListener("mousemove", resizeXorY, false);
  document.body.addEventListener("touchmove", resizeXorY, false);
}
// 斜め
function resizeXandYStart(){
  document.body.addEventListener("mousemove", resizeXandY, false);
  document.body.addEventListener("touchmove", resizeXandY, false);
}


// マウスポインターかタッチ箇所の座標を取得する
function getPointer_X(event){
  if(event.type==="mousemove"){ // マウス操作
    return event.clientX;
  } else { // タッチ操作
    return event.changedTouches[0].pageX;
  }
}

function getPointer_Y(event){
  if(event.type==="mousemove"){ // マウス操作
    return event.clientY;
  } else { // タッチ操作
    return event.changedTouches[0].pageY;
  }
}

function getPointerVector(event){
  const pointer_vector = {
    "x":getPointer_X(event) - resize_base_point["x"],
    "y":getPointer_Y(event) - resize_base_point["y"]
  }
  return pointer_vector;  
}

function getDirectionVector(){
  const width = resize_start_infos["width"];
  const height = resize_start_infos["height"];
  let ratio_x,ratio_y;
  if(width >= height){
    ratio_x = 1;
    ratio_y = height / width;
  } else {
    ratio_x = width / height;
    ratio_y = 1;
  }
  const direction_x = ratio_x * resize_side_vector["x"];
  const direction_y = ratio_y * resize_side_vector["y"];
  const direction_verctor = {
    "x" : direction_x,
    "y" : direction_y,
  }
  return direction_verctor;
}


// 縦横方向のリサイズ
function resizeXorY(e){
  const vector_x = resize_side_vector['x'];
  const vector_y = resize_side_vector['y'];
  const diff_x = (getPointer_X(e) - resize_base_point["x"]) * vector_x;
  const diff_y = (getPointer_Y(e) - resize_base_point["y"]) * vector_y;
  const resize_event = new CustomEvent('resize', {
    detail:{
      resize_side : resize_side_vector,
      resize_start_infos : resize_start_infos,
      diff_x : diff_x,
      diff_y : diff_y
    }
  });
  resize_target.dispatchEvent(resize_event);
  registEventMiddleToEnd();
}

function resizeXandY(e){
  // 角度をベクトル形式で求める
  const pointer_vector = getPointerVector(e);
  const direction_verctor = getDirectionVector();
  const distance_along_with_resize_direction = calcDistanceAlongWithDirectionVector(direction_verctor, pointer_vector)
  const direction_radian = Math.atan2(direction_verctor["y"],direction_verctor["x"]);
  const diff_x = distance_along_with_resize_direction * Math.abs(Math.cos(direction_radian));
  const diff_y = distance_along_with_resize_direction * Math.abs(Math.sin(direction_radian));
  const resize_event = new CustomEvent('resize', {
    detail:{
      resize_side : resize_side_vector,
      resize_start_infos : resize_start_infos,
      diff_x : diff_x,
      diff_y : diff_y,
    }
  });
  resize_target.dispatchEvent(resize_event);
  registEventMiddleToEnd();
}

function calcDistanceAlongWithDirectionVector(base_vector, calc_target_vector){
  const v1 = base_vector;
  const v2 = calc_target_vector;
  const upper = v1["x"]*v2["x"] + v1["y"]*v2["y"];
  const bottom = Math.sqrt(v1["x"]**2 + v1["y"]**2) * Math.sqrt(v2["x"]**2 + v2["y"]**2);
  const cos = upper / bottom;
  const distance_from_base = Math.sqrt(v2["x"]**2 + v2["y"]**2);
  const distance_along_with_direction = distance_from_base * cos;
  return distance_along_with_direction;
}


// 拡大・縮小中メソッドからフックする終了イベントの登録
function registEventMiddleToEnd(){ // マウス、タッチ解除時のイベントを設定
  document.body.addEventListener("mouseleave", resizeEnd, false);
  document.body.addEventListener("touchleave", resizeEnd, false);
}


// 3. 拡大・縮小終了メソッド。登録したイベントを解除する。
function resizeEnd(e){
  document.body.removeEventListener("mousemove", resizeXorY, false);
  document.body.removeEventListener("touchmove", resizeXorY, false);
  document.body.removeEventListener("mousemove", resizeXandY, false);
  document.body.removeEventListener("touchmove", resizeXandY, false);

  document.body.removeEventListener("mouseup", resizeEnd, false);
  document.body.removeEventListener("touchend", resizeEnd, false);
  resize_target.removeEventListener("mouseup", resizeEnd, false);
  resize_target.removeEventListener("touchend", resizeEnd, false);
  const resizeEndEvent = new CustomEvent('resizeEnd');
  resize_target.dispatchEvent(resizeEndEvent);
}

// exportは、移動のトリガーとなるメソッドのみ
export {resizeTargetInit,resizeBasePointInit,resizeStart};

