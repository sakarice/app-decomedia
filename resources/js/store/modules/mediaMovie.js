const mediaMovie = {
  namespaced : true,

  state : {
    // DBから取得したデータで下記mediaMovieが初期化がされたか。
    isInitialized : false,
    // メディア動画情報。show,editモードでは始めにDBのデータで初期化される。    
    mediaMovie : {
      'videoId' : "",
      'width' : "500",
      'height' : "420",
      'isLoop' : false,
      'layer' : 1,
    },
  },
  getters : {
    getIsInitializedMovie : function(state){ return state.isInitialized; },
    getMediaMovie : function(state){ return state.mediaMovie; },
  },
  mutations : {
    updateMediaMovieObjectItem(state, {key, value}){state.mediaMovie[key] = value;},
    updateIsInitializedMovie(state,payload){state.isInitialized = payload},
  },
  actions : {}

}

export default mediaMovie;

