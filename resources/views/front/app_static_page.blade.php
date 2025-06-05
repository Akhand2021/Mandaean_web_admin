<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Mandaean</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
	<link rel="icon" type="image/x-icon" href="{{url('public/assets/images/favicon.png')}}">
</head>
<style>
	body {
    	font-family: 'Montserrat', sans-serif;
	}
	*{
		/*margin: 0;*/
		padding: 0;
		box-sizing: border-box;
	}
	.terms {
	    background-color: #FFFFFF;
	    margin: 0 auto;
	    /*box-shadow: 0px 0px 7px antiquewhite;*/
	}

	.terms .terms-inner-box {
	    /*padding: 35px;*/
        padding: 0 35px 35px 35px;
	}

	.terms .terms-inner-box .content {
	    padding-bottom: 20px;
	    font-size: 14px;
	    color: #000000DE;
	    line-height: 22px;
	}
	.terms .terms-inner-box .content:last-child{
		padding-bottom: 0px;
	}
</style>
<body>
<div class="terms">
	<div class="terms-inner-box">
		 {!!$row['content']!!}
	</div>
</body>
</html>