<?php
// include shared code
//include '../lib/common.php';
include 'include/shared.php';
// start or continue the session
session_start();
header('Cache-control: private');

// perform login logic if login is set
if (isset($_GET['login']))
{
    if (isset($_POST['matricule']))
    {
        // retrieve user record
        $user = (User::validateString($_POST['matricule']))?User::getByUserM($_POST['matricule']):new User();
       
        if ($user->userId)
        {
            // everything checks out so store values in session to track the
            // user and redirect to main page
            $_SESSION['access'] = TRUE;
            $_SESSION['userId'] = $user->userId;
            $_SESSION['matricule'] = $user->matricule;
            $_SESSION['user1stName'] = $user->first_ame;
            $_SESSION['userLastName'] = $user->last_Name;
            $_SESSION['email'] = $user->emailAddr;
            header('Location: vacationRequest.php');
        }
        else
        {
            // invalid user and/or password
            $_SESSION['access'] = FALSE;
            $_SESSION['username'] = null;
            header('Location: 401.php');
        } 
    }
    // missing credentials
    else
    {
        $_SESSION['access'] = FALSE;
        $_SESSION['username'] = null;
        header('Location: 401.php');
    }
    exit();
}

// perform logout logic if logout is set
// (clearing the session data effectively logsout the user)
else if (isset($_GET['logout']))
{
    if (isset($_COOKIE[session_name()]))
    {
        setcookie(session_name(), '', time() - 42000, '/');
    }

    $_SESSION = array();
    session_unset();
    session_destroy();
}

// generate login form
ob_start();
?>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?login" method="post">
 <table>
  <tr>
   <td><label for="email">Matricule</label></td>
   <td><input type="text" name="matricule" id="matricule"/></td>
  </tr><tr>
   <td> </td>
   <td><input type="submit" value="Log In"/></td>
  </tr>
 </table>
</form>
<?php
$GLOBALS['TEMPLATE']['content'] = ob_get_clean();

// display the page
include 'include/template.php'
?>
