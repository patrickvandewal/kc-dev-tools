<html lang="en">
<head>
	<title>KingsCode Dev Tools (Login)</title>
	<style>

    body, form, input, option, select, textarea {
      font-family: Verdana;
      font-size:   16px;
    }

    input, option {
      width: 200px;
    }

    input[type=submit] {
      width: 200px;
    }

    .content {
      padding-top: 1px;
      margin:      2%;
      text-align:  left;
    }

    .message {
      padding: 10px 0px;
      color:   orange;
    }

    #description {
      width: 500px;
    }

	</style>
</head>
<body>
<div class="content">
	<h1>Enter PIN</h1>

	<form action="{{ $request_uri }}" method="post">
		<p>
			<input name="pin[value]" placeholder="PIN" type="text"/>
		</p>
		<p>
			<input type="submit" value="Enter"/>
			<input type="hidden" name="type" value="pin"/>
			<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
		</p>
		@if(! empty($auth_message))
			<p>
			<div class="message">
				{{ $auth_message }}
			</div>
			</p>
		@endif
	</form>
</div>
</body>
</html>