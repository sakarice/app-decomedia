const loginState = {
  namespaced : true,

  state : {
    isLogin : false,
  },
  getters : {},
  mutations : {
    setIsLogin(state, payload){
      state.isLogin = payload;
    },
  },
  actions : {
    async checkIsLogin (context) {
      const res = await axios.get('/checkIsLogin');
      const isLogin = res.data.isLogin;
      context.commit('setIsLogin', isLogin);
    }

  }

}

export default loginState;

