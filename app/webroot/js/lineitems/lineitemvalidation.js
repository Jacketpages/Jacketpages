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
		if($("#LineItemAccount" + (i)).val() == "CO")
		{
			exactValueRule("#LineItemAmount" + (i), $("#LineItemQuantity" + (i)).val() * $("#LineItemCostPerUnit" + (i)).val() *.75);
		}
		else
		{
			exactValueRule("#LineItemAmount" + (i), $("#LineItemQuantity" + (i)).val() * $("#LineItemCostPerUnit" + (i)).val());
		}
	}
	alreadyValidated = true;
}
