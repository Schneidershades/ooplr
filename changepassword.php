<?php
require_once 'core/init.php';

$user = new User();

if(!$user->isLoggedin()){
	Redirect::to('index.php');
}

if(Input::exists()){

	// Avoiding CSFR
	if(Token::check(Input::get('token'))){

		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'password_current' => array(
				'required' => true,
				'min' => 6
			),
			'new_password' => array(
				'required' => true,
				'min' => 6
			),
			'new_password_typed' => array(
				'required' => true,
				'min' => 6,
				'matches' => 'new_password'
			)
		));

		if($validation->passed()){
			// change of password
			if(Hash::make(Input::get('password_current'), $user->data()->salt) !== $user->data()->password ){
				echo "Your password is Wrong ";
			}else{
				$salt = Hash::salt('32');
				$user->update(array(
					'password' => Hash::make(Input::get('new_password'), $salt),
					'salt' => $salt
				));

				Session::flash('home', 'Your password has been changed!');
				Redirect::to('index.php');
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

<form action="" method="POST">
	<div class="field">
		<label for="password_current">Current Password</label>
		<input type="password" name="password_current" id="password_current">
	</div>
	

	<div class="field">
		<label for="new_password">New Password</label>
		<input type="password" name="new_password" id="new_password">
	</div>


	<div class="field">
		<label for="new_password_typed">New Password again</label>
		<input type="password" name="new_password_typed" id="new_password_typed">
	</div>

	<input type="submit" value="Change">
	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">

</form>