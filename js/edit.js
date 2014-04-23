jQuery(document).ready(function($) {

	var file_frame;
	jQuery(".kin_add_media").live("click", function( event ){

		var editor = $(this).data("editor");
		event.preventDefault();

		if ( file_frame ) {
			file_frame.open();
			return;
		}

		file_frame = wp.media.frames.file_frame = wp.media({
			title: jQuery(this).data( "uploader_title" ),
			button: {
				text: jQuery(this).data( "uploader_button_text" ),
			},
			multiple: false
		});


		file_frame.on("select",function() {
			
			attachment = file_frame.state().get("selection").first().toJSON();

			console.log(attachment);

			var url = attachment["url"];

			$("input[name='" + editor + "']").val(url);

			var image = "<img src=\"" + url + "\" />";
			$("#" + editor).html(image);
		});

		file_frame.open();
	});

});