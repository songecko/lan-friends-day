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
	
});