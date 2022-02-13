const mediaSetting = {
  namespaced : true,

  state : {
    // DBから取得したデータで下記mediaSettingが初期化がされたか。
    isInitialized : false,
    // メディア動画情報。show,editモードでは始めにDBのデータで初期化される。
    mediaSetting : {
      'id' : null,
      'isPublic' : true,  // 公開/非公開 デフォルトは公開
      'name' : "no title",
      'description' : "",
      'finish_time' : 0,
      'mediaBackgroundColor' : "#F2F2F2", // 若干グレー
      'isShowImg' : true,
      'isShowMovie' : false,
      'maxAudioNum' : 5,
    },
  },
  getters : {
    getIsInitializedSetting : function(state){ return state.isInitialized; },
    getMediaSetting : function(state){ return state.mediaSetting; },
  },
  mutations : {
    updateMediaSettingObjectItem(state, {key, value}){state.mediaSetting[key] = value;},
    updateIsInitializedSetting(state,payload){state.isInitialized = payload},
  },
  actions : {}

}

export default mediaSetting;

