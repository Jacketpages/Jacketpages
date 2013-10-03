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
	var unique = tableId.substring(tableId.length - 1, tableId.length);
	// If the position to move to is not first row (table headers) and it is defined
	// then swap the two rows
	if (moveTo >= 0 && !(rows[moveTo + 1] == undefined))
	{
		// Save the values from the current row
		var id = get(unique + "FundraiserId" + pos).value;
		var activity = get(unique + "Activity" + pos).value;
		var date = get(unique + "Date" + pos).value;
		var revenue = get(unique + "Revenue" + pos).value;

		// Set the values of the current row to the row that of the row being moved to
		get(unique + "FundraiserId" + pos).value = get(unique + "FundraiserId" + moveTo).value;
		get(unique + "Activity" + pos).value = get(unique + "Activity" + moveTo).value;
		get(unique + "Date" + pos).value = get(unique + "Date" + moveTo).value;
		get(unique + "Revenue" + pos).value = get(unique + "Revenue" + moveTo).value;

		// Set the row being moved to, to the values of the current row
		get(unique + "FundraiserId" + moveTo).value = id;
		get(unique + "Activity" + moveTo).value = activity;
		get(unique + "Date" + moveTo).value = date;
		get(unique + "Revenue" + moveTo).value = revenue;
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
		for (var j = 0; j < cells[i].getElementsByTagName("input").length; j++)
		{
			if (!(cells[i].getElementsByTagName("input")[j] == undefined))
			{
				cells[i].getElementsByTagName("input")[j].setAttribute("value", "");
			}
		}
	}
	cells[0].getElementsByTagName("textarea")[0].innerHTML = "";
	correctNumbers(tableId, num);
}

/**
 * Deletes a row from the table of lineitems.
 * @param int pos - row position
 */
function deleteRow(tableId, pos, num)
{
	pos = pos + 1;
	var table = get(tableId);
	if (table.rows.length > 2)
	{
		table.deleteRow(pos);
		correctNumbers(tableId, num);
	}
	else
	{
		for (var i = 0; i < table.rows[1].cells.length; i++)
		{
			for (var j = 0; j < table.rows[1].cells[i].getElementsByTagName("input").length; j++)
			{
				if (!(table.rows[1].cells[i].getElementsByTagName("input")[j] == undefined))
				{
					table.rows[1].cells[i].getElementsByTagName("input")[j].setAttribute("value", "");
					table.rows[1].cells[i].getElementsByTagName("input")[j].value = '';
				}
				if (!(table.rows[1].cells[i].getElementsByTagName("textarea")[j] == undefined))
				{
					table.rows[1].cells[i].getElementsByTagName("textarea")[j].setAttribute("value", "");
					table.rows[1].cells[i].getElementsByTagName("textarea")[j].value = '';
				}
			}
		}
	}
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
	var oldElement = rows[1].cells[0].getElementsByTagName("input")[0].getAttribute("name");
	var startIndex = oldElement.indexOf("[") + 1;
	var endIndex = oldElement.indexOf("]");
	var category = oldElement.substring(startIndex, endIndex);
	for (var i = 0; i < rows.length - 1; i++)
	{
		var cells = rows[i + 1].cells;
		cells[0].getElementsByTagName("input")[0].setAttribute("id", num + "FundraiserId" + i);
		cells[0].getElementsByTagName("input")[0].setAttribute("name", "data[" + category + "][" + i + "][Fundraiser][id]");
		cells[0].getElementsByTagName("input")[0].setAttribute("id", num + "FundraiserType" + i);
		cells[0].getElementsByTagName("input")[1].setAttribute("name", "data[" + category + "][" + i + "][Fundraiser][type]");
		cells[0].getElementsByTagName("input")[1].setAttribute("id", num + "FundraiserId" + i);
		cells[0].getElementsByTagName("input")[1].setAttribute("value", category);
		cells[0].getElementsByTagName("textarea")[0].setAttribute("id", num + "Activity" + i);
		cells[0].getElementsByTagName("textarea")[0].setAttribute("name", "data[" + category + "][" + i + "][Fundraiser][activity]");
		cells[1].getElementsByTagName("input")[0].setAttribute("id", num + "Date" + i);
		cells[1].getElementsByTagName("input")[0].setAttribute("name", "data[" + category + "][" + i + "][Fundraiser][date]");
		$('#' + num + "Date" + i).removeClass('hasDatepicker').datepicker({dateFormat:'yy-mm-dd'});
		cells[2].getElementsByTagName("input")[0].setAttribute("id", num + "Revenue" + i);
		cells[2].getElementsByTagName("input")[0].setAttribute("name", "data[" + category + "][" + i + "][Fundraiser][revenue]");
		cells[3].getElementsByTagName("button")[0].setAttribute("onclick", "moveUp('" + tableId + "'," + i + ")");
		cells[4].getElementsByTagName("button")[0].setAttribute("onclick", "moveDown('" + tableId + "'," + i + ")");
		cells[5].getElementsByTagName("button")[0].setAttribute("onclick", "addRow('" + tableId + "'," + i + "," + num + ")");
		cells[6].getElementsByTagName("button")[0].setAttribute("onclick", "deleteRow('" + tableId + "'," + i + "," + num + ")");
	}
}

