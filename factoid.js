
	function ajaxSubmit(subform)
	{
		fa_do_loading("fa_alertdiv","<b>Submitting ...</b> ");
		var alertdiv = jQuery('#fa_alertdiv');
		alertdiv.dialog({
			'height' : 260,
			'width' : 400,
			'closeOnEscape' : true,
			'draggable' : false,
			'resizable' : false,
			'dialogClass' : 'wp-dialog',
			modal : true,
			buttons: [{ 
				text : "Close",
				click : function() {
					jQuery(this).dialog("close");
				} } ]
		});
		jQuery('.ui-dialog-titlebar').hide();
		jQuery(".ui-dialog-buttonpane button:contains('Close')").button("disable");
			
		if(subform.action.value == "add")
		{
			var myxmlobj = fa_getXMLobj("POST");
			if(myxmlobj)
			{
				myxmlobj.timeout = 5000;
				myxmlobj.ontimeout = function() {
					alert("The request timed out.");
					alertdiv.html("The request timed out.");
					jQuery(".ui-dialog-buttonpane button:contains('Close')").button("enable");
					jQuery(".ui-dialog-buttonpane button:contains('Close')").focus();
				};
				myxmlobj.onreadystatechange = function(){
					//alert("ready state = " + myxmlobj.readyState);
					if(myxmlobj.readyState == 4)
					{
						var cType = myxmlobj.getResponseHeader("Content-Type");
						//alert("Response type: " + cType + "\nThe response was:\n\n" + myxmlobj.response)
						if (cType == 'text/xml') 
						{
							// XML response
							var xmlDoc = myxmlobj.responseXML;
							if(xmlDoc)
							{
								if( xmlDoc.getElementsByTagName('error')[0] )
								{
									// there was an error
									var textout = "<b>Error: " + xmlDoc.getElementsByTagName('error')[0].textContent + "</b><br /><br />\n";
									textout = textout + xmlDoc.getElementsByTagName('detail')[0].textContent;
									alertdiv.html(textout);
								}
								else
								{
									// saved succesfully!
									alertdiv.html(xmlDoc.getElementsByTagName('short')[0].textContent + "\n<br />\n" + xmlDoc.getElementsByTagName('detail')[0].textContent);
									document.getElementById("fa_content").value = '';
									document.getElementById("fa_headline").value = '';
								}
							}
							else
							{
								alertdiv.html("Request completed successfully, but there was a problem with the xml.\n<br />" + myxmlobj.response + "\n");
							}
						} 
						else if (cType == 'text/plain') 
						{
							// plain text response
								alertdiv.html("Request completed successfully.\n<br />(Raw response follows)<br /><br />\n" + myxmlobj.responseText + "\n");
						} else {
							//alert('unknown content type');
							alertdiv.html("Request completed successfully, but received an unknown content type ()" + cType + ".\n<br />" + myxmlobj.response + "\n");
						}
						jQuery(".ui-dialog-buttonpane button:contains('Close')").button("enable");
						jQuery(".ui-dialog-buttonpane button:contains('Close')").focus();
					}
				};
				myxmlobj.setRequestHeader("Content-type","application/x-www-form-urlencoded");
				// this creates a jquery array of objects containing all the form data
				//alert("Generating parameters.");
				var serparams = jQuery(subform).serializeArray();
				//alert("serparams = \n" + serparams);
				// this turns that array into a string suitable for a URI
				var myparams = jQuery.param(serparams);
				//alert("myparams = \n" + myparams);
				myxmlobj.send(myparams);
				//alert("Ajax request sent.");
			}
			else
			{
				alert("Your browser is too old to support this functionality.");
				alertdiv.html("(Cannot complete request)");
				jQuery(".ui-dialog-buttonpane button:contains('Close')").button("enable");
				jQuery(".ui-dialog-buttonpane button:contains('Close')").focus();
			}
		}
	}
	
	function fa_getFactoid(divname,type,category,sfw)
	{
		var containerdiv = jQuery('#' + divname);
		var headerdiv = jQuery('#' + divname + "header");
		var contentdiv = jQuery('#' + divname + "content");
		var sourcediv = jQuery('#' + divname + "source");
		headerdiv.hide();
		sourcediv.hide();
		fa_do_loading(divname + "content","Fetching ... ");

		var myxmlobj = fa_getXMLobj("POST");
		if(myxmlobj)
		{
			myxmlobj.timeout = 4000;
			myxmlobj.ontimeout = function() {
				containerdiv.html("The request timed out.");
			};
			myxmlobj.container = divname;
			myxmlobj.onreadystatechange = function(){
				if(myxmlobj.readyState == 4)
				{
					var cType = myxmlobj.getResponseHeader("Content-Type");
					if(cType === null)
					{
						containerdiv.html("null response from server");				
					}
					if (cType === 'text/xml') 
					{
						// XML response
						var xmlDoc = myxmlobj.responseXML;
						if(xmlDoc)
						{
							if( xmlDoc.getElementsByTagName('error')[0] )
							{
								// there was an error
								var textout = "<b>Error: " + xmlDoc.getElementsByTagName('error')[0].textContent + "</b><br />\n";
								if( xmlDoc.getElementsByTagName('detail')[0] )
								{	
									textout = textout + xmlDoc.getElementsByTagName('detail')[0].textContent;
								}
								containerdiv.html(textout);
							}
							else
							{
								// fetched succesfully!
								var mytype = xmlDoc.getElementsByTagName('type')[0].textContent;
								if( 1 == mytype )
								{
									if(xmlDoc.getElementsByTagName('header')[0].textContent)
									{
										headerdiv.html(xmlDoc.getElementsByTagName('header')[0].textContent);								
										headerdiv.show();
									}
									else
									{
										headerdiv.hide();
									}
									contentdiv.html(xmlDoc.getElementsByTagName('content')[0].textContent);
									sourcediv.hide();
								}
								if( 2 == mytype )
								{
									headerdiv.hide();
									contentdiv.html('"' + xmlDoc.getElementsByTagName('content')[0].textContent + '"');
									sourcediv.html(xmlDoc.getElementsByTagName('author')[0].textContent);
									sourcediv.show();
								}
//								containerdiv.html(myxmlobj.response);
							}
						}
						else
						{
							containerdiv.html("Request completed successfully, but there was a problem with the xml.\n<br />" + myxmlobj.response + "\n");
						}
					}
				}
			};
			myxmlobj.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			var myparams = "c=get&type=" + type + "&category=" + category + "&sfw=" + sfw + "&d=" + window.location.host + "&pt=" + encodeURI(window.location.pathname);
			myxmlobj.send(myparams);
		}
		else
		{
			contentdiv.html("(Old browser: Cannot complete request)");
		}
	}

	function fa_getXMLobj(type)
	{
	//	var xmlhttp = newRequest();
		var xmlhttp = new XMLHttpRequest();
		if ("withCredentials" in xmlhttp) 
		{
			// Check if the XMLHttpRequest object has a "withCredentials" property.
			// "withCredentials" only exists on XMLHTTPRequest2 objects.
			xmlhttp.open(type, "http://www.october.com.au/cgi-bin/factoidapi.cgi", true );
			return xmlhttp;
		} else if (typeof XDomainRequest !== "undefined") {
			// Otherwise, check if XDomainRequest.
			// XDomainRequest only exists in IE, and is IE's way of making CORS requests.
			xmlhttp = new XDomainRequest();
			xmlhttp.open(type, "http://www.october.com.au/cgi-bin/factoidapi.cgi?t=" + Math.random() + "&", true );
			return xmlhttp;
		} else {
			return null;
		}
	}

	function fa_do_loading(target, text)
	{
		if( text.length < 1 )
		{ 
			text = "<b>Loading ...</b>"; 
		}
		var mydiv = document.getElementById(target);
//		alert("do_loading() looking for " + target + ", found " + mydiv);
		mydiv.innerHTML = "<img src='http://october.com.au/soft/ajax-loader.gif'> " + text;
	}
