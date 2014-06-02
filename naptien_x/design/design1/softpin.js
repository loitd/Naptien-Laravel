$(document).ready(function(){
	var providers = $.getJSON("http://192.168.1.19/naptien/public/softpin/providers", function(data){
		var items = [];
		$.each( data, function( key, val ) {
			$("select#nph").append("<option value='" + val + "'>" + val + "</option>");
		});
		 
		
	});
	
	$('#nph').change(function(){
		var e = document.getElementById("nph");
		var whatselected = e.options[e.selectedIndex].value;
		
		if (whatselected != "") {
			






			alert(whatselected);	
		};
		
	});
});