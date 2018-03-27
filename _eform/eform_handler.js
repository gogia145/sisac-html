<!--

/**************************************************************************/
/************* Script Works Generic Form Handler *** version v1.070321.0  */
/**************************************************************************/

function form_action() {
		var actionpath = ""; //path if handler script not in the root
		var actionfile = eform_handler; //"eform_handler";
		var actionextension = "php"; //"php";
		document.forms.f.action = actionpath + actionfile + "." + actionextension;
		
		for (var t=0; t<document.forms.f.length; t=t+1) {
			var required 			= document.forms.f.elements[t].name.substr(0,4);
			var customer_email_name = document.forms.f.elements[t].name.substr(0,19);

			if(required=="req_") {
				if(document.forms.f.elements[t].type=="text" || document.forms.f.elements[t].type=="textarea") {
					if(customer_email_name=="req_email_recipient") { 
						if(document.forms.f.elements[t].value == "" 
		|| !checkEmail(document.forms.f.elements[t].value)) {
							alert(getTextName(document.forms.f.elements[t].name));
							document.forms.f.elements[t].focus();
							document.forms.f.elements[t].select();
							return(false);
						}
					}
					else {
						if(document.forms.f.elements[t].value == "") {
							alert(getTextName(document.forms.f.elements[t].name));
							document.forms.f.elements[t].focus();
							return(false);
						}
					}
				}
				if(document.forms.f.elements[t].type=="checkbox"){
					if(checkcheckbox(document.forms.f.elements[t].name)){
						alert(getTextName(document.forms.f.elements[t].name));
						document.forms.f.elements[t].focus();
						return(false);
					}
				}
				if(document.forms.f.elements[t].type=="select-one"){
					if(!checkselect(document.forms.f.elements[t].name)){
						alert(getTextName(document.forms.f.elements[t].name));
						document.forms.f.elements[t].focus();
						return(false);
					}
				}
				if(document.forms.f.elements[t].type=="radio"){
					if(checkradio(document.forms.f.elements[t].name)){
						alert(getTextName(document.forms.f.elements[t].name));
						document.forms.f.elements[t].focus();
						return(false);
					}
				}
			}
			
		}
		return true;
	}
	
	function checkEmail(email) {
	if (/\S+@\S+\.\S{2,3}/.exec(email) == null)
		return false;
	else
		return true;
	}
	
	function checkcheckbox(CheckboxName)
	{
		checkboxfield = document.forms.f[CheckboxName];
	 	if (checkboxfield.checked) 
		{ return(false); }
	 	else 
		{ return(true); }
	}
	
	function checkselect(SelectName)
	{
	 	selectfield = document.forms.f[SelectName];
	 	if (selectfield.options[0].selected == true)
		{ return(false); }
	 	else 
		{ return(true); }
	}
	
	function checkradio(RadioName)
	{
	 	found = false;
	 	radiofield = document.forms.f[RadioName];
	 	for (r = 0; r < radiofield.length; r++) {
	  		if (radiofield[r].checked) {
	   			found = true;
	  		}
	 	}
		if (!found)
		{ return(true); }
	 	else 
		{ return(false); }
	}
	
	function getTextName(field_name) {
		for (var i=0; i <= field_name_array.length; i++) {
			if(field_name_array[i]==field_name)
			{ return "Please enter a value for the " + field_text_name_array[i] + " field."; }
		}
		return "Please enter a value for this field.";
	}

//-->
