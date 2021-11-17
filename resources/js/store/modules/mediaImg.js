const mediaImg = {
  namespaced : true,

  state : {
    mediaImg : {
      type : 0,
      id : 0,
      url : "",
      width : 500,
      height : 500,
      opacity : 1,
      layer : 0,
    }
  },
  getters : {
    getMediaImg : function(state){ return state.mediaImg; },
  },
  mutations : {
    updateMediaImgContent(state, {type,id,url}){
      state.mediaImg['type'] = type;
      state.mediaImg['id'] = id;
      state.mediaImg['url'] = url;
    },
    updateMediaImgObjectItem(state, {key, value}){state.mediaImg[key] = value;},
  },
  actions : {}

}

export default mediaImg;

