function previewImage(obj)
{
	var fileReader = new FileReader();
	const div_prev = document.getElementById('preview_wrapper');
	
	fileReader.onload = (function() {
		if(!div_prev.hasChildNodes()){
			const img_prev = document.createElement("img");
			img_prev.setAttribute("id", "preview");
			img_prev.setAttribute("src", fileReader.result);
			div_prev.appendChild(img_prev);
		} else {
			document.getElementById("preview").setAttribute("src", fileReader.result);
		}
	});
	fileReader.readAsDataURL(obj.files[0]);
}

