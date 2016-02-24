(function($) {
	$.entwine('ss', function($) {

		$('.add-existing-dropdown .dropdown').entwine({
			onmatch: function(e)
			{
				$(".add-existing-dropdown .dropdown").chosen()
			},

			onchange: function(e)
			{
				var itemValue = $(this).val();
				var addbutton = $(this).closest(".add-existing-dropdown").find("button");

				if(itemValue){
					addbutton.button('enable');
				}else{
					addbutton.button('disable');
				}
			}
		});
	});
}(jQuery));
