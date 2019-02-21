<?php
$cakeDescription = __d('cake_dev', 'Uganda Prisons Service');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
            <?php echo $cakeDescription ?>
        </title>    
<?php
    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
    echo $this->Html->meta('icon');
    echo $this->Html->css(array('../theme/css/bootstrap.min.css','../theme/css/bootstrap-responsive.min','../theme/css/matrix-login','../theme/font-awesome/css/font-awesome','http://fonts.googleapis.com/css?family=Open+Sans:400,700,800','../alertify/themes/alertify.core','../alertify/themes/alertify.default.css'));
    echo $this->Html->script(array('../alertify/src/alertify','../theme/js/jquery.min','../theme/js/matrix.login', 'md5'));
?> 
    </head>
    <body>
	  <div class="span10 loginImg"><img src="<?php echo $this->webroot; ?>/theme/img/loginlogo.png" class="loginLogo"></div>
        <div id="loginbox">
            <?php echo $this->fetch('content'); ?>
            

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
    <?php //echo $this->element('sql_dump'); ?>
    </body>
</html>