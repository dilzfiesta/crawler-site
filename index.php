<?php

require_once 'database.php';
require_once 'common.php';

//$objCommon = new Common();
$objDatabase = new Database();

# Fetch records
$records = $objDatabase->fetch_records();
//$objCommon->pr($records); exit;

?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<link href="/WebContent/main.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
  		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
  		<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
		<script>
		
		// http://jqueryui.com/dialog/#modal-form
		$(function() {
			var subject = $( "#subject" ),
		      email = $( "#email" ),
		      message = $( "#message" ),
		      allFields = $( [] ).add( subject ).add( email ).add( message ),
		      tips = $( ".validateTips" );

			function updateTips( t ) {
			      tips
			        .text( t )
			        .addClass( "ui-state-highlight" );
			      setTimeout(function() {
			        tips.removeClass( "ui-state-highlight", 1500 );
			      }, 500 );
			    }
			 
			    function checkLength( o, n, min, max ) {
			      if ( o.val().length > max || o.val().length < min ) {
			        o.addClass( "ui-state-error" );
			        updateTips( "Length of " + n + " must be between " +
			          min + " and " + max + "." );
			        return false;
			      } else {
			        return true;
			      }
			    }
			 
			    function checkRegexp( o, regexp, n ) {
			      if ( !( regexp.test( o.val() ) ) ) {
			        o.addClass( "ui-state-error" );
			        updateTips( n );
			        return false;
			      } else {
			        return true;
			      }
			    }
			    
			$( "#dialog-form" ).dialog({
		      autoOpen: false,
		      height: 450,
		      width: 450,
		      modal: true,
		      buttons: {
		        "Mail the employer": function() {
		          var bValid = true;
		          allFields.removeClass( "ui-state-error" );
		 
		          bValid = bValid && checkLength( subject, "subject", 10, 200 );
		          bValid = bValid && checkLength( email, "email", 10, 100 );
		          bValid = bValid && checkLength( message, "message", 10, 9999 );
		 
		          // From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
		          bValid = bValid && checkRegexp( email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. dilzfiesta@gmail.com" );
		 
		          if ( bValid ) {
		            // send email
		            sendEmail();
		            //$( this ).dialog( "close" );
		          }
		        },
		        Cancel: function() {
		          $( this ).dialog( "close" );
		        }
		      },
		      close: function() {
		        allFields.val( "" ).removeClass( "ui-state-error" );
		      }
		    });

			// Highlight rows
			$("tr").not(':first').hover(
			  function () {
			    $(this).css("background","yellow");
			  }, 
			  function () {
			    $(this).css("background","");
			  }
			);

		});
		
		function emailEmployer(id, email) {
			$('#email').val(email);
			$('#row-id').val(id);
			$('#subject').val('Application for the post of --post--');
			$('.validateTips').html('');
			$( "#dialog-form" ).dialog( "open" );
		}

		function getTemplate(type) {
			$.post( "fetch_template.php", { type:type }, function( data ) {
				$( "#message" ).val( data );
			});
		}

		function sendEmail() {
			var to = $('#email').val();
			var job_id = $('#row-id').val();
			var subject = $('#subject').val();
			var message = $('#message').val();
			$.post( "send_email.php", { to:to, job_id:job_id, subject:subject, message:message }, function( data ) {
				$( ".validateTips" ).text( data ).addClass( "ui-state-highlight" );
			      setTimeout(function() {
			    	  $( ".validateTips" ).removeClass( "ui-state-highlight", 1500 );
			      }, 500 );
			});
		}
		</script>
	</head>
	<body>
		<div id="dialog-form" title="Mail the employer">
			<p class="validateTips">All form fields are required.</p>
			<form>
				<fieldset> 
					<label for="subject">Subject</label> 
					<input type="text" name="subject" id="subject" value="" class="text ui-widget-content ui-corner-all">
					<label for="email">Email</label>
					<input type="text" name="email" id="email" value="" class="text ui-widget-content ui-corner-all">
					<label for="message">Message - Email Template: <a href="javascript:void(0)" onclick="getTemplate('java')">JAVA</a> or <a href="javascript:void(0)" onclick="getTemplate('php')">PHP</a></label>
					<textarea name="message" id="message" class="text ui-widget-content ui-corner-all" style="margin: 2px; width: 405px; height: 145px;"></textarea>
					<input type='hidden' id='row-id' value='' />
				</fieldset>
			</form>
		</div>
		<div>
			<table>
				<tr>
					<th scope="col">No.</th>
					<th scope="col">Position</th>
					<th scope="col" width='100'>Published Date</th>
					<th scope="col">Employer</th>
					<th scope="col">Location</th>
					<th scope="col" width='90px'>Start Date</th>
					<th scope="col">&nbsp;</th>
				</tr>
				<?php
					$key = 1;
					foreach($records as $rows) {
				?>
				<tr id='<?php echo $rows['id']; ?>'>
					<td><?php echo $key++; ?></td>
					<td><a href="<?php echo $rows['url']; ?>" target="_blank"><?php echo $rows['position']; ?></a></td>
					<td><?php echo date('d-M-Y', strtotime($rows['published_date'])); ?></td>
					<td><?php echo $rows['employer']; ?></td>
					<td><?php echo $rows['location']; ?></td>
					<td><?php echo date('d-M-Y', strtotime($rows['start_date'])); ?></td>
					<?php if(empty($rows['history_id'])) { ?>
						<td><a href="Javascript:void(0)" onclick="emailEmployer('<?php echo $rows['id']; ?>', '<?php echo $rows['email']; ?>')">Email</a></td>
					<?php } else { ?>
						<td>Sent</td>
					<?php } ?>
				</tr>
				<?php 
					} 
				?>
			</table>
		</div>
	</body>
</html>

