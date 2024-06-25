<?php
//connect to the database

$servername="localhost";
$username="root";
$password="";
$database="db_harshit";

$insert = false;
$update= false;
$delete=false;


//create connection
$conn = new mysqli($servername, $username, $password, $database);

//die if connection was not successful
if(!$conn){
  die("Sorry, we failed to connect: ".mysqli_connect_error());
}

//inserting records
if(isset($_GET['delete'])){
  $sno=$_GET['delete'];
  $sql="DELETE FROM `notes` WHERE `sno`=$sno";
  $result =mysqli_query($conn, $sql);
  if($result){
    $delete=true;
  }
}
if($_SERVER["REQUEST_METHOD"]=='POST'){
  if(isset($_POST['snoEdit'])){
    //update the record
    $sno=$_POST["snoEdit"];
    $title = $_POST["titleEdit"];
    $description = $_POST["descriptionEdit"]; 
  
    //sql query
    $sql="UPDATE `notes` SET `title`='$title', `description` = '$description' WHERE `notes`.`sno`='$sno'";
    $result = mysqli_query($conn,$sql);
    if($result){
      $update=true;
    }
    
  }else{

  
  $title = $_POST["title"];
  $description = $_POST["description"]; 

  //sql query
  $sql="INSERT INTO `notes`(`title`,`description`) VALUES ('$title','$description')";
  $result = mysqli_query($conn,$sql);

  if($result){
    $insert=true;
  }else{
    $insert=false;
  }
}
}
 ?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notes app</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    

  </head>
  <body>

  <!-- Button trigger modal -->
<!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
  Edit modal
</button> -->

<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editModalLabel">Edit Note</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form method="post" action="index.php">
        <input type="hidden" name="snoEdit" id="snoEdit">
            <div class="mb-3">
              <label for="title" class="form-label">Note Title</label>
              <input type="text" class="form-control" name="titleEdit" id="titleEdit" aria-describedby="emailHelp" required>
            </div>
            <div class="mb-3">
              <label for="desc" class="form-label">Note Description</label>
              <textarea class="form-control" id="descriptionEdit" name="descriptionEdit" required></textarea>
            </div>
            
          
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success">Update Note</button>
      </div>
      </form>
      </div>
    </div>
  </div>
</div>


    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">Notes</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">About</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Contact</a>
              </li>
              
              
            </ul>
            
          </div>
        </div>
      </nav>

<?php
if($insert){
  echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
  <strong>Success!</strong> Your note has been added successfully.
  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
</div>";
}
?>

<?php
if($delete){
  echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
  <strong>Success!</strong> Your note has been deleted successfully.
  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
</div>";
}
?>

<?php
if($update){
  echo "<div class='alert alert-primary alert-dismissible fade show' role='alert'>
  <strong>Success!</strong> Your note has been updated successfully.
  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
</div>";
} ?>

      <div class="p-5">
        <h2>Add a Note</h2>
        <form method="post" action="index.php">
            <div class="mb-3">
              <label for="title" class="form-label">Note Title</label>
              <input type="text" class="form-control" name="title" id="title" aria-describedby="emailHelp" required>
            </div>
            <div class="mb-3">
              <label for="desc" class="form-label">Note Description</label>
              <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Note</button>
          </form>

      </div>

      <div class="container my-4">
        
         <table class="table" id="myTable">
  <thead>
    <tr>
      <th scope="col">S.No</th>
      <th scope="col">Title</th>
      <th scope="col">Description</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
  <?php
  //getting all entries from a database
        $sql = "SELECT * FROM `notes`";
        $result = mysqli_query($conn, $sql);
        $sno=0;
        while($row = mysqli_fetch_assoc($result)){
          $sno++;
          echo "
          <tr>
      <th scope='row'>".$sno."</th>
      <td>".$row['title']."</td>
      <td>".$row['description']."</td>
      <td><button class='edit btn btn-sm btn-success' id=".$row['sno'].">Edit</button> &nbsp; <button class='delete btn btn-sm btn-danger' id=d".$row['sno'].">Delete</button></td>
    </tr>";
        }
         ?>
        
  </tbody>
</table>
      </div>

      
       <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
       <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>

        <script>
      let table = new DataTable('#myTable', {
    // options
      });
      </script>
      <script>
      edits = document.getElementsByClassName('edit');
      Array.from(edits).forEach((element)=>{
        element.addEventListener('click', (e)=>{
          tr=e.target.parentNode.parentNode;
          title=tr.getElementsByTagName("td")[0].innerText;
          description=tr.getElementsByTagName("td")[1].innerText;
          titleEdit.value=title;
          descriptionEdit.value=description;
          snoEdit.value=e.target.id;
          $('#editModal').modal('toggle');
          
        })
      })

      deletes = document.getElementsByClassName('delete');
      Array.from(deletes).forEach((element)=>{
        element.addEventListener('click', (e)=>{
          sno=e.target.id.substr(1,);

          if(confirm("Do you want to delete this note?")){
            window.location=`index.php?delete=${sno}`
          }
          
        })
      })
      
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
  </body>
</html>