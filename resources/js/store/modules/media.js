const media = {
  namespaced : true,

  state : {
    mediaId : 0,
  },
  getters : {
    getMediaId : function(state){ return state.mediaId; },
  },
  mutations : {
    setMediaId(state, payload){state.mediaId = payload},
  },

}

export default media;

