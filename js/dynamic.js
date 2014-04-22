jQuery(document).ready(function($) {


	function slugify(val) {
		val = val.replace(/[^a-zA-Z 0-9-]+/g,'');
		val = val.toLowerCase();
		val = val.replace(/\s/g,'-');
		return val;
	};


	function htmlify(obj,name) {
		var html = "";
		html += "<tr id=\"" + name + "_row\">";
		html += "<td style=\"padding:0px;padding-left:4px;\">" + obj["name"] + "</td>";
		html += "<td style=\"padding:0px;padding-left:4px;\">" + obj["type"] + "</td>";
		html += "<td style=\"padding:0px;\"><div class=\"dashicons dashicons-no kin_remove\" data-for=\"" + name + "\"></div></td>";
		html += "</tr>";
		return html;
	}

	function quantify(obj) {
		var quant = 0;
		$.each(obj, function() {
			quant++;
		});
		return quant;
	}


	$("body").on("click", ".kin_add", function() {

		var newname = $("#kin_meta_name_add").val();
		var newtype = $("#kin_meta_type_add").val();

		if (kin_fields) {
			if (newname) {

				var newslug = 'kin-' + slugify(newname);

				if (!kin_fields[newslug]) {

					if (quantify(kin_fields)==0) kin_fields = ({});
	
					kin_fields[newslug] = ({
						 name:newname
						,type:newtype
					});

					var json = JSON.stringify(kin_fields);
					$("#kin_fields").val(json);
					
					var html = htmlify(kin_fields[newslug],newslug);
					$("#kin_meta_table tr:last").after(html);

				} else alert("This name is already in use. Please think of another.");
			} else alert("Please type a name.");
		} else alert("Error saving fields. Please verify data is being localized.");
	});


	$("body").on("click", ".kin_remove", function() {

		var toremove = $(this).data("for");

		if (kin_fields[toremove]) delete kin_fields[toremove];

		$("#" + toremove + "_row").remove();

		var json = JSON.stringify(kin_fields);
		$("#kin_fields").val(json);
	});


	$(window).on("load", function() {
		$.each(kin_fields, function(k,v) {
			var html = htmlify(this,k);
			$("#kin_meta_table tr:last").after(html);
		});
	});
});