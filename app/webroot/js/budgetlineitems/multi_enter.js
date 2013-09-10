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
function moveDown(tableId, pos)
{
	move(tableId, pos, pos + 1);
}

/**
 * Moves a specific line item up one
 * @param int pos - row position
 */
function moveUp(tableId, pos)
{
	move(tableId, pos, pos - 1);
}

/**
 * A helper function to implement moveUp and moveDown. Does the actual work of
 * the two methods
 * @param int pos - row position
 * @param int moveTo - row position to move to
 */
function move(tableId, pos, moveTo)
{
	var rows = get(tableId).rows;
	var unique = tableId.substring(tableId.length-1, tableId.length);
	// If the position to move to is not first row (table headers) and it is defined
	// then swap the two rows
	if (moveTo >= 0 && !(rows[moveTo] == undefined))
	{
		// Save the values from the current row
		var id = get(unique + "BudgetLineItemId" + pos).value;
		var name = get(unique + "BudgetLineItemName" + pos).value;
		var amt = get(unique + "BudgetLineItemAmount" + pos).value;

		// Set the values of the current row to the row that of the row being moved to
		get(unique + "BudgetLineItemId" + pos).value = get(unique + "BudgetLineItemId" + moveTo).value;
		get(unique + "BudgetLineItemName" + pos).value = get(unique + "BudgetLineItemName" + moveTo).value;
		get(unique + "BudgetLineItemAmount" + pos).value = get(unique + "BudgetLineItemAmount" + moveTo).value;

		// Set the row being moved to, to the values of the current row
		get(unique + "BudgetLineItemId" + moveTo).value = id;
		get(unique + "BudgetLineItemName" + moveTo).value = name;
		get(unique + "BudgetLineItemAmount" + moveTo).value = amt;
	}
}

/**
 * Adds a blank row to the table of lineitems.
 * @param int pos - row position
 */
function addRow(tableId, pos, num)
{
	pos = pos + 1;
	var moveTo = pos + 1;
	var table = get(tableId);
	table.insertRow(moveTo);
	table.rows[moveTo].innerHTML = table.rows[pos].innerHTML;
	var rows = get(tableId).rows;
	var cells = rows[moveTo].cells;
	// Since all of the elements that were added have previous values, the old values
	// need to be removed.
	for (var i = 0; i < cells.length; i++)
	{
		if (!(cells[i].getElementsByTagName("input")[0] == undefined))
			cells[i].getElementsByTagName("input")[0].setAttribute("value", "");
	}
	correctNumbers(tableId, num);
}

/**
 * Deletes a row from the table of lineitems.
 * @param int pos - row position
 */
function deleteRow(tableId, num, pos)
{
	pos = pos + 1;
	var table = get(tableId);
	table.deleteRow(pos);
	correctNumbers(tableId, num);
}

/**
 * A helper method for deleteRow and addRow that corrects the line numbers of the
 * line items as well as making sure the buttons refer to the correct line item.
 */
function correctNumbers(tableId, num)
{
	var rows = get(tableId).rows;
	// Go through the rows and make sure their ids and js methods refer to the
	// correct row number.

	for (var i = 1; i < rows.length - 1; i++)
	{
		var cells = rows[i + 1].cells;
		cells[0].getElementsByTagName("input")[0].setAttribute("id", num + "BudgetLineItemId" + i);
		cells[0].getElementsByTagName("input")[0].setAttribute("name", "data[" + i + "][LineItem][id]");
		cells[0].getElementsByTagName("input")[1].setAttribute("id", num + "BudgetLineItemName" + i);
		cells[0].getElementsByTagName("input")[0].setAttribute("name", "data[" + i + "][LineItem][name]");
		cells[2].getElementsByTagName("input")[0].setAttribute("id", num + "BudgetLineItemAmount" + i);
		cells[2].getElementsByTagName("input")[0].setAttribute("name", "data[" + i + "][LineItem][amount]");
		cells[4].getElementsByTagName("button")[0].setAttribute("onclick", "moveUp('" + tableId + "'," + i + ")");
		cells[5].getElementsByTagName("button")[0].setAttribute("onclick", "moveDown('" + tableId + "'," + i + ")");
		cells[6].getElementsByTagName("button")[0].setAttribute("onclick", "addRow('" + tableId + "'," + num + "," + i + ")");
		cells[7].getElementsByTagName("button")[0].setAttribute("onclick", "deleteRow('" + tableId + "'," + num + "," + i + ")");

	}
}