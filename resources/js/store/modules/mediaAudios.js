const mediaAudios = {
  namespaced : true,

  state : {
    // DBから取得したデータで下記mediaAudiosが初期化がされたか。
    isInitialized : false,
    // メディアオーディオ情報。show,editモードでは始めにDBのデータで初期化される。
    mediaAudios : [],
    // mediaAudio : {
    //   'media_id' : null,
    //   'audio_type' : 1,
    //   'audio_id' : null,
    //   'order_seq' : 1,
    //   'name' : "",
    //   'audio_url' : "",
    //   'thumbnail_url' : "",
    //   'volume' : 0.5,
    //   'isLoop': false,
    //   'duration' : 0,
    // },
    targetObjectIndex : 0,
  },
  getters : {
    getIsInitializedAudios : function(state){ return state.isInitialized; },
    getMediaAudios : function(state){ return state.mediaAudios; },
    getMediaAudio : function(state){ return state.mediaAudios[state.targetObjectIndex]},
  },
  mutations : {
    deleteMediaAudiosObjectItem(state, payload){
      state.mediaAudios.splice(payload,1) // audioを1つ削除
    },
    addMediaAudiosObjectItem(state, payload){
      state.mediaAudios.push(payload);
    },
    updateIsInitializedAudios(state,payload){state.isInitialized = payload},
    updateMediaAudiosObjectItem(state, {index, key, value}){state.mediaAudios[index][key] = value;},
    setTargetObjectIndex(state, payload){ state.targetObjectIndex = payload },
  },
  actions : {}

}

export default mediaAudios;

