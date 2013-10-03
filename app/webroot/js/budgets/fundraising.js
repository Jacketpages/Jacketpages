function updateIncome(row)
{
	var members = $('#' + row +'DuesMembers').val();
	var amount =	$('#' + row +'DuesAmount').val();
	var newIncome = parseFloat(members * amount).toFixed(2);
	$('#' + row +'DuesIncome').val('$' + newIncome);
	updateTotal();
}

function updateTotal()
{
	var total = 0;
	for(var i = 0; i < 4; i++)
	{
		var value = $('#' + i +'DuesIncome').val();
		total = total + parseFloat(value.substring(1,value.length));
	}
	var stuff = parseFloat(total).toFixed(2);
	document.getElementById('DuesTotal').innerHTML = '$' + stuff;
}
