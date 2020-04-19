<?php
  session_start();
  //echo isset($_SESSION['login']);

/**
 * MySQL configuration for connection with your Flarum forum database
 * so you can read and delete users
 */
$servername = ""; //put here your MySQL host 
$your_db_table = ""; //put here the name of your MySQL table
$username = ""; //put here MySQL user
$password = ""; //here MySQL password

/**
 * Basic login for admin page
 * yep set a strong password!
 */
$your_username = "Admin"; //put here your username
$your_password = ""; //put here a strong password

/**
 * Little customization link Title and H1
 * 
 */
$your_forum_title = "Node-RED Italia";
$page_title = "Admin users";
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title><?php echo $your_forum_title." ".$page_title; ?></title>
    <style>
     .table-striped tbody tr:nth-of-type(2n+1) { background-color: rgba(0,0,0,.03); }
    </style>
  </head>
 <body>
<div class="container">
    <h1><?php echo $page_title; ?></h1>

<?php
if(isset($_SESSION['login'])) {
 echo "<p classs=\"text-center\">Benvenuto ". $your_username ."</p>";
 // Create connection
 $conn = new mysqli($servername, $username, $password, $dbname);
 // Check connection
 if ($conn->connect_error) {
  die("<div class=\"alert alert-warning\" role=\"alert\">Connection failed: " . $conn->connect_error."</div>");
 }

 if(isset($_GET['action']) AND $_GET['action'] == "delete"){
  $sql_count = "SELECT id FROM ".$your_db_table;
  $result_count = $conn->query($sql_count);

 if ($result_count->num_rows > 0) {
  // sql to delete a record
  $sql_delete = "DELETE FROM ".$your_db_table." WHERE id=".$_GET['id'];

  if ($conn->query($sql_delete) === TRUE) {
   echo "<div class=\"alert alert-success\" role=\"alert\">Record deleted successfully</div>";
  } else {
   echo "<div class=\"alert alert-danger\" role=\"alert\">Error deleting record: " . $conn->error."</div>";
  }
 } else {
  echo "<div class=\"alert alert-warning\" role=\"alert\">Sorry, this user does not exist!</div>";
 }
}
?>

<div class="table-responsive">
<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th scope="col">#Id</th>
      <th scope="col">User</th>
      <th scope="col">eMail</th>
      <th scope="col">Confirm</th>
      <th scope="col">Joined @</th>
      <th scope="col">Last login</th>
      <th scope="col">Discussions</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>

<?php
 $sql = "SELECT id, username, email, is_email_confirmed, joined_at, last_seen_at, discussion_count FROM ".$your_db_table;
 $result = $conn->query($sql);

 if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {

    echo "<tr>
      <th scope=\"row\">" . $row["id"]. "</th>
      <td>" . $row["username"]. "</td>
      <td>" . $row["email"]. "</td>
      <td>" . $row["is_email_confirmed"]. "</td>
      <td>" . $row["joined_at"]. "</td>
      <td>" . $row["last_seen_at"]. "</td>
      <td>" . $row["discussion_count"]. "</td>
      <td><a href=\"#\" data-href=\"admin-users.php?action=delete&id=" . $row["id"]. "\" data-toggle=\"modal\" data-target=\"#confirm-delete\" class=\"btn btn-outline-danger btn-sm\">Delete user</a></td>
    </tr>";
 //$user = "#" . $row["id"]. "<strong>" . $row["username"]. "</strong> - ". $row["email"];
  }
 } else {
  echo "<div class=\"alert alert-warning\" role=\"alert\">0 results</div>";
 }
 $conn->close();
?>
  </tbody>
</table>
</div>
<?php
}
else
{
?>
    <h3 class="text-center">Login</h3>
    <?php
      if(isset($_POST['submit'])){
        $username = $_POST['username']; $password = $_POST['password'];
        if($username === $your_username && $password === $your_password){
          $_SESSION['login'] = true; header('LOCATION:admin-users.php'); die();
        } else {
          echo "<div class='alert alert-danger'>Username and Password do not match.</div>";
        }
      }
    ?>
    <form action="" method="post">
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" class="form-control" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="pwd">Password:</label>
        <input type="password" class="form-control" id="pwd" name="password" required>
      </div>
      <button type="submit" name="submit" class="btn btn-success">Login</button>
    </form>
<?php
}
?>
</div>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Confirm Delete
            </div>
            <div class="modal-body">
                <p>You are about to delete a <?php echo $your_forum_title; ?>'s user<?php //echo $user; ?>, this procedure is irreversible.<br />
		Do you want to proceed?</p>
		<p class="debug-url"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script>
        $('#confirm-delete').on('show.bs.modal', function(e) {
          $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
            
          $('.debug-url').html('Delete URL: <strong>' + $(this).find('.btn-ok').attr('href') + '</strong>');
        });
    </script>

  </body>
</html>
