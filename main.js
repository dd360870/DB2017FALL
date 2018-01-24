function adj_footer() {
  if($(window).height()>$('body').height()) {
    $('footer').css('position', 'absolute');
  } else {
    $('footer').css('position', 'relative');
  }
}
$( window ).ready(adj_footer);
$( window ).resize(adj_footer);
/**
 * @param {int} gid - group id
 * @param {int} pid - post id
 */
function delete_post(gid, pid) {
  if(confirm("Are you sure to delete this post ?")) {
    var posting = $.post(
      'process.php',
      { action: 'delete_post', gid: gid, pid: pid } );
    posting.done(function( data ) {
      if(!alert(data)){window.location.reload();}
    });
  }
}
function delete_group(gid) {
  if(confirm("Are you sure to delete this group ?")) {
    var posting = $.post( 'process.php', { action: 'delete_group', gid: gid} );
    posting.done(function( data ) {
      if(!alert(data)){window.location.replace('index.php');}
    });
  }
}
function apply(gid) {
  var posting = $.post( 'process.php', { action: 'apply', gid: gid} );
  posting.done(function( data ) {
    if(!alert(data)){window.location.reload();}
  });
}
function confirm_apply(uid, gid, aid) {
  var posting = $.post( 'process.php', { action: 'confirm_apply', gid: gid, uid: uid, aid: aid} );
  posting.done(function( data ) {
    if(!alert(data)){window.location.reload();}
  });
}
function send_post(gid) {
  var posting = $.post( 'process.php', { action: 'send_post', gid: gid, title: $('#title').val(), content: $('#content').val()} );
  posting.done(function( data ) {
    if(!alert(data)){window.location.replace('group.php?action=post&gid='+gid);}
  });
}
function drop_out(gid, uid, alert_msg) {
  if(confirm(alert_msg)) {
    var posting = $.post( 'process.php', { action: 'drop_out', gid: gid, uid: uid} );
    posting.done(function( data ) {
      if(!alert(data)){window.location.reload();}
    });
  }
}
function update_post(pid, gid) {
  var posting = $.post( 'process.php', { action: 'update_post', pid: pid, title: $('#title').val(), content: $('#content').val()} );
  posting.done(function( data ) {
    if(!alert(data)){window.location.replace('group.php?action=post&gid='+gid);}
  });
}
function group_setting(gid) {
  var posting = $.post( 'process.php', { action: 'group_setting', gid: gid, name: $('#groupName').val(), description: $('#groupDescription').val()} );
  posting.done(function( data ) {
    if(!alert(data)){window.location.reload();}
  });
}
function nl2br (str, is_xhtml) {
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}
