<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>留言板</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
  <script
			  src="https://code.jquery.com/jquery-3.2.1.js"
			  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
			  crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
  <link rel="stylesheet" type="text/css" href="main.css">
  <script src="main.js"></script>
</head>
<body>
  <?php include_once('nav.php'); ?>
<?php
if(!empty($_GET) && !empty($_GET['gid']) && !empty($_SESSION['uid'])) {
  $current_uid = $_SESSION['uid'];
  $gid = $_GET['gid'];
  $action = $_GET['action'];
  echo "<div class='container'><div class='row'><div class='col-md-10 offset-md-1'>";
  echo '<h1 class="display-4 text-center">'.get_group_name($conn, $gid).'</h1>';
  echo '<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link '.($action=='post'?'active':'').'" href="group.php?gid='.$_GET['gid'].'&action=post">Posts</a>
  </li>
  <li class="nav-item">
    <a class="nav-link '.($action=='member'?'active':'').'" href="group.php?gid='.$_GET['gid'].'&action=member">Member</a>
  </li>
  <li class="nav-item">
    <a class="nav-link '.($action=='add_post'?'active':'').'" href="group.php?gid='.$_GET['gid'].'&action=add_post">Add post</a>
  </li>';
  if(check_mgr($conn, $gid, $current_uid)) {
    echo'<li class="nav-item">
            <a class="nav-link '.($action=='apply'?'active':'').'" href="group.php?gid='.$_GET['gid'].'&action=apply">Apply</a>
          </li>
          <li class="nav-item">
            <a class="nav-link '.($action=='setting'?'active':'').'" href="group.php?gid='.$_GET['gid'].'&action=setting">Settings</a>
          </li>';
  }
  echo "</ul>";
  /*echo "<a class='btn btn-primary' href='group.php?gid=".$_GET['gid']."&action=apply' style='margin:5px;padding:5px;font-size:18px;'>Member Application</a>";
  echo "<a class='btn btn-primary' href='group.php?gid=".$_GET['gid']."&action=member' style='margin:5px;padding:5px;font-size:18px;'>Our Members</a>";
  echo "<a class='btn btn-primary' href='group.php?gid=".$_GET['gid']."&action=post' style='margin:5px;padding:5px;font-size:18px;'>Posts</a>";
  echo "<a class='btn btn-primary' href='group.php?gid=".$_GET['gid']."&action=add_post' style='margin:5px;padding:5px;font-size:18px;'>Add Post</a>";*/
  if($action == 'apply') {
    if(check_mgr($conn, $gid, $current_uid)){
      $sql = "SELECT * FROM `apply` JOIN `user` ON apply.uid=user.uid WHERE gid=$gid";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      if($stmt->rowCount() == 0) {
        echo "<p style='margin:20px;'>No one applied for your group.</p>";
      } else {
        echo "<table class='table table-striped table-dark text-center' style='margin: 0 auto;'><tr><th>Username</th><th class='td-hide'>Nickname</th><th>Apply Time</th><th>Action</th></tr>";
        $result = $stmt->fetchAll();
        foreach($result as $row) {
          echo "<tr><td>".$row['username']."</td><td class='td-hide'>".$row['nickname']."</td><td>".$row['time']."</td><td>";
          echo "<button class='btn btn-outline-success' onclick='confirm_apply(".$row['uid'].",".$row['gid'].",".$row['aid'].")'>Confirm</button>";
          echo "</td></tr>";
        }

        /*$result = $stmt->fetchAll();
        foreach($result as $row) {
          echo'<div class="card" style="max-width:90vw;margin:10px;">
            <div class="card-body">
              <table class="text-center">
                <tr><td>Username</td><td>'.$row['username'].'</td></tr>
                <tr><td>Nickname</td><td>'.$row['nickname'].'</td></tr>
                <tr><td>Time</td><td>'.$row['time'].'</td></tr></table>'.
                "<form onsubmit='return confirm(\"Confirm ".$row['nickname']." to join the group?\");' action='process.php' method='post'>".
                    "<input name='action' value='confirm_apply' hidden/>".
                    "<input name='gid' value=".$row['gid']." hidden/>".
                    "<input name='uid' value=".$row['uid']." hidden/>".
                    "<input name='aid' value=".$row['aid']." hidden/>".
                    "<input class='btn btn-outline-success' type='submit' value='Confirm'/>".
                    "</form>".'
            </div>
            </div>';
        }*/
      }
      echo "</table>";
    } else {
      echo "<p style='margin:20px;'>You are not the MANAGER of this group.</p>";
    }
  }
  else if($action == 'member') {
    $sql = "SELECT * FROM `member` JOIN `user` ON member.uid=user.uid WHERE gid=$gid";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    $sql = "SELECT * FROM `organization` JOIN `user` ON mgr_uid=uid WHERE gid=$gid";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $manager = $stmt->fetch();
    echo "<table class='table table-striped table-dark text-center' style='margin: 0 auto;'><tr><th class='td-hide'>Username</th><th>Nickname</th><th>Join Time</th><th>Action</th></tr>";
    echo "<tr><td class='td-hide'>".$manager['username']."</td><td>".$manager['nickname']."</td><td>".$manager['create_date']."</td><td>MANAGER</td></tr>";
    foreach($result as $row) {
      echo "<tr><td class='td-hide'>".$row['username']."</td><td>".$row['nickname']."</td><td>".$row['time']."</td><td>";
      if($row['uid'] == $current_uid) {
        /*echo "<form onsubmit='return confirm(\"DROP OUT of the group?\");' action='process.php' method='post' autocomplete='off'>".
              "<input name='action' value='drop_out' hidden/>".
              "<input name='gid' value=$gid hidden/>".
              "<input name='uid' value=".$row['uid']." hidden/>".
              "<input class='btn btn-outline-danger' type='submit' value='Drop Out' /></form>";*/
        echo "<button class='btn btn-outline-danger' onclick='drop_out($gid,".$row['uid'].",\"DROP OUT of the group?\")'>Drop out</button>";
      } else if($manager['mgr_uid'] == $current_uid) {
        /*echo "<form onsubmit='return confirm(\"KICK OUT of the group?\");' action='process.php' method='post' autocomplete='off'>".
              "<input name='action' value='drop_out' hidden/>".
              "<input name='gid' value=$gid hidden/>".
              "<input name='uid' value=".$row['uid']." hidden/>".
              "<input class='btn btn-outline-danger' type='submit' value='Kick Out' /></form>";*/
        echo "<button class='btn btn-outline-danger' onclick='drop_out($gid,".$row['uid'].",\"KICK OUT of the group?\")'>Kick out</button>";
      }
      echo "</td></tr>";
    }
    echo "</table>";
  }
  else if($action == 'post') {
    $sql = "SELECT * FROM `post` JOIN `user` ON post.uid=user.uid WHERE `gid`=$gid";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if($stmt->rowCount() > 0) {
      $result = $stmt->fetchAll();
      /*echo "<table class='table table-striped table-dark text-center' style='margin: 0 auto;'><tr><th>Title</th><th>Content</th><th>User</th><th>Time</th><th>Action</th></tr>";
      foreach ($result as $row) {
        $pid = $row['pid'];
        echo "<tr><td>".$row['title']."</td><td>".$row['content']."</td><td>".$row['nickname']."</td><td>".$row['time']."</td><td>";
        if(isPostDeletableByUid($conn, $pid, $current_uid)) {
          echo "<form action='process.php' method='post'>".
                "<input name='action' value='delete_post' hidden/>".
                "<input name='pid' value=$pid hidden/>".
                "<input name='gid' value=$gid hidden/>".
                "<input class='btn btn-outline-danger' type='submit' value='Delete' /></form>";
        }
        echo "</td></tr>";
      }
      echo "</table>";*/
      foreach($result as $row) {
        $pid = $row['pid'];
        echo '<div class="card" style="margin: 20px;">
              <h5 class="card-header">'.$row['title'].'</h5>
              <div class="card-body">
              <p class="card-text">'.nl2br($row['content']).'</p>
              </div>
              <div class="card-footer text-muted" style="float: right;"><font style="">'.$row['nickname'].'</font> / <font>'.$row['time'].'</font>'.
              ((isPostDeletableByUid($conn, $pid, $current_uid))?'<button class="btn btn-sm btn-outline-danger" style="float: right;" onclick="delete_post('.$gid.','.$pid.')">Delete</button>':'').
              (isPostOwner($conn, $pid, $current_uid)?'<button class="btn btn-sm btn-outline-warning" style="float: right;margin-right:10px;" onclick="window.location.replace(\'group.php?pid='.$pid.'&action=update_post&gid='.$gid.'\')">Edit</button>':'').'
              </div>
              </div>';
      }
    } else {
      echo "<p style='margin:20px;'>No post at this time. make a post :)</p>";
    }
  }
  else if($action == 'add_post') {
    echo '<form class="form-bg" style="max-width:50rem;margin:30px auto;" action="javascript:send_post('.$gid.')" method="get" id="commentForm" autocomplete="off">'.
    "<input name='gid' value=$gid hidden />".
    '<input name="action" value="send_post" hidden />'.
    '<div class="form-group">
    <label for="title">Title</label>
    <input type="text" name="title" class="form-control" id="title" placeholder="Title" required></div>'.
    '<div class="form-group">
    <label for="content">Content</label>
    <textarea name="content" class="form-control" id="content" rows="12" placeholder="Content" required></textarea></div>'.
    '<input class="btn btn-primary" type="submit" value="Submit" /></form>';
  }
  else if($action == 'update_post') {
    $pid = $_GET['pid'];
    $sql = "SELECT `title`,`content` FROM `post` WHERE `pid`=$pid";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();
    echo '<form class="form-bg" style="margin: 30px auto;" action="javascript:update_post('.$pid.','.$gid.')" method="get" id="commentForm" autocomplete="off">'.
    "<input name='gid' value=$gid hidden />".
    '<input name="action" value="send_post" hidden />'.
    '<div class="form-group">
    <label for="title">Title</label>
    <input type="text" name="title" class="form-control" id="title" placeholder="Title" value="'.$result['title'].'" required></div>'.
    '<div class="form-group">
    <label for="content">Content</label>
    <textarea name="content" class="form-control" id="content" rows="8" placeholder="Content" required>'.$result['content'].'</textarea></div>'.
    '<input class="btn btn-primary" style="margin:6px;" type="submit" value="Update">'.
    "<input class='btn btn-secondary' style='margin:6px;background-color:#AAAAAA' onclick='window.location.replace(\"group.php?action=post&gid=$gid\")' type='button' value='Cancel'></form>";
  }
  else if($action == 'setting') {
    if(check_mgr($conn, $gid, $current_uid)) {
      $sql = "SELECT `name`, `description` FROM `organization` WHERE `gid`=$gid";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetch();
      echo "<form class='form-bg' action='javascript:group_setting($gid);' autocomplete='off' style='max-width:20rem;margin: 20px auto;'>
          <div class='form-group'>
            <label>Group name</label>
            <div class='input-group mb-3'>
              <input type='text' id='groupName' name='name' class='form-control' style='margin:3px;' value='".$result['name']."' aria-describedby='basic-addon2' required disabled>
              <div class='input-group-append'>
                <button onclick='$(\"#groupName\").removeAttr(\"disabled\")' class='btn btn-outline-secondary' style='margin:3px;' type='button'>Edit</button>
              </div>
            </div>
          </div>
          <div class='form-group'>
            <label>Group description</label>
            <div class='input-group mb-3'>
              <input type='text' id='groupDescription' name='description' class='form-control' style='margin:3px;' value='".$result['description']."' required disabled>
              <div class='input-group-append'>
                <button onclick='$(\"#groupDescription\").removeAttr(\"disabled\")' class='btn btn-outline-secondary' style='margin:3px;' type='button'>Edit</button>
              </div>
            </div>
          </div>
          <input class='btn btn-success' type='submit' value='Save'>
          <input style='float:right;' class='btn btn-danger' type='button' onclick='delete_group($gid)' value='Delete Group'>
          </form>";
    } else {
      echo "404";
    }
  }
  echo "</div>";#col
} else {
  echo "<h1>Error - Please login first.</h1>";
}
?>
</div><!-- .row -->
  </div><!-- .container -->
  <footer id="footer" class="text-center" style="position: relative;bottom: 0;background:rgba(255,255,255,0.2);padding:10px;width:100%;">
    <font style='padding:5px;'>© 2018 Ruzy Lee</font>
    <a href="https://www.freepik.com/free-vector/abstract-background-design_1054590.htm" style='text-decoration:none;padding:5px;'>Background credit by Freepik</a>
  </footer>
</body>
</html>
