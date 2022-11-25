<html lang="en">
<head>
	<title>KingsCode Dev Tools (Joij)</title>
	<style>

    body, form, input, option, select, textarea {
      font-family: Verdana;
      font-size:   16px;
    }

    input, option, select, textarea {
      width: 400px;
    }

    input[type=submit] {
      width: 200px;
    }

    textarea {
      height: 300px;
    }

    .column-left {
      float: left;
      width: 65%;
    }

    .column-right {
      float: right;
      width: 35%;
    }

    .row:after {
      display: table;
      clear:   both;
    }

    .message {
      padding: 10px 0px;
      color:   orange;
    }

    #description {
      width: 500px;
    }

    .content {
      padding-top: 1px;
      margin:      2%;
      text-align:  left;
    }

	</style>
</head>
<body>
<div class="content">

	@include('kc-dev-tools::logout')

	<div class="column-left">
		<h1>Change Environment:</h1>

		<form action="/dev-tools/joij/overview" method="post">
			<p>
				<input name="change-environment[slug]" placeholder="Slug" type="text"/>
			</p>
			<p>
				<input type="submit" value="Change Environment"/>
				<input type="hidden" name="type" value="change-environment"/>
				<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
			</p>
			@if(! empty($change_environment_message))
				<p>
				<div class="message">
					{{ $change_environment_message }}
				</div>
				</p>
			@endif
		</form>
	</div>
	<div class="column-right">
		<div>
			<h1>Send Push Notification:</h1>

			<form id="selector" action="/dev-tools/joij/overview" method="post">
				<select name="notification[candidate]">
					@foreach($notification_candidates as $index => $candidate)
						<option value="{{ data_get($candidate['value'], 'id') }}">{{ $candidate['name'] }} </option>
					@endforeach
				</select>
				<p>
					<input name="notification[title]" placeholder="Title" type="text"/>
				</p>
				<p>
					<input name="notification[body]" placeholder="Body" type="text"/>
				</p>
				<p>
					<textarea name="notification[data]" placeholder="Data"></textarea/>
				</p>
				<p>
					<input type="submit" value="Send"/>
					<input type="hidden" name="type" value="notification"/>
					<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
				</p>
				@if(! empty($notification_message))
					<p>
					<div class="message">
						{{ $notification_message }}
					</div>
					</p>
				@endif
			</form>
		</div>
	</div>
</div>
</body>
</html>