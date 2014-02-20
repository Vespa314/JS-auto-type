$.fn.autotype = function() {
			var $tt = $(this);
		  var str = $tt.html();
			var index = 0;
			$(this).html('');
			var timer = setInterval(function() {
				
				var current = str.substr(index, 1);
				//ºÏ≤‚±Í«©
				if (current == '<') 
					index = str.indexOf('>', index) + 1;
				else 
					index++;
				
				
				$tt.html(str.substring(0, index) + (index & 1 ? '_' : ''));
				
				if (index >= str.length){ 
					clearInterval(timer);
					$("#startbutton").css("display","inline");
				}
			}, 55);
	};

function buttonclick(){
	$("#startbutton").css("display","none");
	$("#autotype").css("display","inline");
	$("#autotype").autotype();
	//
}