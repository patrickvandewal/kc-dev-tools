<html lang="en">
<title>KingsCode Dev Tools (Joij)</title>
<style>

body, form, input {
  font-family: Verdana;
  font-size: 16px;
}

.column-left {
  float: left;
  width: 75%;
}

.column-right {
  float: right;
  width: 25%;
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
						<input name="change-environment[slug]" placeholder="Slug" type="text" />
					</p>
					<p>
						<input type="submit" value="Change Environment" />
						<input type="hidden" name="type" value="change-environment" />
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
		</div>
	</div>
</div>
</body>
</html>