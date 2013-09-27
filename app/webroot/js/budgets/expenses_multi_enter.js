/**
 * @author Stephen Roca
 * @since 9/12/2013
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
	var rows = get("ExpensesTable").rows;
	// If the position to move to is not first row (table headers) and it is defined
	// then swap the two rows
	if (moveTo >= 0 && rows.length >= moveTo + 3)
	{
		// Save the values from the current row
		var id = get("ExpenseId" + pos).value;
		var item = get("Item" + pos).value;
		var expense = get("Amount" + pos).value;

		// Set the values of the current row to the row that of the row being moved to
		get("ExpenseId" + pos).value = get("ExpenseId" + moveTo).value;
		get("Item" + pos).value = get("Item" + moveTo).value;
		get("Amount" + pos).value = get("Amount" + moveTo).value;

		// Set the row being moved to, to the values of the current row
		get("ExpenseId" + moveTo).value = id;
		get("Item" + moveTo).value = item;
		get("Amount" + moveTo).value = expense;
	}
}

/**
 * Adds a blank row to the table of lineitems.
 * @param int pos - row position
 */
function addRow(pos)
{
	pos = pos + 1;
	var moveTo = pos + 1;
	var table = get("ExpensesTable");
	table.insertRow(moveTo);
	table.rows[moveTo].innerHTML = table.rows[pos].innerHTML;
	var rows = get("ExpensesTable").rows;
	var cells = rows[moveTo].cells;
	// Since all of the elements that were added have previous values, the old values
	// need to be removed.
	for (var i = 0; i < cells.length; i++)
	{
		for (var j = 0; j < cells[i].getElementsByTagName("input").length; j++)
		{
			if (!(cells[i].getElementsByTagName("input")[j] == undefined))
				cells[i].getElementsByTagName("input")[j].setAttribute("value", "");
		}
	}
	correctNumbers();
}

/**
 * Deletes a row from the table of lineitems.
 * @param int pos - row position
 */
function deleteRow(pos)
{
	pos = pos + 1;
	var table = get("ExpensesTable");
	if (table.rows.length != 3)
	{
		table.deleteRow(pos);
		correctNumbers();
	}
}

/**
 * A helper method for deleteRow and addRow that corrects the line numbers of the
 * line items as well as making sure the buttons refer to the correct line item.
 */
function correctNumbers()
{
	var rows = get("ExpensesTable").rows;
	// Go through the rows and make sure their ids and js methods refer to the
	// correct row number.

	for (var i = 0; i < rows.length - 2; i++)
	{
		var cells = rows[i + 1].cells;

		cells[0].getElementsByTagName("input")[0].setAttribute("id", "ExpenseId" + i);
		cells[0].getElementsByTagName("input")[0].setAttribute("name", "data[" + i + "][Expense][id]");
		cells[0].getElementsByTagName("input")[1].setAttribute("id", "Item" + i);
		cells[0].getElementsByTagName("input")[1].setAttribute("name", "data[" + i + "][Expense][item]");
		cells[1].getElementsByTagName("input")[0].setAttribute("id", "Amount" + i);
		cells[1].getElementsByTagName("input")[0].setAttribute("name", "data[" + i + "][Expense][amount]");
		// cells[2].getElementsByTagName("input")[0].setAttribute("id", "LineItemCostPerUnit" + i);
		// $("#LineItemCostPerUnit" + i).attr('onchange', "updateTCAndRqstd("+i+")");
		// cells[2].getElementsByTagName("input")[0].setAttribute("name", "data[" + i + "][LineItem][cost_per_unit]");
		// cells[3].getElementsByTagName("input")[0].setAttribute("id", "LineItemQuantity" + i);
		// $("#LineItemQuantity" + i).attr('onchange', "updateTCAndRqstd("+i+")");
		// cells[3].getElementsByTagName("input")[0].setAttribute("name", "data[" + i + "][LineItem][quantity]");
		// cells[4].getElementsByTagName("input")[0].setAttribute("id", "LineItemTotalCost" + i);
		// cells[4].getElementsByTagName("input")[0].setAttribute("name", "data[" + i + "][LineItem][total_cost]");
		// cells[5].getElementsByTagName("input")[0].setAttribute("id", "LineItemAmount" + i);
		// cells[5].getElementsByTagName("input")[0].setAttribute("name", "data[" + i + "][LineItem][amount]");
		// cells[6].firstChild.firstChild.setAttribute("id", "LineItemAccount" + i);
		// $("#LineItemAccount" + i).attr('onchange', "updateTCAndRqstd("+i+")");
		// cells[6].firstChild.firstChild.setAttribute("name", "data[" + i + "][LineItem][account]");
		cells[2].getElementsByTagName("button")[0].setAttribute("onclick", "moveUp(" + i + ")");
		cells[3].getElementsByTagName("button")[0].setAttribute("onclick", "moveDown(" + i + ")");
		cells[4].getElementsByTagName("button")[0].setAttribute("onclick", "addRow(" + i + ")");
		cells[5].getElementsByTagName("button")[0].setAttribute("onclick", "deleteRow(" + i + ")");

	}
}