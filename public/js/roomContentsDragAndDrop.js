const dropZone = document.getElementById('content');

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
