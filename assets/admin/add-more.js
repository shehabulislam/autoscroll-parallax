(function($){
$(document).ready(function(){

	$(".add-menu").on("click", function(){
		var menu = $("#menu-name").val();
		if(menu == ''){
			return false;
		}
		$("table.menu-add").append('<tr><td><input type="checkbox" name="menu"></td><td><input type="hidden" name="rj_fullpage_menu_add[]" value="'+menu+'" /></td><td><h3>'+menu+'</h3></td</tr>');
		$("#menu-name").val('');
	});

	$(".delete-menu").on("click", function(){
		$("table.menu-add tr td input[type='checkbox']:checked").each(function(){
			$(this).closest("tr").remove();
		});
	});

	$(".add-row").on("click", function(){
		var color = $("#color").val();
		if(color == ''){
			return false;
		}
		$("table.section-color").append('<tr class="tr"><td><input type="checkbox" name="section"></td><td><h3>'+'1'+'</h3></td><td><input type="text" name="rj_fullpage_section_color_add[]" value="'+color+'" /></td></tr>');
		$("#color").val('');
	});

	$(".delete-menu").on("click", function(){
		$("table.section-color tr td input[type='checkbox']:checked").each(function(){
			$(this).closest("tr").remove();
		});
	});


})
})(jQuery);