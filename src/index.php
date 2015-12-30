<!DOCTYPE html>
<html>
<head>
<title>Facebook Login JavaScript Example</title>
<meta charset="UTF-8">
<script src="jquery-2.1.4.js"></script>
<script src="facebooklogin.js"></script>
<script src="highlight.js"></script>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

<style>
.highlight {
    background-color: #FFFF88;
}
#RawFacebookData {
    max-width:90%;
    margin: auto;
    border: 3px solid;
}
.img-responsive {
    margin: 0 auto;
}
</style>
</head>
<body>
 <!-- Fixed navbar -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">God Watching</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>


 <div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h1>God Watching</h1>
        
        <p>How often do you share God's message on your social media accounts?  This app will download the last 6 months of Facebook data and count how many times you use words related to Christianity. It will then highlight those words in your feed.  See how many words you can rack up as you share God's message with those around you.</p>
      </div>


<img src="cross.png" class="img-responsive" alt="Cinque Terre">




<script>
var wordlist = new Array("Advent", "Amen", "Anointing", "Apostle", "Ascension", "Atonement", "Baptism", "Blood of Christ", "Body of Christ", "Born Again", "Bible", "Christ", "Christian", "Covenant", "Creed", "Crucifixion", "Devotional", "Easter", "Eternal Life", "Eucharist", "Evangelical", "Faith", "God", "Golden Rule", "Gospel", "Grace", "Hell", "Holy Spirit", "Hosanna", "Incarnation", "Jehova", "Jerusalem", "Last Supper", "Laying on of hands", "Lord's Prayer", "Messiah", "New Covenant","New Testament", "Old Testament", "Passover", "Pentecost", "Rapture", "Redemption", "Redeemed", "Sabbath", "Sacrament", "Peter", "Mark", "Mathew", "Luke", "John", "Salvation", "Sermon", "Church", "Sin ", "Son of God", "Son of Man", "Ten Commandments", "Trinity", "Yahweh", "urbana", "IV", "intervarsity");

function sortByCount(a, colIndex){

    a.sort(sortFunction);

    function sortFunction(a, b) {
        if (a.count === b.count) {
            return 0;
        }
        else {
            return (a.count > b.count) ? -1 : 1;
        }
    }

    return a;
}


function countOcurrences(str, value){
   var regExp = new RegExp(value, "gi");
   return (str.match(regExp) || []).length;
}

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  var facebookdata = new Array();
  function facebookInit() {
	  $("#facebookLoginButton").empty();
    console.log('Welcome!  Fetching your information.... ');
    FB.api("/me/feed?since=2015-6-1&metadata=1", getfeed);
	
	function getfeed(response){
		//console.log("Getfeed fired");
		//console.log(response);
		
		if (response.data){
			$.each(response.data, function( k, v ) {
  				if(response.paging){
					//console.log(v);
					facebookdata.push(v);
				}
			});
		}
		if (response.paging){
       		//console.log("paging fired");
			FB.api(response.paging.next, getfeed);	
   		} else {
			//console.log("Facebook Feed Loaded");
			//console.log(facebookdata);
			showRawFacebook();
		}
		
		
	}
	
	
	
  }
var countedFacebook = new Array(); 
 
function showRawFacebook(){
	//console.log("showRawFacebook Fired");
	var totalwords = 0;
	$.each(facebookdata, function(k,v){
		var temparray = new Array();
		temparray = v;
		var message1 = "";
		var message2 = "";
		if(v.story){
			message1 = v.story;
		} else {
			message2 = "No Story";
		}
		if(v.message){
			message2 = v.message;
		} else {
			message2 = "No Message";
		}
		
		
		var combinedMessage = message1 + " " + message2;
		//console.log("The combined message is " + combinedMessage);
		var count = countwords(combinedMessage);
		temparray.count = count;
		countedFacebook.push(temparray);
		totalwords += count;
		//console.log(count);
		$('#RawFacebookList').append("<tr><td>" + count +  "</td><td>" +message1 + "</td><td>" + message2 + "</td><td>" + v.created_time + "</td></tr>");
		
	});
	
	//console.log("total word count is " + totalwords);
	$('#scoreBox').append(totalwords + " Words");
	$("#progressBar").css("width", totalwords + "%");
	$("#progressBar").append(totalwords + " Words");
	//console.log("countedFacebook");
	//console.log(countedFacebook);
	//console.log("countedFacebook Sorted");
	var sortedFacebook = sortByCount(countedFacebook);
	//console.log(sortedFacebook);
	
	var i = 0;
	do {
		$("#topFiveList").append("<li>" + sortedFacebook[i].message + "</li>");
		i++;
	} while (i < 5);
	highlightFacebook();
}

function countwords(string){
	var count = 0;
	$.each(wordlist, function(k,v){
		//console.log("word to seach is " + v);
		count += countOcurrences(string, v);
	});
	
	
	return count;
}

function highlightFacebook (){
	$.each(wordlist , function(k,v){
		$('#RawFacebookList').highlight(v);
		$('#topFiveList').highlight(v);
	});
}
function evaluateLikes(postID){
	var likenumber = 0;
	var likeholder = FB.api("/me/feed", getfeed);
	//console.log("LikeHolder is " + likeholder);
}
function getLikes(response){
	return "testing";
}
</script>

<!--
  Below we include the Login Button social plugin. This button uses
  the JavaScript SDK to present a graphical Login button that triggers
  the FB.login() function when clicked.
-->

<div id="facebookLoginButton">
<fb:login-button scope="public_profile,email,user_about_me,user_photos,user_posts" onlogin="checkLoginState();">
</fb:login-button>
</div>


 <div class="page-header">
        <h1>Your Score: <span id="scoreBox"></span></h1>
 </div>
 

  <div  ><h1 id="wordBox"></h1></div>
  <div class="col-sm-9"><div class="progress">
  	<div id="progressBar" class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
  	</div>
  </div>
 
  <div class="page-header">
        <h1>Your Top 5 Posts</h1>
 </div>
 <div id="topFive">
 	<ul id="topFiveList"></ul>
 </div>
 <div class="page-header">
        <h1>Facebook Feed</h1>
 </div>
<div id="RawFacebookData" width="50%">
	<table id="RawFacebookList" class="table table-striped table-hover table-bordered" >
    	<tr>
        	<td width="10%">Word Count</td>
            <td width="30%">Status</td>
            <td>Message</td>
            <td>Time Stamp</td>
    </table>
</div>

 <div class="page-header">
        <h1>Contact</h1>
 </div>
 
<div class="well well-lg" id="contact">
	<p>Find a bug?  Have a suggestion?  Please <a href="mailto:enterinlast?Subject=GodWatching" target="_top">email the creaters here.</a></p>
</div>

    </div> <!-- /container -->
</body>
</html>