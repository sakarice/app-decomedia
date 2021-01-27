const dropZone = document.getElementsByClassName('drop-zone');
const imgInput = document.getElementById('imgfile');

for(let i=0; i<2; i++){
	// マウスオーバー時のイベント：背景色を変更
	dropZone[i].addEventListener('dragover', function(e){
		e.stopPropagation();
		e.preventDefault();
		this.style.background = "#aaaaff";
	}, false);

	// マウスが離れた時のイベント：背景色を元に戻す
	dropZone[i].addEventListener('dragleave', function(e){
		e.stopPropagation();
		e.preventDefault();
		this.style.background = "#ffffff";
	}, false);

// ファイルドラッグ&ドロップ時のイベント：inputのvalueをドロップしたファイルで置き換える。
dropZone[i].addEventListener('drop', function(e){
	e.stopPropagation();
	e.preventDefault();
	this.style.background = "#ffffff";	// 背景色を白に戻す
	var files = e.dataTransfer.files;
	if(files.length > 1){
		return alert('アップロードできるファイルは1つだけです。');
	}
	imgInput.files = files;	// inputのvalueをドラッグしたファイルに置き換える
	previewImage(files[0]);
}, false);

}


// 画像が選択された時のイベント：プレビュー表示
imgInput.addEventListener('change', function(){
	previewImage(this.files[0]);
});

// アップロードされた画像のプレビュー
function previewImage(file){
	var fileReader = new FileReader();
	var previewZone = document.getElementById('drop-1');
	var childElement = previewZone.firstElementChild;
	fileReader.onload = (function() {
		if(childElement.tagName!='img'){
			childElement.remove();
			const img_prev = document.createElement("img");
			img_prev.setAttribute("id", "preview");
			img_prev.setAttribute("src", fileReader.result);
			previewZone.appendChild(img_prev);
		} else {
			document.getElementById("preview").setAttribute("src", fileReader.result);
		}
	})
	fileReader.readAsDataURL(file);
}
