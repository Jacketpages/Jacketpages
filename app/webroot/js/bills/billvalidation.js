/**
 * @author Stephen Roca
 * @since 7/29/2013
 */

function assignAndRunValidationRules()
{
	notEmptyRule("#BillTitle");
	notEmptyRule("#BillDescription");
	//notEmptyRule("#AuthorsUndrAuthId", "You must specify an author.");
	//notEmptyRule("#AuthorsGradAuthId", "You must specify an author.");
	notEmptyRule("#BillOrgId", "You must specify an organization.");

	var rows = get("LineItemsTable").rows;
	if (hasLineItems())
	{
		for (var i = 0; i < rows.length - 1; i++)
		{
			notEmptyRule("#LineItemName" + (i));
			moneyRule("#LineItemCostPerUnit" + (i));
			numberRule("#LineItemQuantity" + (i));
			moneyRule("#LineItemTotalCost" + (i));
			moneyRule("#LineItemAmount" + (i));
		}
	}
	alreadyValidated = true;
}

function hasLineItems()
{
	return !($("#LineItemName0").val() == "" && $("#LineItemCostPerUnit0").val() == "" 
		&& $("#LineItemQuantity0").val() == "" && $("#LineItemTotalCost0").val() == "" 
		&& $("#LineItemAmount0").val() == "");
}
