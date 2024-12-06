<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
session_destroy();
header("Location: index.php");
exit();
?>
<script>
  window.location.reload(true);
</script>