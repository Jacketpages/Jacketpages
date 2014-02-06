/**
 * @author Stephen Roca
 * @since 7/25/2013 
 */

/**
 *	Says whether a form is valid or not. Used in onsubmit event. 
 */
var validForm = true;

/**
 * Tells whether to run the validation rules again or just return validForm.
 */
var alreadyValidated = false;

/**
 * @param string id - the HTMl unique identifier
 * @param string message - the error message to display
 */
function notEmptyRule(id, message)
{
	if(typeof message ==="undefined") message = "This field cannot be blank.";
	if ($(id).val() == null || $(id).val() == "")
	{
		addErrorClassAndInvalidateForm(id, message);
	}
}

/**
 * @param string id - the HTMl unique identifier
 */
function numberRule(id)
{
	if ($(id).val().match(/[0-9]+/) == null)
	{
		addErrorClassAndInvalidateForm(id, "This is not a valid number.");
	}
}

/**
 * @param string id - the HTMl unique identifier
 */
function moneyRule(id)
{
	if ($(id).val().match(/^\d*(?:\.\d{0,2})?$/) == null || $(id).val().match(/^\d*(?:\.\d{0,2})?$/) == "")
	{
		addErrorClassAndInvalidateForm(id, "This is not a valid monetary amount.");
	}
}

/**
 * 
 * @param string id - the HTMl unique identifier
 * @param string message - the error message to display as a tooltip
 */
function addErrorClassAndInvalidateForm(id, message)
{
	$(id).addClass("error");
	$(id).attr("title", message);
	$(id).on("click", {id : id}, removeErrors);
	$(id).tooltip(
	{
		track : true
	});
	validForm = false;
}

/**
 * @param string id - the HTMl unique identifier
 */
function removeErrors(event)
{
	var id = event.data.id;
	$(id).removeClass("error");
	$(id).tooltip("close");
}

function exactValueRule(id, value)
{
	if ($(id).val() != value)
	{
		addErrorClassAndInvalidateForm(id, "Value is not " + value);
	}
}

function openToolTips()
{
	assignAndRunValidationRules();
	$(".error").tooltip("open");
}

function validateForm()
{
	valid = validForm,
	validForm = true;
	
	if(!alreadyValidated)
	{
		assignAndRunValidationRules();
		alreadyValidated = false;
	}
	return valid;
}