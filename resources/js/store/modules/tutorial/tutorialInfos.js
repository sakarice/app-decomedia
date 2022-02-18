const tutorialInfos = {
  namespaced : true,

  state : {
    // DBから取得したデータで下記tutorialInfoが初期化がされたか。
    isInitialized : false,
    tutorialInfos : [],
    targetObjectIndex : 0,
  },
  getters : {
    getIsInitializedTutorialInfos : function(state){ return state.isInitialized; },
    getTutorialInfos : function(state){ return state.tutorialInfos; },
    getTutorialInfo : function(state){ return state.tutorialInfos[state.targetObjectIndex]; },
  },
  mutations : {
    deleteTutorialInfosObjectItem(state, payload){
      state.tutorialInfos.splice(payload,1);
    },
    addTutorialInfosObjectItem(state, payload){
      state.tutorialInfos.push(payload);
    },
    setTargetObjectIndex(state, payload){ state.targetObjectIndex = payload },
  },
  actions : {}

}

export default tutorialInfos;

