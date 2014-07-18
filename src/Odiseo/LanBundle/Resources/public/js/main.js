function showAlertModal( message)
{
	$('#modal-body').html('');
	$('#modal-body').html( $("<p class='message'></p>").append(message) );
	$('#alertModal').modal('show');
}

function addMessagesAndShowAlertModal( messages )
{
	$('#modal-body').html('');
	var ul  = $('<ul></ul>');
	for (var i = 0 ; i <  messages.length ; i++)
	{
		var li = $('<li></li>');
		var p = $("<p class='message'></p>");
		ul.append (li.append( p.append( messages[i])));
	}
	$('#modal-body').append(ul);
	$('#alertModal').modal('show');
}

function sendForm( )
{
	var sending = false;
	
	if(!sending)
	{
		sending = true;
		var form = $("form#register-form").get(0);
		var formObj = $(form);
		var formURL = formObj.attr("action");
		var formData = new FormData(form);
		
		$.ajax({
	        type: "POST",
	        url: formURL,
	        data: formData,
	        contentType: false,
	        cache: false,
	        processData:false,
	        success: function(data)
	        {
	        	if (data.status === 'error') 
	            {
	                console.log("error");
	                sending = false;
	            } else 
	            {	
	            	if (data.onError == 'true')
	            	{
	            		addMessagesAndShowAlertModal(data.errors);

	            	}else
	            	{
	            		showAlertModal( data.message );
	            	}
	            }
	        }					
	    });
	}
	return false;
};

$(document).ready(function() 
{
	$('.countdown').countdown({
		date: $('.countdown').data('endDate'),
		render: function(data) 
		{
			$('.countdown .day .number').html(data.days);
			$('.countdown .hour .number').html(data.hours);
			$('.countdown .minute .number').html(data.min);
			$('.countdown .second .number').html(data.sec);
		},
		onEnd: function() 
		{
            alert('Empezó la promoción!');
		}
	});
	
	$("#register-form").validate({
		invalidHandler: function(event, validator)
		{
			//showAlertModal("Debes completar correctamente el formulario.");		
		},		
		errorPlacement: function(error, element) 
		{
		},
		highlight: function(element, errorClass, validClass) 
		{
		    $(element).addClass(errorClass).removeClass(validClass);
		},
		unhighlight: function(element, errorClass, validClass) 
		{
		    $(element).removeClass(errorClass).addClass(validClass);
		},
		submitHandler: function(form) 
		{
			//sendForm();
			form.submit();
		}
	});
	

	
});