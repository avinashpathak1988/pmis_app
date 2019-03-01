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
                    echo $this->Html->meta('icon');

                    echo $this->Html->css(array('../theme/css/bootstrap.min.css', 'jquery-ui','../theme/css/bootstrap-responsive.min','../theme/css/uniform','../theme/css/select2','../theme/css/matrix-style','../theme/css/matrix-media','../theme/font-awesome/css/font-awesome.css','http://fonts.googleapis.com/css?family=Open+Sans:400,700,800','../alertify/themes/alertify.core','../alertify/themes/alertify.default', 'style'));
                //'../theme/css/datepicker',
                    echo $this->Html->script(array('../theme/js/jquery.min', 'jquery-ui','../theme/js/jquery.ui.custom','../theme/js/bootstrap.min','../theme/js/jquery.uniform','../theme/js/select2.min','../theme/js/jquery.dataTables.min','../theme/js/matrix','../theme/js/matrix.tables','../alertify/src/alertify','../theme/js/jquery.validate.min', 'tabbedcontent.min', 'analytics.min'));
                    //'../theme/js/bootstrap-datepicker',
                  echo $this->fetch('script');
                ?> 
    <script type="text/javascript">
            $(document).on('keypress','.alpha',function (event){
                var regex = new RegExp("^[a-zA-z ]+$");
                var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
                if(event.which == 8 || event.keyCode == 9 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40 || event.keyCode == 94){
                    return true;
                }
                if (!regex.test(key)) {
                   event.preventDefault();
                   return false;
                }
            });
        $(document).ready(function(){
            
            $(document).on('keypress','.alphanumeric',function (event){
                var regex = new RegExp("^[a-zA-z0-9 ]+$");
                var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
                if(event.which == 8 || event.keyCode == 9 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40){
                    return true;
                }
                if (!regex.test(key)) {
                   event.preventDefault();
                   return false;
                }
            });            
            $(document).on('keypress','.numeric',function (event){
                var regex = new RegExp("^[0-9.]+$");
                var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
                if(event.which == 8 || event.keyCode == 9 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40 || event.keyCode == 94){
                    return true;
                }
                if(event.which == 46 && $(this).val().indexOf('.') != -1) {
                    event.preventDefault();
                    return false;
                }
                if (!regex.test(key)) {
                   event.preventDefault();
                   return false;
                }
            }); 
            $(document).on('keypress','.mobile',function (event){
                var regex = new RegExp("^[0-9]+$");
                var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
                if(event.which == 8 || event.keyCode == 9 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40){
                    return true;
                }
                if (!regex.test(key)) {
                   event.preventDefault();
                   return false;
                }
            });
            $(document).on('keypress','.phone',function (event){
                var regex = new RegExp("^[0-9-]+$");
                var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
                if(event.which == 8 || event.keyCode == 9 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40){
                    return true;
                }
                if(event.which == 45 && $(this).val().indexOf('-') != -1) {
                    event.preventDefault();
                    return false;
                }
                if (!regex.test(key)) {
                   event.preventDefault();
                   return false;
                }
            });

            $("#from_date").datepicker({
                defaultDate: new Date(),
                changeMonth: true,
                numberOfMonths: 1,
                maxDate:'0',
                onSelect: function( selectedDate ) {
                    $( "#to_date" ).datepicker( "option", "minDate", selectedDate );
                },
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true
            });
            $("#to_date").datepicker({
                defaultDate: new Date(),
                changeMonth: true,
                numberOfMonths: 1,
                onSelect: function( selectedDate ) {
                    $( "#from_date" ).datepicker( "option", "maxDate", selectedDate );
                },
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true
            });  
            $('.my_date').datepicker({
              defaultDate: new Date(),
              changeMonth: true,
              changeYear: true,
              numberOfMonths: 1,
              dateFormat: 'dd-mm-yy',
            });
        });
</script>
 </head>
<body>

<!--Header-part-->
<div id="header">
  <h1>
      <?php echo $this->Html->link(SITE_TITLE,array('controller'=>'sites','action'=>'dashboard'));?>
  </h1>
</div>
<!--close-Header-part--> 


<!--close-top-serch--> 
<?php echo $this->element('topheader'); ?>
<?php echo $this->element('leftmenu'); ?>
<!--sidebar-menu-->

<div id="content">
 <?php echo $this->element('breadcrumbs'); ?>
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
<!--Footer-part-->
<div class="row-fluid">
    <div id="footer" class="span12"> <?php echo date('Y'); ?> &copy; <?php echo SITE_TITLE; ?>. Developed By <a href="http://luminousinfoways.com">Luminous Infoways</a> </div>
</div>

</body>


</html>
<script>

  $(document).ready(function(){

    var action_class = "<?php echo $funcall->request['controller']; ?>";
    var sublink = "<?php echo $funcall->request['action']; ?>";

    $("li[link='"+action_class+"']").closest(".submenu").addClass( "open active" );
    if($("li[link='"+action_class+"']").length > 1) 
      $("li[link='"+action_class+"'][sublink='"+sublink+"']").addClass( "active" );
    else 
      $("li[link='"+action_class+"']").addClass( "active" );
  
  });
</script>