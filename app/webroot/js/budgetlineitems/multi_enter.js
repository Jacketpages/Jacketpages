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
		var id = get(unique + "BudgetLineItemId" + pos).value;
		var name = get(unique + "BudgetLineItemName" + pos).value;
		var amt = get(unique + "BudgetLineItemAmount" + pos).value;
		var req = get(unique + "_py_req_" + pos).innerHTML;
		var alloc = get(unique + "_py_alloc_" + pos).innerHTML;
		var diff = get(unique + 'difference' + pos).innerHTML;

		// Set the values of the current row to the row that of the row being moved to
		get(unique + "BudgetLineItemId" + pos).value = get(unique + "BudgetLineItemId" + moveTo).value;
		get(unique + "BudgetLineItemName" + pos).value = get(unique + "BudgetLineItemName" + moveTo).value;
		get(unique + "BudgetLineItemAmount" + pos).value = get(unique + "BudgetLineItemAmount" + moveTo).value;
		get(unique + "_py_req_" + pos).innerHTML = get(unique + "_py_req_" + moveTo).innerHTML;
		get(unique + "_py_alloc_" + pos).innerHTML = get(unique + "_py_alloc_" + moveTo).innerHTML;
		get(unique + "difference" + pos).innerHTML = get(unique + "difference" + moveTo).innerHTML;

		// Set the row being moved to, to the values of the current row
		get(unique + "BudgetLineItemId" + moveTo).value = id;
		get(unique + "BudgetLineItemName" + moveTo).value = name;
		get(unique + "BudgetLineItemAmount" + moveTo).value = amt;
		get(unique + "_py_req_" + moveTo).innerHTML = req;
		get(unique + "_py_alloc_" + moveTo).innerHTML = alloc;
		get(unique + "difference" + moveTo).value = diff;
		
		if(get(unique + "BudgetLineItemAllocParentId" + pos) != undefined)
		{
			var allocParentId = get(unique + "BudgetLineItemAllocParentId" + pos).value;
			get(unique + "BudgetLineItemAllocParentId" + pos).value = get(unique + "BudgetLineItemAllocParentId" + moveTo).value;
			get(unique + "BudgetLineItemAllocParentId" + moveTo).value = allocParentId;
		}
		if(get(unique + "BudgetLineItemReqParentId" + pos) != undefined)
		{
			var reqParentId = get(unique + "BudgetLineItemReqParentId" + pos).value;
			get(unique + "BudgetLineItemReqParentId" + pos).value = get(unique + "BudgetLineItemReqParentId" + moveTo).value;
			get(unique + "BudgetLineItemReqParentId" + moveTo).value = reqParentId;
		}
		updateDiff();
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
			if (!(cells[i].getElementsByTagName("input")[0] == undefined))
			{
				cells[i].getElementsByTagName("input")[j].setAttribute("value", "");
			}
	}
	cells[1].innerHTML = "0";
	cells[2].innerHTML = "0";
	cells[4].innerHTML = '$0.00';
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

	for (var i = 0; i < rows.length - 1; i++)
	{
		var cells = rows[i + 1].cells;
		var stuff = cells[0].getElementsByTagName("input")[0].getAttribute("name");
		var startIndex = stuff.indexOf("[") + 1;
		var endIndex = stuff.indexOf("]");
		var category = stuff.substring(startIndex, endIndex);
		if(cells[0].getElementsByTagName("input").length > 2)
		{
			cells[0].getElementsByTagName("input")[0].setAttribute("id", num + "BudgetLineItemId" + i);
			cells[0].getElementsByTagName("input")[0].setAttribute("name", "data[" + category + "][" + i + "][BudgetLineItem][id]");
			cells[0].getElementsByTagName("input")[1].setAttribute("id", num + "BudgetLineItemAllocParentId" + i);
			cells[0].getElementsByTagName("input")[1].setAttribute("name", "data[" + category + "][" + i + "][BudgetLineItem][alloc_parent_id]");
			cells[0].getElementsByTagName("input")[2].setAttribute("id", num + "BudgetLineItemReqParentId" + i);
			cells[0].getElementsByTagName("input")[2].setAttribute("name", "data[" + category + "][" + i + "][BudgetLineItem][req_parent_id]");
			cells[0].getElementsByTagName("input")[3].setAttribute("id", num + "BudgetLineItemName" + i);
			cells[0].getElementsByTagName("input")[3].setAttribute("name", "data[" + category + "][" + i + "][BudgetLineItem][name]");
		}
		else
		{
			cells[0].getElementsByTagName("input")[0].setAttribute("id", num + "BudgetLineItemId" + i);
			cells[0].getElementsByTagName("input")[0].setAttribute("name", "data[" + category + "][" + i + "][BudgetLineItem][id]");
			cells[0].getElementsByTagName("input")[1].setAttribute("id", num + "BudgetLineItemName" + i);
			cells[0].getElementsByTagName("input")[1].setAttribute("name", "data[" + category + "][" + i + "][BudgetLineItem][name]");			
		}
		
		
		cells[1].setAttribute("id", num + "_py_req_" + i);
		cells[2].setAttribute("id", num + "_py_alloc_" + i);
		
		cells[3].getElementsByTagName("input")[0].setAttribute("id", num + "BudgetLineItemAmount" + i);
		cells[3].getElementsByTagName("input")[0].setAttribute("name", "data[" + category + "][" + i + "][BudgetLineItem][amount]");
		cells[4].setAttribute("id", num + "difference" + i);
		cells[5].getElementsByTagName("button")[0].setAttribute("onclick", "moveUp('" + tableId + "'," + i + ")");
		cells[6].getElementsByTagName("button")[0].setAttribute("onclick", "moveDown('" + tableId + "'," + i + ")");
		cells[7].getElementsByTagName("button")[0].setAttribute("onclick", "addRow('" + tableId + "'," + i + "," + num + ")");
		cells[8].getElementsByTagName("button")[0].setAttribute("onclick", "deleteRow('" + tableId + "'," + i + "," + num + ")");
	}
}