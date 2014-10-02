jQuery(document).ready(function($) {

	var file_frame;
	jQuery(".kin_add_media").on("click", function( event ){

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
			
			var attachment = file_frame.state().get("selection").first().toJSON();
			var id = attachment["id"];
			var thumbnail = attachment["sizes"]["thumbnail"]["url"]
			var image = "<img src=\"" + thumbnail + "\" />";

			$("input[name='" + editor + "']").val(id);
			$("#" + editor).html(image);
		});

		file_frame.open();
	});

});