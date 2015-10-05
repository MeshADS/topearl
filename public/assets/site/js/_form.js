var formJS = new function(){
	var form, elements,
		cc = 0,
		validationRules = [];
		validateOptions = {},
		colors = ["orange", "green", "green2", "purple", "blue"],
		$fieldsetTpl = '<fieldset class="m-b-30">'+
							'|LEGEND|'+
							'<div class="row">'+
								'|ELEMENTS|'+
							'</div>'+
						'</fieldset>',
		$elementTpl = 	'<div class="|SIZE|">'+
							'<div class="form-group">'+
								'|ELEMENT|'+
							'</div>'+										
						'</div>',
		$buttonTpl= '<button type="submit" class="btn btn-lg |COLOR|-background hoverable m-v-40">'+
							'<span class="white-text">Submit</span>'+
					'</button>',
		$elements = [],
		$listValues = [],
		$elements["checkbox"] = '<h4 class="black-text">|ELEMENTNAME|</h4>|OPTIONS|',
		$elements["radio-button"] = '<h4 class="black-text">|ELEMENTNAME|</h4>|OPTIONS|',
		$elements["select"] = 	'<label for="|ELEMENTID|">|ELEMENTNAME|</label>'+
								'<select name="|ELEMENTSLUG|" id="|ELEMENTID|" class="form-control">'+
								'|OPTIONS|'+
								'</select>',
		$elements["text-input"] = 	'<label for="|ELEMENTID|">|ELEMENTNAME|</label>'+
									'<input type="text" name="|ELEMENTSLUG|" id="|ELEMENTID|" class="form-control |IFDATE|">',
		$elements["textarea"] = 	'<label for="|ELEMENTID|">|ELEMENTNAME|</label>'+
									'<textarea name="|ELEMENTSLUG|" id="|ELEMENTID|" rows="3" class="form-control"></textarea>',
		$listValues["select"] = 	'<option value="|OPTIONVALUE|">'+'|OPTIONNAME|'+'</option>',
		$listValues["checkbox"] = 	'<label for="|ELEMENTID|">'+
										'<input type="checkbox" name="|ELEMENTSLUG|[]" id="|ELEMENTID|" value="|OPTIONVALUE|">&nbsp;|ELEMENTNAME|'+
									'</label><br>',
		$listValues["radio-button"] = '<label for="|ELEMENTID|">'+
										'<input type="radio" name="|ELEMENTSLUG|" id="|ELEMENTID|" class="">&nbsp;|ELEMENTNAME|'+
									'</label><br>';

	var bootData = function(){
		form = Site.data.form;
		elements = form.elements;
		formHtml = "";
		for (var i in elements) {
			var group = elements[i];
			var resp = addElementGroups(i, group, $fieldsetTpl);
			formHtml += resp;
		};
		// Append hidden form elements to form
		$(formHtml).appendTo("#"+form.slug+" #form_fields");
		// Create submit button for forms
		var button = $buttonTpl;
		checkCC();
		button = button.replace("|COLOR|", colors[cc]);
		cc++;
		$(button).appendTo("#"+form.slug+" #form_button");
		// Hide form loader
		$(".form_holder .loader").fadeOut('fast', function(){
			// Make form visisible
			$(".form_holder form").fadeIn('fast');
		});
		// Init form plugins
		// Validate form
		validateForm("#"+form.slug);
		// Date picker
		$('.datepicker').datepicker({
		    format: 'mm/dd/yyyy'
		});
	};

	var addElementGroups = function (groupName, group, tpl) {
		checkCC();
		var elements = "";
		if (group.length > 0) {
			tpl = tpl.replace('|LEGEND|', '<legend class="'+colors[cc]+'-text">'+groupName+'</legend>')
			cc++;
		}
		else{
			tpl = tpl.replace('|LEGEND|', '');
		}
		for(var i in group){
			var element = group[i];
		  	elements += creatElement(element);
		};
		tpl = tpl.replace("|ELEMENTS|", elements);
	  	return tpl;
	};

	var creatElement= function(element){
		// Create new checkbox
		var mainTpl = $elementTpl,
		    groupElement = $elements[element.type],
			thisListValues = "",
			listvalues = element.list_values,
			elementRules = element.rules,
			rules = [],
			isDate = false;
		// Loop through rules
		for(var er in elementRules){
			elRule = elementRules[er];
			rules[elRule] = true;
			if (elRule == "date") {
				isDate = true;
			};
		}
		// Loop through list value
		for(var lv in listvalues){
			var listvalue = listvalues[lv];
			thisListValues += $listValues[element.type];
			// Convert elemenet data
			thisListValues = thisListValues.replace("|ELEMENTID|", element.id+listvalue.id);
			thisListValues = thisListValues.replace("|ELEMENTNAME|", listvalue.name);
			thisListValues = thisListValues.replace("|ELEMENTSLUG|", element.slug);
			thisListValues = thisListValues.replace("|OPTIONNAME|", listvalue.name);
			thisListValues = thisListValues.replace("|OPTIONVALUE|", listvalue.value);
			thisListValues = thisListValues.replace("|ELEMENTID|", element.id+listvalue.id);			
		};
		// Set element rules
		validationRules[element.slug] = rules;
		// Format element
		groupElement = groupElement.replace("|OPTIONS|", thisListValues)
		groupElement = groupElement.replace("|ELEMENTNAME|", element.name)
		groupElement = groupElement.replace("|ELEMENTID|", element.id);
		groupElement = groupElement.replace("|ELEMENTID|", element.id);
		groupElement = groupElement.replace("|ELEMENTSLUG|", element.slug);
		if (isDate) {
			groupElement = groupElement.replace("|IFDATE|", 'datepicker');
		};
		// Format element
		mainTpl = mainTpl.replace("|SIZE|", element.size);
		mainTpl = mainTpl.replace("|ELEMENT|", groupElement);
		// Return response
		return mainTpl;
	};

	var validateForm = function(id){

		$(id).validate({
			rules: validationRules
		});
	};

	var checkCC = function(){
		if (cc == colors.length < 1) { cc = 0; };		
	};

	/*
		Init function for page JS
	*/
	this.init = function(){
		bootData();		
	};
};