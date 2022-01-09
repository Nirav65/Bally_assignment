<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bally Interactive | Assignment Submission</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
  <style>
  .fakeimg {
    height: 200px;
    background: #aaa;
  }
  </style>
</head>
<body>

<div class="jumbotron" style="margin-bottom:0">
  <h1>Assignment Submission</h1>
  <div class="col-md-8 my-auto">
    <p>Write a script to parse the attached projections file, and for each event in the file do the following:</p>
    <ul>
      <li>Organize the data according to the heirarchy diagram below</li>
      <li>Score each player with the given scoring system and assign them a "FantasyPoints" projection with the value. 
      Change the display name for the stats to a formatted version of the represented stat</li>
      <li>Sort the players in each team by FantasyPoints descending</li>
    </ul>
	</div>
  <button class="btn btn-info" type="submit" name="submit" id="button-addon2" onclick="getOutput();">Click here to get the output file</button>
</div>

<script type="text/javascript">
  function getOutput(){
      window.location.href = "home.php";
  }
</script>

</body>
</html>
