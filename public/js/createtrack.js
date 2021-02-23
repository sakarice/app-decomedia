const dropZone = document.getElementById('drop-zone');
const imgInput = document.getElementById('imgfile');
const soundInput = document.getElementById('soundfile');
const soundTitleForm = document.getElementById('sound-title-form');
const soundTitleInput = document.getElementById('sound-title-in');
const soundTitleInHidden = document.getElementById('sound-title-in-hidden');


	// マウスオーバー時のイベント：背景色を変更
	dropZone.addEventListener('dragover', function(e){
		e.stopPropagation();
		e.preventDefault();
		this.style.background = "#aaaaff";
	}, false);

	// マウスが離れた時のイベント：背景色を元に戻す
	dropZone.addEventListener('dragleave', function(e){
		e.stopPropagation();
		e.preventDefault();
		this.style.background = "#ffffff";
	}, false);

	// ファイルドラッグ&ドロップ時のイベント：inputのvalueをドロップしたファイルで置き換える。
	dropZone.addEventListener('drop', function(e){
		e.stopPropagation();
		e.preventDefault();
		this.style.background = "#ffffff";	// 背景色を白に戻す
		var files = e.dataTransfer.files;
		if(files.length > 1){
			return alert('アップロードできるファイルは1つだけです。');
		}

		// アップロードしたファイルの種類に応じてinputを書き換える
		if(files[0].type.indexOf('image')===0){
			imgInput.files = files;	// inputのvalueをドラッグしたファイルに置き換える
			previewImage(files[0]); // 画像プレビュー表示
		} else if(files[0].type.indexOf('audio')===0) {
			soundInput.files = files;	// inputのvalueをドラッグしたファイルに置き換える
			previewSound(files[0]); // 音声ファイルのファイル名表示
		}


	}, false);



// 画像ファイルが選択された時のイベント：プレビュー表示
imgInput.addEventListener('change', function(){
	previewImage(this.files[0]);
});

// 音声ファイルが選択された時のイベント：プレビュー表示
soundInput.addEventListener('change', function(){
		previewSound(this.files[0]);
});


// アップロードされた画像のプレビュー
function previewImage(file){
	var fileReader = new FileReader();
	var previewZone = document.getElementById('drop-zone');
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

// 音声ファイルのファイル名表示
function previewSound(file){
	var fileReader = new FileReader();
	fileReader.onload = (function() {
		soundTitleInput.value = file.name;
		soundTitleInHidden.value = file.name;
		// let KEvent = new KeyboardEvent("keydown", {keyCode:13});
		// soundTitleInput.dispatchEvent(KEvent);
	})
	fileReader.readAsDataURL(file);
}

// 音声トラック名がユーザに編集されたら
soundTitleInput.addEventListener('change', function(){
	soundTitleInHidden.value = soundTitleInput.value;
})

