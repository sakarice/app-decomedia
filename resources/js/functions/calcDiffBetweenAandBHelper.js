// 2点間の距離を計算し、結果をイベントのプロパティで通知する。

  let target_elem; // イベント通知先elem
  let base_x; // 基準点のx座標
  let base_y; // 基準点のy座標
  let diff_x;
  let diff_y;
  let isLimitMode;
  let distance_limit;

function setDistanceLimit(value){
  isLimitMode = true;
  distance_limit = value;
}

function offLimit(){
  isLimitMode = false;
}

function init(target, x, y){
  target_elem = target;
  base_x = x;
  base_y = y;
  diff_x = 0;
  diff_y = 0;
}

function getDiff(event){
  if(isLimitMode){
    const diff_x_next = event.clientX - base_x;
    const diff_y_next = event.clientY - base_y;
    const distance = diff_x_next**2 + diff_y_next**2;
    if(distance < distance_limit**2){
      return [diff_x_next, diff_y_next];
    } else {
      return [diff_x, diff_y];
    }
  }
}


// 位置操作用
function calcDiffStart(e,target,base_x,base_y){
  init(target,base_x,base_y);
  let event;
  if(e.type==="mousedown"){
    event = e;
    [diff_x, diff_y] = getDiff(event);
  } else {
    event = e.changedTouches[0];
    [diff_x, diff_y] = getDiff(event);
  }

  // ムーブイベントにコールバック
  document.body.addEventListener("mousemove", calcDiff, false);
  document.body.addEventListener("mouseup", calcDiffEnd, false);
  target_elem.addEventListener("mouseup", calcDiffEnd, false);
  document.body.addEventListener("touchmove", calcDiff, false);
  document.body.addEventListener("touchend", calcDiffEnd, false);
  target_elem.addEventListener("touchend", calcDiffEnd, false);
}


function calcDiff(e){
  e.preventDefault();
  let event;
  if(e.type==="mousemove"){
    event = e;
    [diff_x, diff_y] = getDiff(event);
    } else {
    event = e.changedTouches[0];
    [diff_x, diff_y] = getDiff(event);
  }

  const calc_event = new CustomEvent('calcDiffBetweenAandB',{detail:{diff_x:diff_x, diff_y:diff_y}})
  target_elem.dispatchEvent(calc_event);

  // マウス、タッチ解除時のイベントを設定
  document.body.addEventListener("mouseleave", calcDiffEnd, false);
  document.body.addEventListener("touchleave", calcDiffEnd, false);
}



function calcDiffEnd(e){
  document.body.removeEventListener("mousemove", calcDiff, false);
  document.body.removeEventListener("mouseleave", calcDiffEnd, false);
  document.body.removeEventListener("touchmove", calcDiff, false);
  document.body.removeEventListener("touchleave", calcDiffEnd, false);
  document.body.removeEventListener("mouseup", calcDiffEnd, false);
  document.body.removeEventListener("touchend", calcDiffEnd, false);

  target_elem.removeEventListener("mouseup", calcDiffEnd, false);
  target_elem.removeEventListener("touchend", calcDiffEnd, false);
  
  // 距離計算終了カスタムイベント。
  const finish_event = new CustomEvent('calcDiffFinish',{detail:{diff_x:diff_x, diff_y:diff_y}})
  target_elem.dispatchEvent(finish_event);
}

export {setDistanceLimit,offLimit,calcDiffStart};

