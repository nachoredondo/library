
function max_text(text, length = 20){
	if (text.length > length + 3) {
			return text.substr(0,length) + "...";
	} else {
			return text
	}
}
