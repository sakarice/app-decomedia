const media = {
  namespaced : true,

  state : {
    mediaId : 0,
    isMyMedia : false,
    mode : 0, // 1:create, 2:edit, 3:show
    isWaiting : false,
  },
  getters : {
    getMediaId : function(state){ return state.mediaId; },
    getIsMyMedia : function(state){ return state.isMyMedia; },
    getMode : function(state){ return state.mode; },
    getIsWaiting : function(state){ return state.IsWaiting; },
  },
  mutations : {
    setMediaId(state, payload){state.mediaId = payload},
    setIsMyMedia(state, payload){state.isMyMedia = payload},
    setMode(state, payload){state.mode = payload},
    setIsWaiting(state, payload){state.isWaiting = payload},
  },

}

export default media;

