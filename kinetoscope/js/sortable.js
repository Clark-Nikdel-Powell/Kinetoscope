jQuery(document).ready(function($) {
	$(function() {
		$(".kin-slideshow").sortable({
			placeholder: "kin-slides kin-slides-small kin-placeholder"
			,revert: true
			,update: function(event, ui) {

				var order=[];
				var liparent = $(this);
				var lichildren = $(liparent).children('li');

				var i=0;
				$.each(lichildren,function() {
					order[i] = $(this).data("id");
					i++;
					$(this).children("pos").html(i+".");
				});

				ostring = JSON.stringify(order);

				$.ajax({
					type:"POST"
					,url: ajaxurl
					,data: {
						action: "kinetoscope_save"
						,id: $(liparent).data("id")
						,data: ostring
					}
					,processData: true
					,success: function(response) {
						var title = $("#"+$(liparent).attr("id")+"_slug");
						$(title).addClass("kin-updated");
						setTimeout(
							function(){
								$(title).removeClass("kin-updated")
							},3000
						);
					}
				});
			}
		}).disableSelection();


		$(".kin-trash").droppable({
			hoverClass: "kin-hover"
			,tolerance: "pointer"
			,drop: function(event, ui) {
				$.ajax({
					type:"POST"
					,url: ajaxurl
					,data: {
						action: "kinetoscope_remove"
						,id: ui.draggable.data("id")
						,term_id: ui.draggable.parent("ul").data("id")
					}
					,processData: true
					,success: function(response) {
						ui.draggable.remove();
					}
				});

			}
		});
	});
});