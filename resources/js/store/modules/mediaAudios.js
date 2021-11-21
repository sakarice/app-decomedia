const mediaAudios = {
  namespaced : true,

  state : {
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
  },
  getters : {
    getMediaAudios : function(state){ return state.mediaAudios; },
  },
  mutations : {
    deleteMediaAudiosObjectItem(state, payload){
      state.mediaAudios.splice(payload,1) // audioを1つ削除
    },
    addMediaAudiosObjectItem(state, payload){
      state.mediaAudios.push(payload);
    },
    updateMediaAudiosObjectItem(state, {index, key, value}){state.mediaAudios[index][key] = value;},
  },
  actions : {}

}

export default mediaAudios;

