(function($){

	$("#main").addClass("fullpage")
	$(".post-content .fusion-fullwidth").addClass("section");
	

	var myFullpage = new fullpage('.fullpage', {
        sectionsColor: data.sectionColors,
        anchors: data.menu,
        navigation:true,
        navigationTooltips: data.menu,
        showActiveTooltip: true,
        menu: '#menus'
    });

    console.log(data);
    console.log(data.sectionColors);
    console.log(data.autoScrolling);
    console.log(data.menu);

})(jQuery);