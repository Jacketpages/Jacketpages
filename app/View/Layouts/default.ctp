<?php?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php echo $this -> Html -> charset();?>
		<title><?php echo 'JacketPages'
			?>:<?php echo $title_for_layout;?></title>
		<?php
		// Include any meta, css, and script links here.
      echo $this -> Html -> meta('icon');

      echo $this -> Html -> css('style');

      echo $this -> fetch('meta');
      echo $this -> fetch('css');
      echo $this -> fetch('script');
		?>
	</head>
	<body>
		<div id="container">
			<div id="header">
				<?php
            // Show the Jacketpages logo and link it to the home page.
            echo $this -> Html -> link($this -> Html -> image('jpHeader.jpg'), "/", array('escape' => false));
				?>
				        <div id="utilityBar">
            <div id="utilityBarWrapper" class="ddsmoothmenu">
               </div>
			</div>
			<div id="content">
				<?php echo $this -> Session -> flash();?>

				<?php echo $this -> fetch('content');?>
			</div>
			<div id="footer"></div>
		</div>
		<?php echo $this -> element('sql_dump');?>
	</body>
</html>
