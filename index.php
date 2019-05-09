<?php
require_once 'core/init.php';

if(Session::exists('home')){
	echo '<p>' . Session::flash('home') . '</p>';
}

$user = new User();

if ($user->isLoggedin()){
	?>

	<p> Hello <a href="profile.php?user=<?php echo escape($user->data()->username); ?>"><?php echo escape($user->data()->username); ?></a></p>

	<ul>
		<li><a href="logout.php">Logout</a></li>
		<li><a href="update.php">Update Details</a></li>
		<li><a href="changepassword.php">Change Password</a></li>


	</ul>
	<?php

	if(!$user->hasPermission('moderator')){
		echo '<p>You are an moderator</p>';
	}

}else{
	echo '<p>You need to <a href="login.php">Login</a> or<a href="register.php">Register</a></p>';
}