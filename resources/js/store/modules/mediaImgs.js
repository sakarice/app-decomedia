const mediaImgs = {
  namespaced : true,

  state : {
    // DBから取得したデータで下記mediaImgが初期化がされたか。
    isInitialized : false,
    // メディア画像情報。show,editモードでは始めにDBのデータで初期化される。
    mediaImgs : [],
    // mediaImg : {
    //   type : 99,
    //   img_type : 0,
    //   groupNo : null,
    //   id : 0,
    //   url : "",
    //   top : 0,
    //   left : 0,
    //   width : 500,
    //   height : 500,
    //   degree : 0,
    //   opacity : 1,
    //   layer : 1,
    // },
    targetObjectIndex : 0,
  },
  getters : {
    getIsInitializedImg : function(state){ return state.isInitialized; },
    getMediaImgs : function(state){ return state.mediaImgs; },
    getMediaImg : function(state){ return state.mediaImgs[state.targetObjectIndex]; },
  },
  mutations : {
    deleteMediaImgsObjectItem(state, payload){
      state.mediaImgs.splice(payload,1) // Imgを1つ削除
    },
    addMediaImgsObjectItem(state, payload){
      state.mediaImgs.push(payload);
    },
    updateIsInitializedImgs(state,payload){state.isInitialized = payload},
    updateMediaImgsObjectItem(state, {index, key, value}){state.mediaImgs[index][key] = value;},
    setTargetObjectIndex(state, payload){ state.targetObjectIndex = payload },
  },
  actions : {}

}

export default mediaImgs;

