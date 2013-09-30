/**
 * @author Stephen Roca
 * @since 9/27/2013
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
function lmoveDown(pos)
{
	lmove(pos, pos + 1);
}

/**
 * Moves a specific line item up one
 * @param int pos - row position
 */
function lmoveUp(pos)
{
	lmove(pos, pos - 1);
}

/**
 * A helper function lto implement moveUp and moveDown. Does the actual work of
 * the two methods
 * @param int pos - row position
 * @param int moveTo - row position to move to
 */
function lmove(pos, moveTo)
{
	var rows = get("LiabilitiesTable").rows;
	// If the position to move to is not first row (table headers) and it is defined
	// then swap the two rows
	if (moveTo >= 0 && rows.length >= moveTo + 2)
	{
		// Save the values from the current row
		var id = get("LiabilityId" + pos).value;
		var item = get("Item" + pos).value;
		var expense = get("Amount" + pos).value;

		// Set the values of the current row to the row that of the row being moved to
		get("LiabilityId" + pos).value = get("LiabilityId" + moveTo).value;
		get("Item" + pos).value = get("Item" + moveTo).value;
		get("Amount" + pos).value = get("Amount" + moveTo).value;

		// Set the row being moved to, to the values of the current row
		get("LiabilityId" + moveTo).value = id;
		get("Item" + moveTo).value = item;
		get("Amount" + moveTo).value = expense;
	}
}

/**
 * Adds a blank row to the table of lineitems.
 * @param int pos - row position
 */
function laddRow(pos)
{
	pos = pos + 1;
	var moveTo = pos + 1;
	var table = get("LiabilitiesTable");
	table.insertRow(moveTo);
	table.rows[moveTo].innerHTML = table.rows[pos].innerHTML;
	var rows = get("LiabilitiesTable").rows;
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
	lcorrectNumbers();
}

/**
 * Deletes a row from the table of lineitems.
 * @param int pos - row position
 */
function ldeleteRow(pos)
{
	pos = pos + 1;
	var table = get("LiabilitiesTable");
	if (table.rows.length != 2)
	{
		table.deleteRow(pos);
		lcorrectNumbers();
	}
	else
	{
		for (var i = 0; i < table.rows[1].cells.length; i++)
		{
			for (var j = 0; j < table.rows[1].cells[i].getElementsByTagName("input").length; j++)
			{
				if (!(table.rows[1].cells[i].getElementsByTagName("input")[j] == undefined))
					table.rows[1].cells[i].getElementsByTagName("input")[j].setAttribute("value", "");
			}
		}
	}
}

/**
 * A helper method for deleteRow and addRow that corrects the line numbers of the
 * line items as well as making sure the buttons refer to the correct line item.
 */
function lcorrectNumbers()
{
	var rows = get("LiabilitiesTable").rows;
	// Go through the rows and make sure their ids and js methods refer to the
	// correct row number.

	for (var i = 0; i < rows.length - 1; i++)
	{
		var cells = rows[i + 1].cells;

		cells[0].getElementsByTagName("input")[0].setAttribute("id", "LiabilityId" + i);
		cells[0].getElementsByTagName("input")[0].setAttribute("name", "data[Budget][" + i + "][Liability][id]");
		cells[0].getElementsByTagName("input")[1].setAttribute("id", "Item" + i);
		cells[0].getElementsByTagName("input")[1].setAttribute("name", "data[Budget][" + i + "][Liability][item]");
		cells[1].getElementsByTagName("input")[0].setAttribute("id", "Amount" + i);
		cells[1].getElementsByTagName("input")[0].setAttribute("name", "data[Budget][" + i + "][Liability][amount]");
		cells[2].getElementsByTagName("button")[0].setAttribute("onclick", "lmoveUp(" + i + ")");
		cells[3].getElementsByTagName("button")[0].setAttribute("onclick", "lmoveDown(" + i + ")");
		cells[4].getElementsByTagName("button")[0].setAttribute("onclick", "laddRow(" + i + ")");
		cells[5].getElementsByTagName("button")[0].setAttribute("onclick", "ldeleteRow(" + i + ")");

	}
}