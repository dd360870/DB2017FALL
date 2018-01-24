<?php
$servername="localhost";
$username="root";
$password="dd360870";

try {
  $conn = new PDO("mysql:host=$servername;dbname=demo;charset=UTF8", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //echo "Connected.";
} catch (PDOException $e) {
  echo "Error.".$e->getMessage()."<br/>";
}
ini_set('session.use_strict_mode', 1);
my_session_start();

#common functions
function isPostDeletableByUid($conn, $pid, $uid) {
  $sql = "SELECT * FROM `post` JOIN `organization` ON post.gid=organization.gid WHERE `pid`=$pid";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  if($stmt->rowCount() == 0) {
    return false;
  } else {
    $row = $stmt->fetch();
    if($uid == $row['uid'] || $uid == $row['mgr_uid']) {
      return true;
    }
  }
  return false;
}
function get_group_name($conn, $gid) {
  $sql = "SELECT `name` FROM `organization` WHERE `gid`=$gid";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  if($stmt->rowCount() == 0){
    return "Error, can't fetch group name of gid=$gid.";
  }
  $result = $stmt->fetch();
  return $result['name'];
}
function check_mgr($conn, $gid, $uid) {
  $sql = "SELECT * FROM `organization` WHERE gid=$gid";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $result = $stmt->fetch();
  if($result['mgr_uid'] == $uid) {
    return true;
  }
  return false;
}
function get_nickname($conn, $uid) {
  $stmt = $conn->prepare("SELECT `nickname` FROM `user` WHERE `uid` = $uid");
  $stmt->execute();
  $result = $stmt->fetch();
  return $result['nickname'];
}
function my_session_start() {
  session_start();
  if(!empty($_SESSION['delete_time']) && $_SESSION['delete_time'] < time() - 180) {
    session_destroy();
    session_start();
  }
}
function regen_session_id() {
  if(session_status() != PHP_SESSION_ACTIVE) {
    session_start();
  }
  $newid = session_create_id('886-');
  $_SESSION['delete_time'] = time();
  session_commit();
  ini_set('session.use_strict_mode', 0);
  session_id($newid);
  session_start();
}
function isApplied($conn, $gid, $uid) {
  $sql = "SELECT * FROM `apply` WHERE `gid`=$gid AND `uid`=$uid";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  if($stmt->rowCount()>0) {
    return true;
  }
  return false;
}
function isGroupMember($conn, $gid, $uid) {
  $sql = "SELECT * FROM `member` WHERE `gid`=$gid AND `uid`=$uid";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $sql2 = "SELECT * FROM `organization` WHERE gid=$gid AND mgr_uid=$uid";
  $stmt2 = $conn->prepare($sql2);
  $stmt2->execute();
  if($stmt->rowCount() == 0 && $stmt2->rowCount() == 0) {
    return false;
  }
  return true;
}
function isPostOwner($conn, $pid, $uid) {
  $sql = "SELECT * FROM `post` WHERE pid=$pid";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  if($stmt->rowCount() == 0) return false;
  $result = $stmt->fetch();
  if($result['uid']==$uid)
    return true;
  return false;

}
function groupMemberCount($conn, $gid) {
  $sql = "SELECT `uid` FROM `member` WHERE `gid`=$gid";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  return $stmt->rowCount()+1;#including group manager
}
?>
