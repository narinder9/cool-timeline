jQuery(document).ready(function($) {
	
	$( '.add-fa-icon' ).on( 'click', function(e) {
		e.preventDefault();


		$('#fa-field-modal').show();
	});

	$( '.fa-field-modal-close' ).on( 'click', function() {

		$('#fa-field-modal').hide();

	});

	$( '.fa-field-modal-icon-holder' ).on( 'click', function() {

		var icon = $(this).data('icon'),
			holder = $( '.fa-field-metabox .fa-field-current-icon .icon' ),
			deleter = $( '.fa-field-metabox .fa-field-current-icon .delete' ),
			input = $( '.fa-field-metabox #fa_field_icon' ),
			close = $( '.fa-field-modal-close' );

		holder.html( '<i class="'+ icon +'" />' );
		deleter.addClass( 'active' );
		input.val(icon);
		close.trigger( 'click' );



	});

	$( '.fa-field-metabox .fa-field-current-icon .icon' ).on( 'click', function() {

		var holder = $( '.fa-field-metabox .fa-field-current-icon .icon' ),
			deleter = $( '.fa-field-metabox .fa-field-current-icon .delete' ),
			input = $( '.fa-field-metabox #fa_field_icon' ),
			close = $( '.fa-field-modal-close' );

		holder.html( '' );
		deleter.removeClass( 'active' );
		input.val('');

	});


	
});
function ctlSearchIcon() {
	// Declare variables
	var input, filter, ul, li, a, i, txtValue;
	input = document.getElementById('searchicon');
	filter = input.value.toUpperCase();
	iconsWrapper = document.getElementById("ctl_icon_wrapper");
	allIcons = iconsWrapper.getElementsByTagName('div');
	// Loop through all list items, and hide those who don't match the search query
	for (i = 0; i < allIcons.length; i++) {
	  txtValue = allIcons[i].getElementsByTagName("i")[0].getAttribute("data-icon-name");;
	 // txtValue = icon.attr("data-icon-name");
	  if (txtValue.toUpperCase().indexOf(filter) > -1) {
		allIcons[i].style.display = "";
	  } else {
		allIcons[i].style.display = "none";
	  }
	}
  }