const mediaComments = {
  namespaced : true,

  state : {
    // DBから取得したデータで下記mediaCommentが初期化がされたか。
    isInitialized : false,
    // メディア画像情報。show,editモードでは始めにDBのデータで初期化される。
    mediaComments : [],
      // id : 0,
      // media_id : 0,
      // user_id : 0,
      // user_name : "",
      // user_icon_url : "",
      // comment : "",
      // good : "",
      // created_at : 0,
      // updated_at : 0,
    targetObjectIndex : 0,
  },
  getters : {
    getIsInitializedComment : function(state){ return state.isInitialized; },
    getMediaComments : function(state){ return state.mediaComments; },
    getMediaComment : function(state){ return state.mediaComments[state.targetObjectIndex]; },
  },
  mutations : {
    deleteMediaCommentsObjectItem(state, payload){
      state.mediaComments.splice(payload,1) // commentを1つ削除
    },
    addMediaCommentsObjectItem(state, payload){
      state.mediaComments.push(payload);
    },
    updateIsInitializedComments(state,payload){state.isInitialized = payload},
    updateMediaCommentsObjectItem(state, {index, key, value}){state.mediaComments[index][key] = value },
    setTargetObjectIndex(state, payload){ state.targetObjectIndex = payload },  },
  actions : {}

}

export default mediaComments;

