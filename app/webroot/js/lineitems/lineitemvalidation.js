function assignAndRunValidationRules()
{
	var rows = get("LineItemsTable").rows;
	for (var i = 0; i < rows.length - 1; i++)
	{
		notEmptyRule("#LineItemName" + (i));
		moneyRule("#LineItemCostPerUnit" + (i));
		numberRule("#LineItemQuantity" + (i));
		moneyRule("#LineItemTotalCost" + (i));
		moneyRule("#LineItemAmount" + (i));
		//MRE: I don't know why this is here since you can't edit total cost anyway
		//...It just throws errors due to rounding...
		//exactValueRule("#LineItemTotalCost" + (i), $("#LineItemQuantity" + (i)).val() * $("#LineItemCostPerUnit" + (i)).val());
	}
	alreadyValidated = true;
}
