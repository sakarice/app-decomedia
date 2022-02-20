const tutorialInfo_2 = {
  namespaced : true,

  state: {
    title : "tutorial title2",
    imgUrl : "https://cdn.pixabay.com/photo/2017/10/08/20/37/dvd-2831541_1280.png",
    description : "this is test description2",
  },
  getters : {
    getTutorialInfo : function(state){return state},
    getTitle : function(state){return state.title},
    getImgUrl : function(state){return state.imgUrl},
    getDescription : function(state){return state.Description},
  },
  mutations : {
  },

}

export default tutorialInfo_2;