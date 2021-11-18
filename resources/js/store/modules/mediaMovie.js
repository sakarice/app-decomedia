const mediaMovie = {
  namespaced : true,

  state : {
    mediaMovie : {
      'videoId' : "",
      'width' : "500",
      'height' : "420",
      'isLoop' : false,
      'layer' : 1,
    },
  },
  getters : {
    getMediaMovie : function(state){ return state.mediaMovie; },
  },
  mutations : {
    updateMediaMovieObjectItem(state, {key, value}){state.mediaMovie[key] = value;},
  },
  actions : {}

}

export default mediaMovie;

