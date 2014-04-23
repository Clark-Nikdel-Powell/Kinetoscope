jQuery(document).ready(function($) {


	// function to create meta slugs
	function slugify(val) {
		val = val.replace(/[^a-zA-Z 0-9-]+/g,'');
		val = val.toLowerCase();
		val = val.replace(/\s/g,'_');
		return val;
	};


	// function to create html table
	function htmlify(obj,name) {
		var html = "";
		html += "<tr id=\"" + name + "_row\">";
		html += "<td style=\"padding:0px;\"><input class=\"kin_update\" data-for=\"" + name + "\" type=\"text\" value=\"" + obj["name"] + "\" /></td>";
		html += "<td style=\"padding:0px;\">" + obj["type"] + "</td>";
		html += "<td style=\"padding:0px;\"><div class=\"dashicons dashicons-no kin_remove\" data-for=\"" + name + "\"></div></td>";
		html += "</tr>";
		return html;
	}


	// count object elements
	function quantify(obj) {
		var quant = 0;
		$.each(obj, function() { quant++; });
		return quant;
	}


	// do this to when loading the document to generate fields
	$(window).on("load", function() {
		$.each(_kin_fields, function(k,v) {
			var html = htmlify(this,k);
			$("#kin_meta_table tr:last").after(html);
		});
	});


	// do this each time the add button is clicked
	$("body").on("click", ".kin_add", function() {
		var newname = $("#kin_meta_name_add").val();
		var newtype = $("#kin_meta_type_add").val();
		if (_kin_fields) {
			if (newname) {

				var newslug = '_kin_' + slugify(newname);

				if (!_kin_fields[newslug]) {

					if (quantify(_kin_fields)==0) _kin_fields = ({});
	
					_kin_fields[newslug] = ({
						 name:newname
						,type:newtype
					});

					var json = JSON.stringify(_kin_fields);
					$("#_kin_fields").val(json);
					
					var html = htmlify(_kin_fields[newslug],newslug);
					$("#kin_meta_table tr:last").after(html);

				} else alert("This name is already in use. Please think of another.");
			} else alert("Please type a name.");
		} else alert("Error saving fields. Please verify data is being localized.");
	});


	// do this to remove elements
	$("body").on("click", ".kin_remove", function() {
		var toremove = $(this).data("for");
		if (_kin_fields[toremove]) delete _kin_fields[toremove];

		$("#" + toremove + "_row").remove();

		var json = JSON.stringify(_kin_fields);
		$("#_kin_fields").val(json);
	});


	// do this to update the names
	$("body").on("keyup", ".kin_update", function() {
		var key = $(this).data("for");
		_kin_fields[key]['name'] = $(this).val();

		var json = JSON.stringify(_kin_fields);
		$("#_kin_fields").val(json);
	});

	
});