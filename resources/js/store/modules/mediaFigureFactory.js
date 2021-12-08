const mediaFigure = {
  namespaced : true,

  state : {
    // DBから取得したデータで下記mediaFigureが初期化がされたか。
    isInitialized : false,
    // メディア画像情報。show,editモードでは始めにDBのデータで初期化される。
    mediaFigure : {
      type : 0,
      id : 0,
      left : 100,
      top : 100,
      width : 50,
      height : 50,
      degree : 0,
      globalAlpha : 1,
      layer : 0,
      isDrawFill : false,
      fillColor : "black",
      isDrawStroke : true,
      strokeColor : "grey",
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

