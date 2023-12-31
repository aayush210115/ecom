<div class="alert alert-warning"><?php _e('Already registered?', 'jigoshop'); ?> <a href="#" id="jigoshop-login"><?php _e('Click here to login.', 'jigoshop'); ?></a></div>
<form role="form" action="<?= wp_login_url(); ?>" method="post" id="jigoshop-login-form" class="not-active">
	<div class="form-group">
		<label for="user_login"><?php _e('Username', 'jigoshop'); ?></label>
		<input type="text" name="log" class="form-control" id="user_login" placeholder="<?php _e('Enter username', 'jigoshop'); ?>">
	</div>
	<div class="form-group">
		<label for="user_pass"><?php _e('Password', 'jigoshop'); ?></label>
		<input type="password" name="pwd" class="form-control" id="user_pass" placeholder="<?php _e('Your password', 'jigoshop'); ?>">
	</div>
	<div class="checkbox">
		<label>
			<input type="checkbox" name="rememberme" value="forever"> <?php _e('Remember me', 'jigoshop'); ?>
		</label>
	</div>
	<button type="submit" name="wp-submit" value="Log in" class="btn btn-default"><?php _e('Log in', 'jigoshop'); ?></button>
	<input type="hidden" value="<?= get_permalink(); ?>" name="redirect_to">
</form>
