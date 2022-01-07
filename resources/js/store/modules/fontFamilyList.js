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

    ]
  },
  getters : {
    getFontFamilyList : function(state){ return state.fontFamilyList; },
  },
  mutations : {},

}

export default fontFamilyList;

