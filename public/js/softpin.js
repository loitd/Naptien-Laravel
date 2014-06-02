$(document).ready(function(){
	//feed the providers
	var providers = $.getJSON("softpin/providers", function(data){
		//console.log(data);
		$.each(data, function(key, val){
			//console.log(key + "::" + val);
			$("select#nph").append("<option value='" + key + "'>" + val + "</option>");
		});
	});
	
	$('#nph').change(function(){
		var e = document.getElementById("nph");
		var whatselected = e.options[e.selectedIndex].value;
		//clear the old value
		$("select#sotien").empty();
		//add the first option -> not make user suprise
		$("select#sotien").append("<option value='" + "'>" + "Hãy chọn mệnh giá thẻ" + "</option>");
		
		if (whatselected != "") {
			//feed the amount
			var providers = $.getJSON("softpin/"+whatselected, function(data){
				//console.log(data);
				$.each(data, function(key, val){
					//console.log(key + "::" + val);
					$("select#sotien").append("<option value='" + key + "'>" + val + "</option>");
				});
			});


			//alert(whatselected);	
		};
		
	});


	
});