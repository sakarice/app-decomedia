const mediaFigure = {
  namespaced : true,

  state : {
    // DBから取得したデータで下記mediaFigureが初期化がされたか。
    isInitialized : false,
    // メディア画像情報。show,editモードでは始めにDBのデータで初期化される。
    mediaFigure : {
      type : 0,
      id : 0,
      width : 50,
      height : 50,
      opacity : 1,
      layer : 0,
      isFill : true,
      fillColer : "red",
      isRect : true,
      rectColor : "blue",
    },
  },
  getters : {
    getIsInitializedFigure : function(state){ return state.isInitialized; },
    getMediaFigure : function(state){ return state.mediaFigure; },
  },
  mutations : {
    updateMediaFigureObjectItem(state, {key, value}){state.mediaFigure[key] = value;},
    updateIsInitializedFigure(state,payload){state.isInitialized = payload},
  },
  actions : {}

}

export default mediaFigure;

