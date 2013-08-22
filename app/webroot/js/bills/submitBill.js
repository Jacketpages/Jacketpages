function hideLineItems()
{
	if ($('#BillType').val().toString().localeCompare('Resolution') == 0)
	{
		$('.multi_enter_line_items').hide();
	}
	else
	{
		$('.multi_enter_line_items').show();
	}
}
