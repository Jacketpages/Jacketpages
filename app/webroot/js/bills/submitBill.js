function hideLineItems()
{
	if ($('#BillType').val().toString().localeCompare('Resolution') == 0)
	{
		$('.multi_enter_line_items').fadeOut("slow");
	}
	else
	{
		$('.multi_enter_line_items').fadeIn("slow");
	}
};

function hideAuthors()
{
	var cat = $("#categoryChoice option:selected").val();
	if(cat == "Joint") {
		$(".underAuthor_id").fadeIn("slow");
		$(".gradAuthor_id").fadeIn("slow");
	} else if(cat == "Graduate") {
		$(".underAuthor_id").fadeOut("slow");
		$(".gradAuthor_id").fadeIn("slow");
	} else if(cat == "Undergraduate") {
		$(".underAuthor_id").fadeIn("slow");
		$(".gradAuthor_id").fadeOut("slow");
	}
};
