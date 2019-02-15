(function($){

	$("#main").addClass("fullpage")
	$(".post-content .fusion-fullwidth").addClass("section");
	

	var myFullpage = new fullpage('.fullpage', {
        sectionsColor: ['#152121', '#152121', '#152121', '#152121', '#152121'],
        anchors: ['home', 'vision', 'description', 'whatsinforme', 'team'],
        navigation:true,
        navigationTooltips: ['Home', 'Vision', 'Description', 'Whats in for me?', 'Team'],
        showActiveTooltip: true,
        menu: '#menus'
    });

})(jQuery);