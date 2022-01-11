// 
  const showOverLayEvent = new CustomEvent('showOverLay');
  const hideOverLayEvent = new CustomEvent('hideOverLay');

  function showOverLay(){
    document.body.dispatchEvent(showOverLayEvent);
  }

  function hideOverLay(){
    document.body.dispatchEvent(hideOverLayEvent);
  }

// exportは、移動のトリガーとなるmoveStartのみ
export {showOverLay, hideOverLay};

