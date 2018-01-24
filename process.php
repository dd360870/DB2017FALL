<?php
include_once('basic.php');

if(!empty($_POST) && !empty($_POST['action']) && !empty($_SESSION['uid'])) {
  $current_time = date("Y-m-d H:i:s");
  $current_uid = $_SESSION['uid'];//current user.
  $gid = empty($_POST['gid'])?null:$_POST['gid'];//group id.
  $action = $_POST['action'];//action to perform
  #action DELETE group
  if($action == 'delete_group') {
    if(!check_mgr($conn, $gid, $current_uid)) {
      echo "invalid delete operation.";
    } else {
      $sql = "DELETE FROM `organization` WHERE `gid`=$gid";
      $stmt = $conn->prepare($sql);
      $result_org = $stmt->execute();
      $sql = "DELETE FROM `member` WHERE `gid`=$gid";
      $stmt = $conn->prepare($sql);
      $result_mem = $stmt->execute();
      $sql = "DELETE FROM `apply` WHERE `gid`=$gid";
      $stmt = $conn->prepare($sql);
      $result_app = $stmt->execute();
      $sql = "DELETE FROM `post` WHERE `gid`=$gid";
      $stmt = $conn->prepare($sql);
      $result_pos = $stmt->execute();
      if($result_org && $result_mem && $result_app && $result_pos) {
        echo "Delete group gid=$gid successfully.";
      } else {
        echo "Delete group gid=$gid failed.";
        echo "organization=".($result_org?"true":"false");
        echo "member=".($result_mem?"true":"false");
        echo "apply=".($result_app?"true":"false");
        echo "apply=".($result_pos?"true":"false");
      }
    }
  }
  else if($action == 'apply') {
    $sql = "INSERT INTO `apply`(`uid`, `gid`, `time`) VALUES ($current_uid,$gid,'$current_time')";
    $stmt = $conn->prepare($sql);
    $result = $stmt->execute();
    if($result) {
      echo "Apply group gid=$gid success, please wait manager to confirm.";
    } else {
      echo "Apply group gid=$gid failed, something goes wrong.";
    }
  }
  else if($action == 'confirm_apply') {
    if(check_mgr($conn, $gid, $current_uid)) {
      $uid = $_POST['uid'];//uid to join the group.
      $aid = $_POST['aid'];//apply id to delete.
      //insert user to `member`
      $sql = "INSERT INTO `member`(`uid`, `gid`, `time`) VALUES ($uid, $gid, '$current_time')";
      $stmt=$conn->prepare($sql);
      $result = $stmt->execute();
      //delete apply row from `apply`
      $sql = "DELETE FROM `apply` WHERE aid=$aid";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      if($result) {
        echo "Join success.";
      } else {
        echo "Sorry. Something goes wrong. Please try again.";
      }
    } else {
      echo "Invalid operation.";
    }
  }
  else if($action == 'send_post') {
    if(isGroupMember($conn, $gid, $current_uid) && !empty($_POST['title']) && !empty($_POST['content'])) {
      $title = $_POST['title'];
      $content = $_POST['content'];
      $sql = "INSERT INTO `post`(`title`, `content`, `uid`, `gid`, `time`) VALUES ('$title','$content',$current_uid,$gid,'$current_time')";
      $stmt = $conn->prepare($sql);
      $result = $stmt->execute();
      if($result) {
        echo "Post successed.";
      } else {
        echo "Error - Post failed.";
      }
    } else {
      echo "Error - You are not the member of this group.";
    }
  }
  else if ($action == 'delete_post') {
    $pid = $_POST['pid'];
    if(isPostDeletableByUid($conn, $pid, $current_uid)) {
      $sql = "DELETE FROM `post` WHERE `pid`=$pid";
      $stmt = $conn->prepare($sql);
      $result = $stmt->execute();
      if($result) {
        echo "DELETE_POST successed.";
      } else {
        echo "Error - DELETE_POST failed. Something goes wrong. Please try again.";
      }
    } else {
      echo "Error - DELETE_POST failed. You are not available to do this operation.";
    }
  }
  else if ($action == 'drop_out') {
    $uid = $_POST['uid'];
    if(isGroupMember($conn, $gid, $uid) || check_mgr($conn, $gid, $current_uid)) {
      $sql = "DELETE FROM `member` WHERE `uid`=$uid AND `gid`=$gid";
      $stmt = $conn->prepare($sql);
      $result = $stmt->execute();
      if($result) {
        echo 'DROP_OUT from the group '.get_group_name($conn, $gid).' successed.';
      } else {
        echo "Error - DROP_OUT failed. Something goes wrong. Please try again.";
      }
    } else {
      echo "Error - DROP_OUT failed.";
    }
  }
  else if($action == 'update_post') {
    $pid = $_POST['pid'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    if(isPostOwner($conn, $pid, $current_uid)) {
      $sql = "UPDATE `post` SET `title`='$title',`content`='$content',`time`='$current_time' WHERE `pid`=$pid";
      $stmt = $conn->prepare($sql);
      $result = $stmt->execute();
      if($result)
        echo "Update success.";
      else {
        echo "Failed";
      }
    } else {
      echo "Invalid operation.";
    }
  }
  else if($action == 'group_setting') {
    if(check_mgr($conn, $gid, $current_uid)) {
      $name = nl2br($_POST['name']);
      $description = nl2br($_POST['description']);
      if(!empty($name) && !empty($description)) {
        $sql = "UPDATE `organization` SET `name`='$name', `description`='$description' WHERE `gid`=$gid";
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute();
        if($result) {
          echo "Success";
        } else {
          echo "Failed";
        }
      }
    }
    else {
      echo "You are not the manager of this group.";
    }
  }
} else {
  echo "No action.";
}
?>
