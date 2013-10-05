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
	if (moveTo >= 0 && !(rows[moveTo] == undefined))
	{
		// Save the values from the current row
		var id = get("LineItemId" + pos).value;
		var name = get("LineItemName" + pos).value;
		var cost = get("LineItemCostPerUnit" + pos).value;
		var qty = get("LineItemQuantity" + pos).value;
		var tc = get("LineItemTotalCost" + pos).value;
		var amt = get("LineItemAmount" + pos).value;
		var account = get("LineItemAccount" + pos).value;

		// Set the values of the current row to the row that of the row being moved to
		get("LineItemId" + pos).value = get("LineItemId" + moveTo).value;
		get("LineItemName" + pos).value = get("LineItemName" + moveTo).value;
		get("LineItemCostPerUnit" + pos).value = get("LineItemCostPerUnit" + moveTo).value;
		get("LineItemQuantity" + pos).value = get("LineItemQuantity" + moveTo).value;
		get("LineItemTotalCost" + pos).value = get("LineItemTotalCost" + moveTo).value;
		get("LineItemAmount" + pos).value = get("LineItemAmount" + moveTo).value;
		get("LineItemAccount" + pos).value = get("LineItemAccount" + moveTo).value;

		// Set the row being moved to, to the values of the current row
		get("LineItemId" + moveTo).value = id;
		get("LineItemName" + moveTo).value = name;
		get("LineItemCostPerUnit" + moveTo).value = cost;
		get("LineItemQuantity" + moveTo).value = qty;
		get("LineItemTotalCost" + moveTo).value = tc;
		get("LineItemAmount" + moveTo).value = amt;
		get("LineItemAccount" + moveTo).value = account;
	}
	var rows = get("LineItemsTable").rows;
}

/**
 * Adds a blank row to the table of lineitems.
 * @param int pos - row position
 */
function addRow(pos)
{
	pos = pos + 1;
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
	pos = pos + 1;
	var table = get("LineItemsTable");
	if (table.rows.length != 2)
	{
		table.deleteRow(pos);
		correctNumbers();
	}
	else
	{
		for (var i = 0; i < table.rows[1].cells.length; i++)
		{
			for (var j = 0; j < table.rows[1].cells[i].getElementsByTagName("input").length; j++)
			{
				if (!(table.rows[1].cells[i].getElementsByTagName("input")[j] == undefined))
				{
					if (table.rows[1].cells[i].getElementsByTagName("input")[j].className != 'LineItemNumber')
					{
						table.rows[1].cells[i].getElementsByTagName("input")[j].setAttribute("value", "");
						table.rows[1].cells[i].getElementsByTagName("input")[j].value = '';
					}
				}
			}
		}
	}
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

	var correction = 0;
	if(get('LineItemId0') == undefined)
		correction = 1;
	for (var i = 1; i < rows.length; i++)
	{
		updateRow(rows[i].cells,i -1 + correction,i - 1,i);
		
	}
}

function updateRow(cells, idNum, row,  lineNumber)
{
	cells[0].getElementsByTagName("input")[0].setAttribute("id", "LineItemId" + row);
		cells[0].getElementsByTagName("input")[0].setAttribute("name", "data[" +row + "][LineItem][id]");
		cells[0].getElementsByTagName("input")[1].setAttribute("id", "LineItemLineNumber" +row);
		get("LineItemLineNumber" + row).value = lineNumber;
		cells[0].getElementsByTagName("input")[1].setAttribute("name", "data[" +row + "][LineItem][line_number]");
		cells[0].getElementsByTagName("label")[0].setAttribute("id", "LineNumber" +row);
		cells[0].getElementsByTagName("label")[0].setAttribute("value", lineNumber);
		cells[0].getElementsByTagName("label")[0].innerHTML = lineNumber;
		cells[1].getElementsByTagName("input")[0].setAttribute("id", "LineItemName" +row);
		cells[1].getElementsByTagName("input")[0].setAttribute("name", "data[" +row + "][LineItem][name]");
		cells[2].getElementsByTagName("input")[0].setAttribute("id", "LineItemCostPerUnit" +row);
		$("#LineItemCostPerUnit" + row).attr('onchange', "updateTCAndRqstd(" + row + ")");
		cells[2].getElementsByTagName("input")[0].setAttribute("name", "data[" +row + "][LineItem][cost_per_unit]");
		cells[3].getElementsByTagName("input")[0].setAttribute("id", "LineItemQuantity" +row);
		$("#LineItemQuantity" + row).attr('onchange', "updateTCAndRqstd(" +row + ")");
		cells[3].getElementsByTagName("input")[0].setAttribute("name", "data[" +row + "][LineItem][quantity]");
		cells[4].getElementsByTagName("input")[0].setAttribute("id", "LineItemTotalCost" +row);
		cells[4].getElementsByTagName("input")[0].setAttribute("name", "data[" +row + "][LineItem][total_cost]");
		cells[5].getElementsByTagName("input")[0].setAttribute("id", "LineItemAmount" +row);
		cells[5].getElementsByTagName("input")[0].setAttribute("name", "data[" +row + "][LineItem][amount]");
		cells[6].firstChild.firstChild.setAttribute("id", "LineItemAccount" +row);
		$("#LineItemAccount" + row).attr('onchange', "updateTCAndRqstd(" +row + ")");
		cells[6].firstChild.firstChild.setAttribute("name", "data[" +row + "][LineItem][account]");
		cells[7].getElementsByTagName("button")[0].setAttribute("onclick", "moveUp(" +row + ")");
		cells[8].getElementsByTagName("button")[0].setAttribute("onclick", "moveDown(" +row + ")");
		cells[9].getElementsByTagName("button")[0].setAttribute("onclick", "addRow(" +row + ")");
		cells[10].getElementsByTagName("button")[0].setAttribute("onclick", "deleteRow(" +row + ")");

}

function updateTCAndRqstd(row)
{
	if ($("#LineItemAccount" + row).val() == "CO")
	{
		$("#LineItemAmount" + row).val(parseFloat($("#LineItemCostPerUnit" + row).val() * $("#LineItemQuantity" + row).val() * .75).toFixed(2));
		$("#LineItemTotalCost" + row).val(parseFloat($("#LineItemCostPerUnit" + row).val() * $("#LineItemQuantity" + row).val()).toFixed(2));
	}
	else
	{
		$("#LineItemAmount" + row).val(parseFloat($("#LineItemCostPerUnit" + row).val() * $("#LineItemQuantity" + row).val()).toFixed(2));
		$("#LineItemTotalCost" + row).val(parseFloat($("#LineItemCostPerUnit" + row).val() * $("#LineItemQuantity" + row).val()).toFixed(2));
	}
}
