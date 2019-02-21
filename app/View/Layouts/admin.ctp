<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo SITE_TITLE; ?></title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="<?php echo $this->webroot; ?>theme/css/bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo $this->webroot; ?>theme/css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="<?php echo $this->webroot; ?>theme/css/fullcalendar.css" />
<link rel="stylesheet" href="<?php echo $this->webroot; ?>theme/css/matrix-style.css" />
<link rel="stylesheet" href="<?php echo $this->webroot; ?>theme/css/matrix-media.css" />
<link href="<?php echo $this->webroot; ?>theme/font-awesome/css/font-awesome.css" rel="stylesheet" />
<link rel="stylesheet" href="<?php echo $this->webroot; ?>theme/css/jquery.gritter.css" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>



<script src="<?php echo $this->webroot; ?>theme/js/excanvas.min.js"></script> 
<script src="<?php echo $this->webroot; ?>theme/js/jquery.min.js"></script> 
<script src="<?php echo $this->webroot; ?>theme/js/jquery.ui.custom.js"></script> 
<script src="<?php echo $this->webroot; ?>theme/js/bootstrap.min.js"></script> 
<script src="<?php echo $this->webroot; ?>theme/js/jquery.flot.min.js"></script> 
<script src="<?php echo $this->webroot; ?>theme/js/jquery.flot.resize.min.js"></script> 
<script src="<?php echo $this->webroot; ?>theme/js/jquery.peity.min.js"></script> 
<script src="<?php echo $this->webroot; ?>theme/js/fullcalendar.min.js"></script> 
<script src="<?php echo $this->webroot; ?>theme/js/matrix.js"></script> 
<script src="<?php echo $this->webroot; ?>theme/js/matrix.dashboard.js"></script> 
<script src="<?php echo $this->webroot; ?>theme/js/jquery.gritter.min.js"></script> 
<script src="<?php echo $this->webroot; ?>theme/js/matrix.interface.js"></script> 
<script src="<?php echo $this->webroot; ?>theme/js/matrix.chat.js"></script> 
<script src="<?php echo $this->webroot; ?>theme/js/jquery.validate.js"></script> 
<script src="<?php echo $this->webroot; ?>theme/js/matrix.form_validation.js"></script> 
<script src="<?php echo $this->webroot; ?>theme/js/jquery.wizard.js"></script> 
<script src="<?php echo $this->webroot; ?>theme/js/jquery.uniform.js"></script> 
<script src="<?php echo $this->webroot; ?>theme/js/select2.min.js"></script> 
<script src="<?php echo $this->webroot; ?>theme/js/matrix.popover.js"></script> 
<script src="<?php echo $this->webroot; ?>theme/js/jquery.dataTables.min.js"></script> 
<script src="<?php echo $this->webroot; ?>theme/js/matrix.tables.js"></script> 
</head>
<body>

<!--Header-part-->
<div id="header">
  <h1> <?php 
      echo $this->Html->link(SITE_TITLE,array(
          'controller'=>'sites',
          'action'=>'dashboard'
      ));
      ?></h1>
</div>
<!--close-Header-part--> 


<?php echo $this->element('topheader'); ?>
<?php echo $this->element('leftmenu'); ?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
 <?php echo $this->element('breadcrumbs'); ?>
</div>
<!--End-breadcrumbs-->
 
    <hr/>
    <div class="row-fluid">
     <?php echo $this->fetch('content'); ?>
    </div>
 

<!--end-main-container-part-->

<!--Footer-part-->

<div class="row-fluid">
    <div id="footer" class="span12"> <?php echo date('Y');?> &copy; <?php echo SITE_TITLE; ?>. 
        Developed by <a href="http://luminousinfoways.com">Luminous Infoways</a> </div>
</div>

<!--end-Footer-part-->



<script type="text/javascript">
  // This function is called from the pop-up menus to transfer to
  // a different page. Ignore if the value returned is a null string:
  function goPage (newURL) {

      // if url is empty, skip the menu dividers and reset the menu selection to default
      if (newURL != "") {
      
          // if url is "-", it is this page -- reset the menu:
          if (newURL == "-" ) {
              resetMenu();            
          } 
          // else, send page to designated URL            
          else {  
            document.location.href = newURL;
          }
      }
  }

// resets the menu selection upon entry to this page:
function resetMenu() {
   document.gomenu.selector.selectedIndex = 2;
}
</script>
</body>
</html>
