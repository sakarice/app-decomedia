const mediaContentsField = {
  namespaced : true,

  state : {
    // DBから取得したデータで下記mediaContentsFieldが初期化がされたか。
    isInitialized : false,
    // メディア動画情報。show,editモードでは始めにDBのデータで初期化される。
    mediaContentsField : {
      'id' : null,
      'media_id' : null,
      'width' : 500,
      'height' : 500,
      'color' : '#ffffff',
      'img_url' : '',
      'img_size_type' : 'cover',
    },
  },
  getters : {
    getIsInitializedContentsField : function(state){ return state.isInitialized; },
    getMediaContentsField : function(state){ return state.mediaContentsField; },
  },
  mutations : {
    updateMediaContentsFieldObjectItem(state, {key, value}){state.mediaContentsField[key] = value;},
    updateIsInitializedContentsField(state,payload){state.isInitialized = payload},
  },
  actions : {}

}

export default mediaContentsField;

