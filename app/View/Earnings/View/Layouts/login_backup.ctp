<!DOCTYPE html>
<html lang="en">

<head>
        <title><?php echo SITE_TITLE; ?></title><meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="<?php echo $this->webroot; ?>theme/css/bootstrap.min.css" />
		<link rel="stylesheet" href="<?php echo $this->webroot; ?>theme/css/bootstrap-responsive.min.css" />
        <link rel="stylesheet" href="<?php echo $this->webroot; ?>theme/css/matrix-login.css" />
        <link href="<?php echo $this->webroot; ?>theme/font-awesome/css/font-awesome.css" rel="stylesheet" />
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

    </head>
    <body>
        <div id="loginbox">
            <?php echo $this->fetch('content'); ?>
            <script src="<?php echo $this->webroot; ?>alertify/src/alertify.js"></script>
        	  <link rel="stylesheet" href="<?php echo $this->webroot; ?>alertify/themes/alertify.core.css" />
        	  <link rel="stylesheet" href="<?php echo $this->webroot; ?>alertify/themes/alertify.default.css" />

        	  <?php
        	if($this->Session->read('message') != '' && $this->Session->read('message_type') == 'success'){
        	?>

        	  <script type="text/javascript">
        	    alertify.success("<?php echo $this->Session->read('message'); ?>");
        	  </script>
        	<?php
        	$_SESSION['message']=null;
        	$_SESSION['message_type']=null;
        	}
        	   ?>
        	   <?php
        	if($this->Session->read('message') != '' && $this->Session->read('message_type') == 'error'){
        	?>

        	   <script type="text/javascript">
        	     alertify.error("<?php echo $this->Session->read('message'); ?>");
        	   </script>
        	<?php
        	$_SESSION['message']=null;
        	$_SESSION['message_type']=null;
        	}
        	    ?>
        </div>

        <script src="<?php echo $this->webroot; ?>theme/js/jquery.min.js"></script>
        <script src="<?php echo $this->webroot; ?>theme/js/matrix.login.js"></script>
    <?php //echo $this->element('sql_dump'); ?>
    </body>

</html>
