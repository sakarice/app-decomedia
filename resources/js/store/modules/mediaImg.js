const mediaImg = {
  namespaced : true,

  state : {
    // DBから取得したデータで下記mediaImgが初期化がされたか。
    isInitialized : false,
    // メディア画像情報。show,editモードでは始めにDBのデータで初期化される。
    mediaImg : {
      type : 0,
      id : 0,
      url : "",
      width : 500,
      height : 500,
      opacity : 1,
      layer : 0,
    },
  },
  getters : {
    getIsInitializedImg : function(state){ return state.isInitialized; },
    getMediaImg : function(state){ return state.mediaImg; },
  },
  mutations : {
    updateMediaImgContent(state, {type,id,url}){
      state.mediaImg['type'] = type;
      state.mediaImg['id'] = id;
      state.mediaImg['url'] = url;
    },
    updateMediaImgObjectItem(state, {key, value}){state.mediaImg[key] = value;},
    updateIsInitializedImg(state,payload){state.isInitialized = payload},
  },
  actions : {}

}

export default mediaImg;

