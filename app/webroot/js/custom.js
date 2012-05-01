function displayAuthors(cat) {
	if(cat == "joint"){
		$(".underAuthorId").fadeIn("slow");
		$(".gradAuthorId").fadeIn("slow");
	}else if(cat == "grad"){
		$(".underAuthorId").fadeOut("slow");
		$(".gradAuthorId").fadeIn("slow");
	}else if(cat == "undergrad"){
		$(".underAuthorId").fadeIn("slow");
		$(".gradAuthorId").fadeOut("slow");
	}
}

$(document).ready(function(){
	
	ddsmoothmenu.init({
	mainmenuid: "utilityBar", //menu DIV id
	orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu', //class added to menu's outer DIV
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})

});
