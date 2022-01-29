const loginState = {
  namespaced : true,

  state : {
    isLogin : false,
    isGuest : false,
  },
  getters : {
    getIsLogin : function(state){
      return state.isLogin;
    },
    getIsGuest : function(state){
      return state.isGuest;
    },
  },
  mutations : {
    setIsLogin(state, payload){
      state.isLogin = payload;
    },
    setIsGuest(state, payload){
      state.isGuest = payload;
    },
  },
  actions : {
    async checkIsLogin (context) {
      const res1 = await axios.get('/checkIsLogin');
      const res2 = await axios.get('/checkIsGuestLogin');
      const isLogin = res1.data.isLogin;
      const isGuest = res2.data.isGuest;
      context.commit('setIsLogin', isLogin);
      context.commit('setIsGuest', isGuest);
    }

  }

}

export default loginState;

