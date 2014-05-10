$(function(){
	// only if we are on an org view page
	if(window.location.pathname.indexOf('organizations/view') > -1){
		// get the org name
		var org_name = $('#page_title').text();
	
		$('.silverleafbadge').colorbox({
			html:'<div style="background: linear-gradient(#EEB211, #F5F5F5) repeat scroll 0 0 rgba(0, 0, 0, 0); padding: 5px 10px 15px"> \
	        <div style="font-size:30px; padding: 5px 0px; border-bottom:1px dotted #666666">Silver Leaf Certified</div> \
	        <br> \
	        <img src="/img/silverleafcertified.png" style="float:left;height:185px"> \
	        <p>This award certifies that '+org_name+' has attained the highest degree of sustainability merit at Georgia Tech. This award is sponsored by the Georgia Tech Student Government Association and it is a commitment to continue adopting eco-friendly habits through the help of The Office of Solid Waste Management.</p> \
	        <div style="font-size:16px; padding: 10px 5px 5px 0px; margin-bottom:5px; border-bottom:1px dotted #666666; clear:both">Ready to become Silver Leaf Certified?</div> \                                                      \
	        Visit the <a href="http://sga.gatech.edu/green">Student Government Association</a> site to learn more about how your can become certified. \
	    </div>',
			transition: 'none',
			title: '',
			width: '720px',
			opacity: 0.7,
			top: '10%'
		});
	}
});