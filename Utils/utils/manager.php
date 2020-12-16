<?php
function getsitecolor(){
  return "#1565c0 blue darken-3";
}

function showregister( $title){
  ?>
  <script type="text/javascript">
  $.sweetModal({
    title: '<?php echo $title; ?>',
	  content: '<?php echo '<form class="index" action="index.php" method="post"><h5>SteamID</h5><input type="text" name="steamid" placeholder="SteamID" required><br><h5>Name</h5><br><input type="text" name="name" placeholder="Name" required><button type="submit" name="register" class="btn green right">Hinzufügen</button></form>'; ?>'

  });
  </script>
  <?php
}

function showModal( $title, $message){
  ?>
  <script type="text/javascript">
  $.sweetModal({
    title: '<?php echo $title; ?>',
	  content: '<?php echo $message; ?>'

  });
  </script>
  <?php
}

function showModalRedirect($type, $title, $message, $location){
  ?>
  <script type="text/javascript">
  $.sweetModal({
    title: '<?php echo $title; ?>',
	  content: '<?php echo $message; ?>',
	  icon: $.sweetModal.ICON_<?php echo $type; ?>,
    onClose: function(){
            window.location = "<?php echo $location; ?>";
          }
  });
  </script>
  <?php
}
 ?>
