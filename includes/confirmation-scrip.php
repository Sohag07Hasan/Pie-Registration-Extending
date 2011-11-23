<?php
	if(strlen($message)>5) :
?>
<?php
	get_header();
	
?>
<div id="content-area" class="clearfix<?php if($fullwidth) echo(' fullwidth');?>">
	<div id="left-area">
		<div class="entry">
			<?php echo $message; ?>
			<br/>
		
		</div> <!-- end of the entry -->
	</div> <!-- end of the left area -->

	
	
</div> <!-- end #content-area -->	

 
<?php
    
     get_footer();
     exit;
?>

<html>
	<head>
		<style type="text/css">
			form{
	  			width: 100%;
	  			background: none repeat scroll 0 0 #FFFFFF;
			    border: 1px solid #E5E5E5;
			    border-radius: 3px 3px 3px 3px;
			    box-shadow: 0 4px 10px -1px rgba(200, 200, 200, 0.7);
			    font-weight: normal;
			    margin-left: 8px;
			    padding: 26px 24px 46px;
			}
			
			*{
			    margin: 0;
			    padding: 0;
			}
			body{
				color: #333333;
				font-family: sans-serif;
	   			font-size: 17px;
			}
						
		</style>
	</head>
	<body>
		<form>
			<p style="text-align:center;margin-bottom:10px;">
				<?php echo $message; ?>
			</p>
		</form>
	</body>
</html>
<?php
exit;
endif;
