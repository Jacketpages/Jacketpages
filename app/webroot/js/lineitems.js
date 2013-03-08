/**
 * @author Stephen Roca
 * @since 11/17/2012
 */

/**
 * Calls document.getElementById on the id given
 * @param String id
 */
function get(id)
{
	return document.getElementById(id);
}

/**
 * Moves a specific line item down one
 * @param int pos - row position
 */
function moveDown(pos)
{
	move(pos, pos + 1);
}

/**
 * Moves a specific line item up one
 * @param int pos - row position
 */
function moveUp(pos)
{
	move(pos, pos - 1);
}

/**
 * A helper function to implement moveUp and moveDown. Does the actual work of
 * the two methods
 * @param int pos - row position
 * @param int moveTo - row position to move to
 */
function move(pos, moveTo)
{
	var rows = get("LineItemsTable").rows;
	// If the position to move to is not first row (table headers) and it is defined
	// then swap the two rows
	if (moveTo > 0 && !(rows[moveTo] == undefined))
	{
		var name = get("LineItemName" + pos).getAttribute("value");
		var cost = get("LineItemCostPerUnit" + pos).getAttribute("value");
		var qty = get("LineItemQuantity" + pos).getAttribute("value");
		var tc = get("LineItemTotalCost" + pos).getAttribute("value");
		var amt = get("LineItemAmount" + pos).getAttribute("value");
		get("LineItemName" + pos).setAttribute("value", get("LineItemName" + moveTo).getAttribute("value"));
		get("LineItemCostPerUnit" + pos).setAttribute("value", get("LineItemCostPerUnit" + moveTo).getAttribute("value"));
		get("LineItemQuantity" + pos).setAttribute("value", get("LineItemQuantity" + moveTo).getAttribute("value"));
		get("LineItemTotalCost" + pos).setAttribute("value", get("LineItemTotalCost" + moveTo).getAttribute("value"));
		get("LineItemAmount" + pos).setAttribute("value", get("LineItemAmount" + moveTo).getAttribute("value"));
		get("LineItemName" + moveTo).setAttribute("value", name);
		get("LineItemCostPerUnit" + moveTo).setAttribute("value", cost);
		get("LineItemQuantity" + moveTo).setAttribute("value", qty);
		get("LineItemTotalCost" + moveTo).setAttribute("value", tc);
		get("LineItemAmount" + moveTo).setAttribute("value", amt);
	}
}

/**
 * Adds a blank row to the table of lineitems.
 * @param int pos - row position
 */
function addRow(pos)
{
	var moveTo = pos + 1;
	var table = get("LineItemsTable");
	table.insertRow(moveTo);
	table.rows[moveTo].innerHTML = table.rows[pos].innerHTML;
	var rows = get("LineItemsTable").rows;
	var cells = rows[moveTo].cells;
	// Since all of the elements that were added have previous values, the old values
	// need to be removed.
	for (var i = 0; i < cells.length; i++)
	{
		if (!(cells[i].getElementsByTagName("input")[0] == undefined))
			cells[i].getElementsByTagName("input")[0].setAttribute("value", "");
	}
	correctNumbers();
}

/**
 * Deletes a row from the table of lineitems.
 * @param int pos - row position
 */
function deleteRow(pos)
{
	var table = get("LineItemsTable");
	table.deleteRow(pos);
	correctNumbers();
}

/**
 * A helper method for deleteRow and addRow that corrects the line numbers of the
 * line items as well as making sure the buttons refer to the correct line item.
 */
function correctNumbers()
{
	var rows = get("LineItemsTable").rows;
	// Go through the rows and make sure their ids and js methods refer to the
	// correct row number.
	for (var i = 1; i < rows.length; i++)
	{
		var cells = rows[i].cells;
		cells[0].getElementsByTagName("label")[0].setAttribute("id", "LineItemLineNumber" + i);
		cells[0].getElementsByTagName("label")[0].setAttribute("value", i);
		cells[0].getElementsByTagName("label")[0].innerHTML = i;
		cells[1].getElementsByTagName("input")[0].setAttribute("id", "LineItemState" + i);
		cells[1].getElementsByTagName("input")[0].setAttribute("value", i);
		cells[1].getElementsByTagName("input")[0].setAttribute("name", "data[LineItem][" + i + "][state]");
		cells[2].getElementsByTagName("input")[0].setAttribute("id", "LineItemName" + i);
		cells[2].getElementsByTagName("input")[0].setAttribute("name", "data[LineItem][" + i + "][name]");
		cells[3].getElementsByTagName("input")[0].setAttribute("id", "LineItemCostPerUnit" + i);
		cells[3].getElementsByTagName("input")[0].setAttribute("name", "data[LineItem][" + i + "][cost_per_unit]");
		cells[4].getElementsByTagName("input")[0].setAttribute("id", "LineItemQuantity" + i);
		cells[4].getElementsByTagName("input")[0].setAttribute("name", "data[LineItem][" + i + "][quantity]");
		cells[5].getElementsByTagName("input")[0].setAttribute("id", "LineItemTotalCost" + i);
		cells[5].getElementsByTagName("input")[0].setAttribute("name", "data[LineItem][" + i + "][total_cost]");
		cells[6].getElementsByTagName("input")[0].setAttribute("id", "LineItemAmount" + i);
		cells[6].getElementsByTagName("input")[0].setAttribute("name", "data[LineItem][" + i + "][amount]");
		cells[7].getElementsByTagName("button")[0].setAttribute("onclick", "moveUp(" + i + ")");
		cells[8].getElementsByTagName("button")[0].setAttribute("onclick", "moveDown(" + i + ")");
		cells[9].getElementsByTagName("button")[0].setAttribute("onclick", "addRow(" + i + ")");
		cells[10].getElementsByTagName("button")[0].setAttribute("onclick", "deleteRow(" + i + ")");
	}
}