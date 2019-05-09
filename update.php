<?php
require ('core/init.php');

$user = new User();

if(!$user->isLoggedin()){
	Redirect::to('index.php');
}

if(Input::exists()){

	// Avoiding CSFR
	if(Token::check(Input::get('token'))){

		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'name' => array(
				'required' => true,
				'min' => 2,
				'max' => 50
			)
		));

		if($validation->passed()){
			
			try{
				$user->update(array(
					'name' => Input::get('name')
				));

				Session::flash('home', 'your details have been updated');
				Redirect::to('index.php');
				
			} catch(Exception $e){
				die($e->getMessage());
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
		<label for="name">Your name</label>
		<input type="text" name="name" value="<?php echo escape($user->data()->name); ?>" id="name">
	</div>

	<input type="hidden" name="token" value="<?php echo Token::generate();?>">

	<input type="submit" value="Update">

