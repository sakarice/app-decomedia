const mediaFigure = {
  namespaced : true,

  state : {
    // DBから取得したデータで下記mediaFigureが初期化がされたか。
    isInitialized : false,
    // メディア画像情報。show,editモードでは始めにDBのデータで初期化される。
    mediaFigures : [],
      // media_id : null,
      // type : 1,
      // groupNo : null,
      // left : 100,
      // top : 100,
      // width : 50,
      // height : 50,
      // degree : 0,
      // globalAlpha : 1,
      // layer : 0,
      // isDrawFill : false,
      // fillColor : "black",
      // isDrawStroke : true,
      // strokeColor : "grey",
    targetObjectIndex : 0,
  },
  getters : {
    getIsInitializedFigures : function(state){ return state.isInitialized; },
    getMediaFigures : function(state){ return state.mediaFigures; },
    getMediaFigure : function(state){ return state.mediaFigures[state.targetObjectIndex]; },
  },
  mutations : {
    deleteMediaFiguresObjectItem(state, payload){
      state.mediaFigures.splice(payload,1) // figureを1つ削除
    },
    addMediaFiguresObjectItem(state, payload){
      state.mediaFigures.push(payload);
    },
    updateIsInitializedFigures(state,payload){state.isInitialized = payload},
    updateMediaFiguresObjectItem(state, {index, key, value}){state.mediaFigures[index][key] = value },
    setTargetObjectIndex(state, payload){ state.targetObjectIndex = payload },
  },
  actions : {}

}

export default mediaFigure;

