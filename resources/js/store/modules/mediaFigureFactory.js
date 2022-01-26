const mediaFigure = {
  namespaced : true,

  state : {
    // DBから取得したデータで下記mediaFigureが初期化がされたか。
    isInitialized : false,
    // メディア画像情報。show,editモードでは始めにDBのデータで初期化される。
    mediaFigure : {
      media_id : 0,
      type : 1,
      groupNo : null,
      left : 100,
      top : 100,
      width : 50,
      height : 50,
      degree : 0,
      globalAlpha : 1,
      layer : 0,
      isDrawFill : true,
      fillColor : "#000000",
      isDrawStroke : true,
      strokeColor : "#000000",
    },
  },
  getters : {
    getIsInitializedFigure : function(state){ return state.isInitialized; },
    getFigureData : function(state){ return state.mediaFigure; },
  },
  mutations : {
    updateFigureData(state, {key, value}){state.mediaFigure[key] = value;},
    updateIsInitializedFigure(state,payload){state.isInitialized = payload},
  },
  actions : {}

}

export default mediaFigure;

