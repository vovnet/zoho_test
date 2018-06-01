<?php 

session_start();

require_once __DIR__.'/Request.php';
require_once __DIR__.'/Lead.php';
require_once __DIR__.'/Converter.php';

if ( !empty($_REQUEST['first_name']) && 
      !empty($_REQUEST['last_name']) &&
      !empty($_REQUEST['email']) &&
      !empty($_REQUEST['phone']) &&
      !empty($_REQUEST['company']) ) {

  $lead = new Lead();
  $lead->addData('First Name', $_REQUEST['first_name'])
    ->addData('Last Name', $_REQUEST['last_name'])
    ->addData('Email', $_REQUEST['email'])
    ->addData('Phone', $_REQUEST['phone'])
    ->addData('Company', $_REQUEST['company']);

  if ($lead->isCreated()) {
    $converter = new Converter();
    $_SESSION['zoho_result'] = 'Lead exists! New deal was created.';
  } else {
    $lead->create();
    $_SESSION['zoho_result'] = 'Lead was created.';
  }
  header("Location: " . "http://" . $_SERVER['HTTP_HOST']);
  exit;
}

$zohoResult = '';
if (isset($_SESSION['zoho_result'])) {
  $zohoResult = $_SESSION['zoho_result'];
  unset($_SESSION['zoho_result']);
}

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Zoho Test</title>
  </head>

  <body>
    <?= $zohoResult ?>
      <form action="" method="POST">
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name"></br>
        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name"></br>
        <label for="email">Email:</label>
        <input type="text" name="email"></br>
        <label for="phone">Phone:</label>
        <input type="tel" name="phone"></br>
        <label for="company">Company:</label>
        <input type="text" name="company"></br>
        <input type="submit" name="Submit">
      </form>
  </body>

</html>