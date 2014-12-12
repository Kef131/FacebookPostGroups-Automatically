<?php include("topadmin.php");
if((isset($_SESSION['userpass']))&&(isset($_SESSION['useraccount'])))
{
unset($_SESSION['fbs_userpass']);
unset($_SESSION['fbs_useraccount']);
unset($_SESSION['fbs_error']);
unset($_SESSION['fbs_admin']);
session_destroy();
echo("<script>window.location='index.php'</script>");
}
else
{
echo("<script>window.location='index.php'</script>");
}
?>