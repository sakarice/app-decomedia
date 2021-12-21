const selectedObjects = {
  namespaced : true,
  state : {
    selectedObjects : [],
    // selectedObject : {
    //   type,  // type…1:figure, 2:img, 
    //   index,
    // },
  },
  getters : {
    getSelectedObjects : function(state){ return state.selectedObjects; },
  },
  mutations : {
    deleteSelectedObjectItem(state, payload){
      state.selectedObjects.splice(payload,1)
    },
    unSelectedAll(state){
      state.selectedObjects.splice(0);
    },
    addSelectedObjectItem(state, payload){
      state.selectedObjects.push(payload);
    },
  },

}

export default selectedObjects;

