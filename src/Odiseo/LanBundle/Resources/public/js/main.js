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

var sending = false;
function sendForm( form )
{
	if(!sending)
	{
		sending = true;
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
	        	sending = false;
	        	if (data.status === 'error') 
	            {
	                console.log("error");
	            } else 
	            {	
	            	if (data.onError == 'true')
	            	{	            		
	            		$('.twitterMessage p').html(data.errors);
	            		$('.twitterMessage').addClass('error');
	            		$('.twitterMessage').removeClass('sucess');
	            	}else
	            	{
	            		$('#form_data_tweet').val('#AmigosLan ');
	            		$('.twitterMessage p').html(data.message);
	            		$('.twitterMessage').addClass('sucess');
	            		$('.twitterMessage').removeClass('error');
	            		
	            	}
	            }
	        }					
	    });
	}
	return false;
};

$(document).ready(function() 
{
	if($('.countdown').length > 0)
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
				location.reload();
			}
		});
	}
	
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
	
	$("#tweet-form").validate({
		invalidHandler: function(event, validator)
		{
			showAlertModal("Debes completar correctamente el formulario.");		
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
			sendForm(form);
			//form.submit();
		}
	});
	
	

	function refreshPassengers(){
		 if ( $('#flight-passengers').length > 0 )
		 { 
			$.ajax({
		        type: "POST",
		        url: $('#flight-passengers').data('refreshUrl'),
		    }).success(function( data ){
		        setTimeout(function(){refreshPassengers();}, 5000);
		        seats = data.seatsImageUrl;
				
				$.each(seats, function( index, urlImage ) {
						var idDiv = '#position-' + index;
						var img = $('<img>'); 
						img.attr('src', urlImage);
						console.log(idDiv);
						$(idDiv).html(img.get(0));
				});
				
				tweets = data.tweets;
				divMedias = $('.box_tweets .media');
				$.each(tweets, function( index, tweet ) {
					$(divMedias[index]).find('img').attr('src', tweet['imageUrl']);
					$(divMedias[index]).find('p').html(tweet['tweet']);
					$(divMedias[index]).find('h4').html('@' + tweet['screenName'] + '	');
					$(divMedias[index]).find('h4').append( $('<span>').html( jQuery.timeago( new Date(tweet['timeAgo'].date )  ) ) );
					console.log($(divMedias[index]).find('span'));
					$(divMedias[index]).css('display' , 'block');
				});
		    
		    });

		 }
	};
	refreshPassengers();
	
	jQuery.timeago.settings.strings = {
			   prefixAgo: "hace",
			   prefixFromNow: "dentro de",
			   suffixAgo: "",
			   suffixFromNow: "",
			   seconds: "menos de un minuto",
			   minute: "un minuto",
			   minutes: "unos %d minutos",
			   hour: "una hora",
			   hours: "%d horas",
			   day: "un día",
			   days: "%d días",
			   month: "un mes",
			   months: "%d meses",
			   year: "un año",
			   years: "%d años"
			};
	
});