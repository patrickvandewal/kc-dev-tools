<html lang="en">
<title>KingsCode Dev Tools (Joij)</title>
<style>

body, form, input, option, select, textarea {
  font-family: Verdana;
  font-size: 16px;
}

input, option, select, textarea
{
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
  color: orange;
}

#description {
  width: 500px;
}

.content {
  padding: 1%;
}

</style>

<body>
<div class="content">
	<div class="row">
		<div class="column-left">
			<div>
				<h1>Change Environment:</h1>

				<form action="/dev-tools/joij/overview" method="post">
					<p>
						<input name="change-environment[slug]" placeholder="Slug" type="text"/>
					</p>
					<p>
						<input type="submit" value="Change Environment"/>
						<input type="hidden" name="type" value="change-environment"/>
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
</div>
</div>
</body>
</html>