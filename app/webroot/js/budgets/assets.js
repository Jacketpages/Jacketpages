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
function amoveDown(pos)
{
	amove(pos, pos + 1);
}

/**
 * Moves a specific line item up one
 * @param int pos - row position
 */
function amoveUp(pos)
{
	amove(pos, pos - 1);
}

/**
 * A helper function ato implement moveUp and moveDown. Does the actual work of
 * the two methods
 * @param int pos - row position
 * @param int moveTo - row position to move to
 */
function amove(pos, moveTo)
{
	var rows = get("AssetsTable").rows;
	// If the position to move to is not first row (table headers) and it is defined
	// then swap the two rows
	if (moveTo >= 0 && rows.length >= moveTo + 2)
	{
		// Save the values from the current row
		var id = get("AssetId" + pos).value;
		var item = get("Item" + pos).value;
		var expense = get("Amount" + pos).value;
		var tagged = get("Tagged" + pos).checked;

		// Set the values of the current row to the row that of the row being moved to
		get("AssetId" + pos).value = get("AssetId" + moveTo).value;
		get("Item" + pos).value = get("Item" + moveTo).value;
		get("Amount" + pos).value = get("Amount" + moveTo).value;
		get("Tagged" + pos).checked = get("Tagged" + moveTo).checked;

		// Set the row being moved to, to the values of the current row
		get("AssetId" + moveTo).value = id;
		get("Item" + moveTo).value = item;
		get("Amount" + moveTo).value = expense;
		get("Tagged" + moveTo).checked = tagged;
	}
}

/**
 * Adds a blank row to the table of lineitems.
 * @param int pos - row position
 */
function aaddRow(pos)
{
	pos = pos + 1;
	var moveTo = pos + 1;
	var table = get("AssetsTable");
	table.insertRow(moveTo);
	table.rows[moveTo].innerHTML = table.rows[pos].innerHTML;
	var rows = get("AssetsTable").rows;
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
	acorrectNumbers();
}

/**
 * Deletes a row from the table of lineitems.
 * @param int pos - row position
 */
function adeleteRow(pos)
{
	pos = pos + 1;
	var table = get("AssetsTable");
	if (table.rows.length != 2)
	{
		table.deleteRow(pos);
		acorrectNumbers();
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
function acorrectNumbers()
{
	var rows = get("AssetsTable").rows;
	// Go through the rows and make sure their ids and js methods refer to the
	// correct row number.

	for (var i = 0; i < rows.length - 1; i++)
	{
		var cells = rows[i + 1].cells;

		cells[0].getElementsByTagName("input")[0].setAttribute("id", "AssetId" + i);
		cells[0].getElementsByTagName("input")[0].setAttribute("name", "data[Budget][" + i + "][Asset][id]");
		cells[0].getElementsByTagName("input")[1].setAttribute("id", "Item" + i);
		cells[0].getElementsByTagName("input")[1].setAttribute("name", "data[Budget][" + i + "][Asset][item]");
		cells[1].getElementsByTagName("input")[0].setAttribute("id", "Amount" + i);
		cells[1].getElementsByTagName("input")[0].setAttribute("name", "data[Budget][" + i + "][Asset][amount]");
		cells[2].getElementsByTagName("input")[0].setAttribute("id", "Tagged" + i + "_");
		cells[2].getElementsByTagName("input")[0].setAttribute("name", "data[Budget][" + i + "][Asset][tagged]");
		cells[2].getElementsByTagName("input")[1].setAttribute("id", "Tagged" + i);
		cells[2].getElementsByTagName("input")[1].setAttribute("name", "data[Budget][" + i + "][Asset][tagged]");
		cells[3].getElementsByTagName("button")[0].setAttribute("onclick", "amoveUp(" + i + ")");
		cells[4].getElementsByTagName("button")[0].setAttribute("onclick", "amoveDown(" + i + ")");
		cells[5].getElementsByTagName("button")[0].setAttribute("onclick", "aaddRow(" + i + ")");
		cells[6].getElementsByTagName("button")[0].setAttribute("onclick", "adeleteRow(" + i + ")");

	}
}