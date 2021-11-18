const mediaSetting = {
  namespaced : true,

  state : {
    mediaSetting : {
      'id' : null,
      'isPublic' : true,  // 公開/非公開 デフォルトは公開
      'name' : "",
      'description' : "",
      'finish_time' : 0,
      'mediaBackgroundColor' : "#F7F7F7", // ほぼ白
      'isShowImg' : true,
      'isShowMovie' : false,
      'maxAudioNum' : 5,
    },
  },
  getters : {
    getMediaSetting : function(state){ return state.mediaSetting; },
  },
  mutations : {
    updateMediaSettingObjectItem(state, {key, value}){state.mediaSetting[key] = value;},
  },
  actions : {}

}

export default mediaSetting;

