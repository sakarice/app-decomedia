const mediaText = {
  namespaced : true,

  state : {
    // DBから取得したデータで下記mediaTextが初期化がされたか。
    isInitialized : false,
    // メディア画像情報。show,editモードでは始めにDBのデータで初期化される。
    mediaText : {
      type : 90,
      groupNo : null,
      id : 0,
      text : "textTextテキスト",
      top : 100,
      left : 100,
      width : 100,
      height : 100,
      scale_x : 1,
      scale_y : 1,
      degree : 0,
      opacity : 1,
      layer : 1,
    },
  },
  getters : {
    getIsInitializedText : function(state){ return state.isInitialized; },
    getTextData : function(state){ return state.mediaText; },
  },
  mutations : {
    updateTextData(state, {key, value}){state.mediaText[key] = value;},
    updateIsInitializedText(state,payload){state.isInitialized = payload},
  },
  actions : {}

}

export default mediaText;

