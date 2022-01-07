const mediaTexts = {
  namespaced : true,

  state : {
    // DBから取得したデータで下記mediaTextが初期化がされたか。
    isInitialized : false,
    // メディアテキスト情報。show,editモードでは始めにDBのデータで初期化される。
    mediaTexts : [],
    // mediaText : {
    //   type : 99,
    //   groupNo : null,
    //   id : 0,
    //   text : "",
    //   top : 0,
    //   left : 0,
    //   width : 500,
    //   height : 500,
    //   scale_x : 1,
    //   scale_y : 1,
    //   degree : 0,
    //   opacity : 1,
    //   layer : 1,
    // },
    targetObjectIndex : 0,
  },
  getters : {
    getIsInitializedText : function(state){ return state.isInitialized; },
    getMediaTexts : function(state){ return state.mediaTexts; },
    getMediaText : function(state){ return state.mediaTexts[state.targetObjectIndex]; },
  },
  mutations : {
    deleteMediaTextsObjectItem(state, payload){
      state.mediaTexts.splice(payload,1) // Textを1つ削除
    },
    addMediaTextsObjectItem(state, payload){
      state.mediaTexts.push(payload);
    },
    updateIsInitializedTexts(state,payload){state.isInitialized = payload},
    updateMediaTextsObjectItem(state, {index, key, value}){state.mediaTexts[index][key] = value;},
    setTargetObjectIndex(state, payload){ state.targetObjectIndex = payload },
  },
  actions : {}

}

export default mediaTexts;

