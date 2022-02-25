<?php
	include 'include/shared.php';
	//start or continue session so the CAPTCHA text stored in $_SESSION is accessible

	session_start();
	header('Cache-control:private');

	//prepare the registration form's HTML

	ob_start();

?>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
	
	<table>
		<tr>
			<td><label for="matricule">Matricule</label></td>
			<td><input type="text" name="matricule" id="matricule" value="<?php if(isset($_SESSION['matricule'])) echo htmlspecialchars($_SESSION['matricule']); ?>"></td>
		</tr>
		<tr>
			<td><label for="user1stName">First Name</label></td>
			<td><input type="text" name="user1stName" id="user1stName" value="<?php if(isset($_SESSION['user1stName'])) echo htmlspecialchars($_SESSION['user1stName']); ?>"></td>
		</tr>
		<tr>
			<td><label for="userLastName">Last Name</label></td>
			<td><input type="text" name="userLastName" id="userLastName" value="<?php if(isset($_SESSION['userLastName'])) echo htmlspecialchars($_SESSION['userLastName']); ?>"></td>
		</tr>
		<tr>
			<td><label for="email">Email</label></td>
			<td><input type="email" name="email" id="email" value="<?php if(isset($_SESSION['email'])) echo htmlspecialchars($_SESSION['email']); ?>"></td>
		</tr>
		<!-- <tr>
			<td><label for="captcha">Verify</label></td>
			<td>Enter the text seen in this image<br/>
				<img src="include/captcha.php?nocache=<?php //echo time(); ?>" alt=""/></td>
		</tr> -->
		<tr>
			<td>
				<label for="vacationType">Type of vacation:</label>
			</td>
			<td>
				<?php
					$query = sprintf('SELECT * FROM vacationType');
					$result = mysqli_query($GLOBALS['DB'],$query);
					$no = mysqli_num_rows($result);
					$i = 0;
					$id = 0;
						echo '<tr>';
					echo '<td></td>';
					echo '<td><select name="vacationType[' . $id . ']">';
						while ($row = mysqli_fetch_assoc($result)) {
            		    		echo '<option ';
            		    		if ($i == $id)
            		    			{
            		        			echo 'selected="selected" ';
            		    			}
            		    		echo 'value="'.$i.'">'.$row['vacationDesignation'].'</option>';
            				
						}
            						
				?>
			</td>
		</tr>
		<tr>
			<td><label for="numberofdays">Duration</label></td>
			<td><input type="text" name="numberofdays" min="1" max="21"></td>
		</tr>
		<tr>
			<td><input type="submit" value="submit"></td>
			<td><input type="hidden" name="submitted" value="1"></td>
		</tr>
	</table>

</form>
<?php
	
	if (isset($_SESSION['matricule'])) {
		$form = ob_get_clean();
		$GLOBALS['TEMPLATE']['content'] =$form;
		if (isset($_POST['submitted'])) {

		//$user->VRequest($days,$tVacation,$user->uid);
		$query = sprintf('INSERT INTO pendingVRequest(vacationDuration, typeId, userId) VALUES (%d,%d,%s)',(int)mysqli_real_escape_String($GLOBALS['DB'],$_POST['numberofdays']),(int)mysqli_real_escape_String($GLOBALS['DB'],$_POST['vacationType']),(int)mysqli_real_escape_String($GLOBALS['DB'],$userId));
			if (!mysqli_query($GLOBALS['DB'], $query)) {

					echo "Error";
					return false;

				}
				else{
					mysqli_insert_id($GLOBALS['DB']);
					$message = 'thank you for applying. you will be receiving an email to tell you if your request has been approuved or not';
					echo($message);
					header('Location:login.php');
					return true;
					
				}

	}

	}
	include 'include/template.php';

?>