<?php
echo "
<div class='".$css."_contact'>
	<form action='sendmail.php' method='post' id='contactform' class='".$css."_formcontact'>
				<p>
					<label for='your-name'>Your Name </label>
				</p>
				<p>
					<input type='text' name='name' id='your-name' class='input-xlarge'>
				</p>

				<p>
					<label for='your-email'>Your Email <span class='required'>(required)</span></label>
				</p>
				<p>
					<input type='email' name='email' id='your-email' class='input-xlarge' required>
				</p>

				<p>
					<label for='your-subject'>Subject </label>
				</p>
				<p>
					<input type='text' name='subject' id='your-subject' class='input-xlarge'>
				</p>

				<p>
					<label for='your-message'>Your message <span class='required'>(required)</span></label>
				</p>
				<p>
					<textarea name='message' cols='50' rows='10' id='your-message' class='input-xxlarge' required placeholder='What do you want to say?'></textarea>
				</p>

				<!-- This is hidden for normal users -->
				<div class='hidden'>
					<label>
						Do not fill out this field
						<input name='s_check'>
					</label>
				</div>

				<p>
					<input type='submit' name='submit' class='primary' value='Send Message'>
				</p>

				<p hidden id='response'></p>


		</form>
<script src='js/jquery-1.9.1.min.js'></script>
	<script>window.jQuery || document.write('<script src='wy_files/js/jquery-1.9.1.min.js'><\/script>')</script>
<script src='js/script_mailer.js'></script>
</div><!--close form_settings-->";

?>