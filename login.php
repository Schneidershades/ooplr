<?php
require ('core/init.php');

// if there is any input
if(Input::exists()){

	// Avoiding CSFR
	if(Token::check(Input::get('token'))){

		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'username' => array('required' => true),
			'password' => array('required' => true)
		));

		if($validation->passed()){
			//login user
			$user = new User();

			$remember = (Input::get('remember') === 'on') ? true : false;
			$login = $user->login(Input::get('username'), Input::get('password'), $remember);

			if($login){
				Redirect::to('index.php');
			}else{
				echo "<p>Sorry, logging in failed</p>";
			}
		}else{
			// create the errors
			foreach($validation->errors() as $error){
				echo $error, '<br>';
			}
		}

	}
}
?>

<form action="" method="post">
	<div class="field">
	<label for="username">Username</label>
	<input type="text" name="username" id="username" autocomplete="off">
	</div>

	<div class="field">
	<label for="password">password</label>
	<input type="password" name="password" id="password" autocomplete="off">
	</div>

	<div class="field">
	<label for="Remember">
	<input type="checkbox" name="remember" id="remember">Remember me
	</label>
	</div>


	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
	<input type="submit" value="Login">
</form>