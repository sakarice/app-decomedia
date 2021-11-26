const media = {
  namespaced : true,

  state : {
    mediaId : 0,
    isMyMedia : false,
    mode : 0, // 1:create, 2:edit, 3:show
    IsCrudDoing : false,
  },
  getters : {
    getMediaId : function(state){ return state.mediaId; },
    getIsMyMedia : function(state){ return state.isMyMedia; },
    getMode : function(state){ return state.mode; },
    getIsCrudDoing : function(state){ return state.IsCrudDoing; },
  },
  mutations : {
    setMediaId(state, payload){state.mediaId = payload},
    setIsMyMedia(state, payload){state.isMyMedia = payload},
    setMode(state, payload){state.mode = payload},
    setIsCrudDoing(state, payload){state.IsCrudDoing = payload},
  },

}

export default media;

