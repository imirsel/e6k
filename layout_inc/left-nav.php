
            <div id="build">
                <a href="http://www.music-ir.org/mirex/wiki/"><span>MIREX</span></a>
            </div>
		<?php if(!isUserLoggedIn()) { ?>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
                <li><a href="forgot-password.php">Forgot Password</a></li>
                <li><a href="resend-activation.php">Resend Activation Email</a></li>
            </ul>
       <?php } else { ?>
       		<ul>
            	<li><a href="logout.php">Logout</a></li>
            	<li><a href="account.php">Account Info</a></li>
       			<li><a href="consent.php">Informed Consent</a></li>
       			<li><a href="assignment.list.php">My Assignments</a></li>
       			<li><a href="change-password.php">Change password</a></li>
                <li><a href="update-email-address.php">Update email address</a></li>
       		</ul>
       <?php 
				if ($loggedInUser->isGroupMember(2)) {
			?>
				<ul>
					<li>ADMIN</li>
					<li><a href="admin.task.edit.php">Tasks Admin</a></li>
					<li><a href="admin.assignments.php">Assignments Admin</a></li>
				</ul>
			<?php
				}
			}
		?>
       
