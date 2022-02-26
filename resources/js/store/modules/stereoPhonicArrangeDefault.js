const stereoPhonicArrangeDefault = {
  namespaced : true,

  state : {
    stereoPhonicArrangeDefault : {
      panningFlag : true,
      panningModel : "equalpower",
      positionX : 0,
      positionY : 0,
      positionZ : 0,
      // orientationX : 0,
      // orientationY : 0,
      // orientationZ : 0,
      // distanceModel : "inverse",
      // refDistance : 1,
      // maxDistance : 10000,
      // rolloffFactor : 1,
      // coneInnerAngle : 360,
      // coneOuterAngle : 360,
      // coneOuterGain : 0,
    },
  },
  getters : {
    getStereoPhonicArrangeDefault : function(state){ return state.stereoPhonicArrangeDefault; },
  },
}

export default stereoPhonicArrangeDefault;

