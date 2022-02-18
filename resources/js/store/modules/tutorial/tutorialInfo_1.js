const tutorialInfo_1 = {
  namespaced : true,

  state: {
    title : "tutorial title",
    imgUrl : "https://cdn.pixabay.com/photo/2018/09/17/09/47/pixel-3683374_960_720.png",
    description : "this is test description",
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

export default tutorialInfo_1;