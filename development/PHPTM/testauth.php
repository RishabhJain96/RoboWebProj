<?
  // Set the minimum access level required to access this page.
  $REQUSRACCESSLVL = 100;

  // Check the current user's authentication level (if not > the min.
  // required, it will automagically redirect to the authentication page,
  // which will return them here once authenticated.
  include('checkauth.php');
  include('head.php');
?>
This should be protected and no user with access level < 100 should be 
able to view it.
<?
  include('foot.php');
?>
