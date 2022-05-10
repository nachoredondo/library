
function max_text(text, length = 20){
	if (text.length > length + 3) {
			return text.substr(0,length) + "...";
	} else {
			return text
	}
}

function make_request(path, params, method) {
	method = method || "post"; // Set method to post by default if not specified.

	var form = document.createElement("form");
	form.setAttribute("method", method);
	form.setAttribute("action", path);

	for (var key in params) {
		if (params.hasOwnProperty(key)) {
			var hiddenField = document.createElement("input");
			hiddenField.setAttribute("type", "hidden");
			hiddenField.setAttribute("name", key);
			hiddenField.setAttribute("value", params[key]);

			form.appendChild(hiddenField);
		}
	}

	document.body.appendChild(form);
	form.submit();
}
