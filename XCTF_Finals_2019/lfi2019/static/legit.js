var form = $("#kawaii");
var text = $(".text");
$(function() {
	$(window).scroll(function() {
		var scroll = $(window).scrollTop();
		if (scroll >= 200) {
			if (text.hasClass("hidden")) {
				text.removeClass("hidden");
				setTimeout("form.removeClass('hide')", 1000);
			}
		} else {
			if (!text.hasClass("hidden")) {
				text.addClass("hidden");
				form.addClass("hide");
			}
		}
	});
	$("#put").click(function() {
		$("#put-modal").modal();
	});
	$("#get").click(function() {
		$("#get-modal").modal();
	});
	$("#include-submit").click(function() {
		_filename = $("#include-filename").val();
		$.get("?get&" + _filename, function(_output) {
			try {
				switch (_output['type']) {
					case "error":
						alert(_output['msg']);
						break;
					case "success":
						alert('Load successful, but nothing returned.');
				}
			} catch (e) {
				$("#include-content").html(_output);
			}
		});
	});
	$("#upload-submit").click(function() {
		_filename = $("#upload-filename").val();
		_post_data = {
			"filename": _filename,
			"content": $("#upload-content").val()
		};
		$.post("?put&" + _filename, _post_data, function(_output) {
			try {
				switch (_output['type']) {
					case "error":
						alert(_output['msg']);
						break;
					case "success":
						alert('Upload successful at /files/');
				}
			} catch (e) {
				alert(_output);
			}
		});
	});
	$("#info").click(function() {
		$("#info-modal").modal();
	});
});