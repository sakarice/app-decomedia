
// 動かない、衝突される方のオブジェクト情報
  let base_obj_center = {"x":0,"y":0}; // 基準点のx,y座標
  let base_obj_r = 0;

// 動かない方のオブジェクト情報初期化
function setBaseObjInfo(center_x, center_y, r){
  base_obj_center["x"] = center_x;
  base_obj_center["y"] = center_y;
  base_obj_r = r;
}

// 2点間の距離を計算
function calcDiff(point_a, point_b){
  const diff_x = point_a["x"] - point_b["x"];
  const diff_y = point_a["y"] - point_b["y"];
  const diff = Math.sqrt(diff_x*diff_x + diff_y*diff_y);
  return diff;
}

// 2つの円系オブジェクトの衝突判定
function judgeCollide(move_target_center, move_target_r){
  const diff = calcDiff(base_obj_center, move_target_center);
  const sum_r = base_obj_r + move_target_r;
  const isCollide = sum_r >= diff ? true : false;
  return isCollide
}


export {setBaseObjInfo, judgeCollide};

