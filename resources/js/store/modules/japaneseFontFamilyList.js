const japaneseFontFamilyList = {
  namespaced : true,

  state : {
    japaneseFontFamilyList : [
      // 日本語
      {name:"Noto Sans Japanese", value:'"Noto Sans JP",sans-serif'},
      {name:"Noto Serif Japanese", value:'"Noto Serif JP",serif'},
      {name:"Sawarabi Mincho", value:'"Sawarabi Mincho",sans-serif'},
      {name:"Mochiy Pop P One", value:'"Mochiy Pop P One",sans-serif'},
      {name:"Yuji Mai", value:'"Yuji Mai", serif'},
      {name:"Zen Antique", value:'"Zen Antique",serif'},
      {name:"Reggae One", value:'"Reggae One", cursive'},
      {name:"Rampart One", value:'"Rampart One", cursive'},
      {name:"DotGothic16", value:'"DotGothic16", sans-serif'},
      {name:"YuMincho", value:'"Yu Mincho", "YuMincho"'},
      {name:"", value:'""'},
    ]
  },
  getters : {
    getJapaneseFontFamilyList : function(state){ return state.japaneseFontFamilyList; },
  },
  mutations : {},

}

export default japaneseFontFamilyList;

