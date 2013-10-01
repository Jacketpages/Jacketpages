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
		exactValueRule("#LineItemTotalCost" + (i), $("#LineItemQuantity" + (i)).val() * $("#LineItemCostPerUnit" + (i)).val());
	}
	alreadyValidated = true;
}
