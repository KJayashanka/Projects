
function changeFont(target) {		// Refered from the stackoverflow
	var body_content = document.getElementById("body_content");
	var computedStyle = window.getComputedStyle ? getComputedStyle(body_content) : body_content.currentStyle;
	var fontSize;

	if (computedStyle) {
		fontSize = parseFloat(computedStyle && computedStyle.fontSize);
		if (target === document.getElementById("max")) {
			fontSize += 2;
		}else if (target === document.getElementById("min")) {
			fontSize -= 2;
		}else{
			fontSize = 16;
		}
			body_content.style.fontSize = fontSize + "px";
	}
}