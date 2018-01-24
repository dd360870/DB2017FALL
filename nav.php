<?php
include_once('basic.php');
$current_uid = empty($_SESSION['uid'])?null:$_SESSION['uid'];
?>
<nav class="navbar navbar-dark bg-dark navbar-expand-sm">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item"><a class="nav-link" href="index">Home</a></li>
          <?php
          if(!empty($current_uid)) {
            echo '<li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                My groups
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">';
            $sql = "SELECT DISTINCT organization.gid, name
                  FROM `member` RIGHT JOIN `organization` ON member.gid = organization.gid
                  WHERE `uid` = $current_uid OR `mgr_uid` = $current_uid";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            if($stmt->rowCount() == 0) {
              echo "<p class='dropdown-item'>No groups yet</p>";
            } else {
              $result = $stmt->fetchAll();
              foreach($result as $row) {
                echo "<a class='dropdown-item' href='group.php?gid=".$row['gid']."&action=post'>".$row['name']."</a>";
              }
            }
            echo '</div></li>';
          }
          ?>
          <!--a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <a class="dropdown-item" href="#">Something else here</a-->
    </ul>
      <?php
      if(!empty($current_uid)) {
        $nickname = get_nickname($conn, $_SESSION['uid']);
        echo "
          <form class='form-inline' onsubmit='/*return confirm(\"Log out?\");*/' action='login.php' method='post' style=''>
          <input name='action' value='logout' hidden />
          <label style='color:white;padding:8px;'>$nickname</label>
          <input class='btn btn-outline-success' type='submit' value='Logout' />
          </form>";
      } else {
        echo "<a class='btn btn-outline-primary' href='login.php'>Log In</a>";
      }
?>
  </div>
</nav>
