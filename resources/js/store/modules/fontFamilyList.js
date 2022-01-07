const fontFamilyList = {
  namespaced : true,

  state : {
    fontFamilyList : [
      // 総称フォント
      {name:"monospace", value:"monospace"},
      {name:"serif", value:"serif"},

      // 英語(&日本語)
      {name:"Meiryo UI", value:'"Meiryo UI"'},
      {name:"Arial Black", value:'"Arial Black"'},
      {name:"Helvetica", value:'"Helvetica"'},
      {name:"Times New Roman", value:'"Times New Roman"'},
      {name:"Sacramento", value:'"Sacramento"'},
      {name:"Caveat", value:'"Caveat"'},
      {name:"Amatic SC", value:'"Amatic SC"'},
      {name:"Itim", value:'"Itim"'},
      {name:"Alegreya Sans SC", value:'"Alegreya Sans SC"'},
      {name:"Anton", value:'"Anton"'},
      {name:"Bangers", value:'"Bangers"'},
      {name:"Cherry Swash", value:'"Cherry Swash"'},
      {name:"Corben", value:'"Corben"'},
      {name:"Creepster", value:'"Creepster"'},
      {name:"IM Fell DW Pica SC", value:'"IM Fell DW Pica SC"'},
      {name:"Londrina Shadow", value:'"Londrina Shadow"'},
      {name:"", value:'""'},
      {name:"", value:'""'},
      {name:"", value:'""'},

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
      {name:"", value:'""'},

      {name:"YuMincho", value:'"Yu Mincho", "YuMincho"'},
      {name:"Hiragino Sans", value:'"Hiragino Sans"'},
      {name:"Kosugi", value:'"Kosugi"'},
      {name:"Hannari", value:'"Hannari"'},
      {name:"Nikukyu", value:'"Nikukyu"'},
      {name:"Nico Moji", value:'"Nico Moji"'},
      {name:"", value:'""'},
    ]
  },
  getters : {
    getFontFamilyList : function(state){ return state.fontFamilyList; },
  },
  mutations : {},

}

export default fontFamilyList;

