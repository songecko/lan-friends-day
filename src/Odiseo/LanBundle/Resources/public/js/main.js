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
			alert("Debes completar correctamente el formulario.");
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
			form.submit();
		}
	});
	
});