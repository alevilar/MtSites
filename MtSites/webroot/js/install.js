(function() {
	// get timezone
	var tz = jstz.determine();
	tz.name();
	$('#timezone').val( tz.name() );


	var delay = (function(){
	  var timer = 0;
	  return function(callback, ms){
	  clearTimeout (timer);
	  timer = setTimeout(callback, ms);
	 };
	})();



	var ajaxPending = false;


	function volverADesabilitar() {
		$("#alias-name-container").hide('fade');
		$("#btn-form-submit").addClass('disabled');
		$(".spinner", "#new-site-creator").hide();
	}


	var valAnterior = '';
	$('#inpup-site-name').on('keyup change', function() {
		var este = $(this),
			url  = este.data('urlCheckName'),
			name = este.attr('name'),
			data = {		
			},
			success = function ( e ){
				ajaxPending = false;
				var data = e;
				console.log('vino', e);

				if ( data && data.hasOwnProperty('aliasName') ) {
					$("#alias-name").html(data.aliasName);
					$("#alias-name-container").show('fade');
					$("#btn-form-submit").removeClass('disabled');
				}

			};

			data[name] = este.val();


			if ( !data[name] ) {
				// si el input esta vacio ocultar divs de exito y esas yerbas
				volverADesabilitar();
				return;
			}

			if ( valAnterior != data[name]) {
				valAnterior = data[name];
				volverADesabilitar();
				$(".spinner", "#new-site-creator").show();
				delay(function(){
					if ( ajaxPending ) {
						ajaxPending.abort();
					}
					

					ajaxPending = $.post(url, data, success, 'json')
										.done(function () {
											$(".spinner", "#new-site-creator").hide();
										});
				}, 1000 );
			}

	});
})();
