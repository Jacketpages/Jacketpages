function assignAndRunValidationRules()
{
	// var i = 0;
	// var locations = [];
	// var values = [];
	// while(i < 6)
	// {
		// var table = document.getElementById("BudgetLineItem-" + i);
		// if (table != undefined)
		// {
			// var rows = table.rows;
			// for ( j = 1; rows[j].cells[0].innerHTML != ""; j++)
			// {
				// values.push(rows[j].cells[1].getElementsByTagName("textarea")[0].getAttribute("name"));
				// locations.push(rows[j].cells[1].getElementsByTagName("textarea")[0].getAttribute("id"));
			// }
		// }
		// i++;
	// }
	
	for(var i = 0; i < 5; i++)
	{
		var element = $("#BudgetLineItem-" + i);
		if(element != undefined)
		{
			var j = 0;
			while(document.getElementById("BudgetLineItem" + j + "Amount") != undefined)
			{
				console.log($("#BudgetLineItem" + j + "Amount"));
				moneyRule("#BudgetLineItem" + j + "Amount");
				j++;
			}
		}
	}
	alreadyValidated = true;
}

function addRow(pos, tableName)
{
	var tableNumber = document.getElementById(tableName).rows[1].cells[1].getElementsByTagName("input")[0].value;
	var textarea = "<textarea name=\"data[BudgetLineItem][1][name]\" rows=\"1\" cols=\"30\" id=\"BudgetLineItem1Name\"></textarea>";
	var category = "<input type=\"hidden\" name=\"data[BudgetLineItem][" + tableNumber + "][category]\" value=\"" + tableNumber + "\" id=\"BudgetLineItem1Category\">";
	var id = "<input type=\"hidden\" name=\"data[BudgetLineItem][1][id]\" id=\"BudgetLineItem1Id\">";
	//table has a header row
	pos = pos + 1;
	moveTo = pos + 1;
	var table = document.getElementById(tableName);
	var rows = table.rows;
	if (pos <= (rows.length - 2))
	{
		table.insertRow(moveTo);
		table.rows[moveTo].innerHTML = table.rows[pos].innerHTML;
		var cells = rows[moveTo].cells;
		var limit = cells.length;
		for (var i = 0; i < limit; i++)
		{
			if (i < 2)
			{
				if (i == 1)
					cells[i].innerHTML = textarea + category + id;
			}
			else
			if (i > (limit - 3))
			{
				if (i == (limit - 1))
				{
					cells[i].innerHTML = "<button type=\"button\" onclick=\"deleteRow(" + pos + ",'" + tableName + "')\">-</button>";
				}
			}
			else
			{
				if (rows[pos].cells[i].getElementsByTagName("input").length == 0)
				{
					cells[i].innerHTML = '$0.00';
				}
				else
				{
					rows[moveTo].cells[i].getElementsByTagName("input")[0].value = 0.00;
				}
			}
		}
	}
	correctReferences();
}

function deleteRow(pos, tableName)
{
	pos = pos + 1;
	var table = document.getElementById(tableName);
	table.deleteRow(pos);
	correctReferences();
}

function correctReferences()
{
	i = 0;
	rowNumber = 1;
	index = 0;
	while (i < 6)
	{
		var table = document.getElementById("BudgetLineItem-" + i);
		if (table != undefined)
		{
			var rows = table.rows;
			for ( j = 1; rows[j].cells[0].innerHTML != ""; j++)
			{
				rows[j].cells[0].getElementsByTagName("input")[0].value = rowNumber;
				rows[j].cells[0].getElementsByTagName("input")[0].setAttribute("value",rowNumber);
				rows[j].cells[0].getElementsByTagName("input")[0].setAttribute("name","data[BudgetLineItem][" + index + "][line_number]");
				rows[j].cells[0].getElementsByTagName("input")[0].setAttribute("id","BudgetLineItem" + index + "LineNumber");
				rows[j].cells[1].getElementsByTagName("textarea")[0].setAttribute("id", "BudgetLineItem" + index + "Name");
				rows[j].cells[1].getElementsByTagName("textarea")[0].setAttribute("name", "data[BudgetLineItem][" + index + "][name]");
				rows[j].cells[1].getElementsByTagName("input")[0].setAttribute("id", "BudgetLineItem" + index + "Category");
				rows[j].cells[1].getElementsByTagName("input")[0].setAttribute("name", "data[BudgetLineItem][" + index + "][category]");
				rows[j].cells[1].getElementsByTagName("input")[1].setAttribute("id", "BudgetLineItem" + index + "Id");
				rows[j].cells[1].getElementsByTagName("input")[1].setAttribute("name", "data[BudgetLineItem][" + index + "][id]");
				for ( k = 5; k < rows[j].cells.length; k++)
				{
					if (rows[j].cells[k].getElementsByTagName("input").length > 0)
					{
						rows[j].cells[k].getElementsByTagName("input")[0].setAttribute("id", "BudgetLineItem" + index + "Amount");
						rows[j].cells[k].getElementsByTagName("input")[0].setAttribute("name", "data[BudgetLineItem][" + index + "][amount]");
					}
				}
				rows[j].cells[12].getElementsByTagName("button")[0].setAttribute("onclick", "addRow(" + (j - 1) + ", 'BudgetLineItem-" + i + "')");
				if (rows[j].cells[13].getElementsByTagName("button").length > 0)
					rows[j].cells[13].getElementsByTagName("button")[0].setAttribute("onclick", "deleteRow(" + (j - 1) + ", 'BudgetLineItem-" + i + "')");
				rowNumber++;
				index++;
			}
		}
		i++;

	}

}
