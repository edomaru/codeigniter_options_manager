<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Setting | Codeigniter Options Manager</title>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">		
</head>
<body>
	<!-- Static navbar -->
  <nav class="navbar navbar-default">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">CI Options Manager</a>
      </div>
      <div id="navbar" class="navbar-collapse collapse">            
        <ul class="nav navbar-nav navbar-right">
          <li class="active"><a href="./">Default <span class="sr-only">(current)</span></a></li>
          <li><a href="../navbar-static-top/">Static top</a></li>
          <li><a href="../navbar-fixed-top/">Fixed top</a></li>
        </ul>
      </div><!--/.nav-collapse -->
    </div><!--/.container -->
  </nav>

	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<h1 class="page-header">Setting</h1>

				<div class="row">
					<?php echo $this->options->render() ?>
				</div>
				
			</div>
		</div>
	</div>
		
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

</body>
</html>