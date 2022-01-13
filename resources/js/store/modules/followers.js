const followers = {
  namespaced : true,

  state : {
    // DBから取得したデータで下記mediaFollowerが初期化がされたか。
    isInitialized : false,
    // メディア画像情報。show,editモードでは始めにDBのデータで初期化される。
    followers : [],
    // follower : {
      // name : ""
      // profile_img_url : ""
      // profile : ""
    // },
    targetObjectIndex : 0,
  },
  getters : {
    getIsInitializedFollower : function(state){ return state.isInitialized; },
    getFollowers : function(state){ return state.followers; },
    getFollower : function(state){ return state.followers[state.targetObjectIndex]; },
  },
  mutations : {
    deleteFollowersObjectItem(state, payload){
      state.followers.splice(payload,1) // Followerを1つ削除
    },
    addFollowersObjectItem(state, payload){
      state.followers.push(payload);
    },
    updateIsInitializedFollowers(state,payload){state.isInitialized = payload},
    updateFollowersObjectItem(state, {index, key, value}){state.followers[index][key] = value;},
    setTargetObjectIndex(state, payload){ state.targetObjectIndex = payload },
  },
  actions : {}

}

export default followers;

