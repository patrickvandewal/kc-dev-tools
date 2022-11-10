@php
	$selectedIndex = (int)data_get($_GET, 'command-index');
@endphp

<html lang="en">
<title>KingsCode Dev Tools</title>
<style>

body, form, input, option, select, .console {
  font-family: Verdana;
  font-size: 16px;
}

.console {
  width: 75%;
  box-sizing: border-box;
}

.console .consolebody {
  box-sizing: border-box;
  padding: 20px;
  overflow: scroll;
  overflow-x: hidden;
  background-color: #000;
  color: #63de00;
}

.column-left {
  float: left;
  width: 75%;
}

.column-right {
  float: right;
  width: 25%;
}

.row:after {
  display: table;
  clear:   both;
}

.message {
  color: orange;
}

#description {
  width: 500px;
}

.content {
  padding: 1%;
}

</style>
<script>

function commandSelected() {
  document.getElementById('selector').submit();
}
</script>

<body>
<div class="content">
	<div class="row">
		<div class="column-left">

			<h1>Commands:</h1>

			<form id="selector" action="" method="get">
				<select name="command-index" onchange="commandSelected()">
					@foreach($commands as $index => $command)
						{{ $isSelected = $selectedIndex === $index ? 'selected' : '' }}
						<option {{$isSelected}} value={{ $index }}>{{ $command['name'] }}</option>
					@endforeach
				</select>
			</form>

			@if(data_get($_GET, 'command-index'))

				<form action="/dev-tools/process" method="post">

					@php
						$command = $commands[$selectedIndex]['value'];
					@endphp

					<div class="description">{{$command->getDescription()}}</div>
					<p>
						@php
							foreach($command->getDefinition()->getArguments() as $index => $argument) {
								echo '<input name="arguments['.$argument->getName().']" placeholder='.$argument->getName().' /><br />';
							}
						@endphp
					</p>
					<p>
						<input type="hidden" name="command-name" value="{{ $command->getName() }}">
						<input type="hidden" name="command-index" value="{{ $selectedIndex }}">
						<input type="submit" value="Execute"/>
					</p>
				</form>
			@endif

			@if(data_get($_GET, 'command.message') !== null)
				Message:
				<div class="message"> {{ data_get($_GET, 'command.message') }} </div>
			@endif

			@if(data_get($_GET, 'command.output'))
				<div class="console">
					<div class="consolebody">
						<p>{!! data_get($_GET, 'command.output') !!}</p>
					</div>
				</div>
			@endif

		</div>
		<div class="column-right">

			<h1>Reset Password:</h1>

			<form action="/dev-tools/updatePassword" method="post">
				<p>
					<input name="password-reset[email]" placeholder="E-mail" type="text">
				</p>
				<p>
					<input name="password-reset[password]" placeholder="New Password" type="password">
				</p>
				<p>
					<input type="submit" value="Reset password"/>
				</p>
				@if(data_get($_GET, 'password-reset.message') !== null)
					<p>
					<div class="message">
						{{ data_get($_GET, 'password-reset.message') }}
					</div>
					</p>
				@endif
			</form>
		</div>
	</div>
</div>
</body>
</html>