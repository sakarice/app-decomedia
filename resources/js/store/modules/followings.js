const followings = {
  namespaced : true,

  state : {
    // DBから取得したデータで下記mediaFollowingが初期化がされたか。
    isInitialized : false,
    // メディア画像情報。show,editモードでは始めにDBのデータで初期化される。
    followings : [],
    // following : {
      // name : ""
      // profile_img_url : ""
      // profile : ""
    // },
    targetObjectIndex : 0,
  },
  getters : {
    getIsInitializedFollowing : function(state){ return state.isInitialized; },
    getFollowings : function(state){ return state.followings; },
    getFollowing : function(state){ return state.followings[state.targetObjectIndex]; },
  },
  mutations : {
    deleteFollowingsObjectItem(state, payload){
      state.followings.splice(payload,1) // Followingを1つ削除
    },
    addFollowingsObjectItem(state, payload){
      state.followings.push(payload);
    },
    updateIsInitializedFollowings(state,payload){state.isInitialized = payload},
    updateFollowingsObjectItem(state, {index, key, value}){state.followings[index][key] = value;},
    setTargetObjectIndex(state, payload){ state.targetObjectIndex = payload },
  },
  actions : {}

}

export default followings;

