const deviceType = {
  namespaced : true,

  state : {
    // 0:PC, 1:TABLET, 2:MOBILE
    deviceType : 0
  },
  getters : {
    getDeviceType : function(state){ return state.deviceType; },
  },
  mutations : {
    setDeviceType(state, payload){state.deviceType = payload},
  },
}

export default deviceType;

