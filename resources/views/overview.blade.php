@php
	$selectedIndex = (int)data_get($_POST, 'command-index', 0);
@endphp

<html lang="en">
<title>KingsCode Dev Tools</title>
<style>

body, form, input, option, select, textarea, .console {
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

.console {
  width:      65%;
  box-sizing: border-box;
}

.console .consolebody {
  box-sizing:       border-box;
  padding:          20px;
  overflow:         scroll;
  overflow-x:       hidden;
  background-color: #000;
  color:            #63de00;
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
<script>

function commandSelected() {
  document.getElementById('selector').submit();
}
</script>

<body>
<div class="content">
	<div class="row">

		@include('kc-dev-tools::logout')

		<div class="column-left">

			<h1>Commands:</h1>

			<form id="selector" action="{{ $request_uri }}" method="post">
				<select name="command-index" onchange="commandSelected()">
					@foreach($commands as $index => $command)
						{{ $isSelected = $selectedIndex === $index ? 'selected' : '' }}
						<option {{$isSelected}} value={{ $index }}>{{ $command['name'] }}</option>
					@endforeach
				</select>
				<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
			</form>

			@if(data_get($_POST, 'command-index'))

				<form action="{{ $request_uri }}" method="post">

					@php
						$command = $commands[$selectedIndex]['value'];
					@endphp

					@if($command !== null)
						<div class="description">{{$command->getDescription()}}</div>
						<p>
							@php
								foreach($command->getDefinition()->getArguments() as $index => $argument) {
									echo '<input name="arguments['.$argument->getName().']" placeholder='.$argument->getName().' /><br />';
								}
							@endphp
						</p>

					@endif
					<p>
						<input type="hidden" name="type" value="process-command"/>
						<input type="hidden" name="command_name" value="{{ $command->getName() }}"/>
						<input type="hidden" name="command_index" value="{{ $selectedIndex }}"/>
						<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
						<input type="submit" value="Execute"/>
					</p>
				</form>
			@endif

			@if(! empty($command_message))
				<div class="message"> {!! $command_message !!} </div>
			@endif

			@if(! empty($command_output))
				<div class="console">
					<div class="consolebody">
						<p>{!! $command_output !!}</p>
					</div>
				</div>
			@endif

		</div>

		<div class="column-right">
			<h1>Reset Password:</h1>

			<form action="{{ $request_uri }}" method="post">
				<p>
					<input name="password-reset[email]" placeholder="E-mail" type="text"/>
				</p>
				<p>
					<input name="password-reset[password]" placeholder="New Password" type="password"/>
				</p>
				<p>
					<input type="submit" value="Reset password"/>
					<input type="hidden" name="type" value="password-reset"/>
					<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
				</p>
				@if(! empty($password_reset_message))
					<p>
					<div class="message">
						{{ $password_reset_message }}
					</div>
					</p>
				@endif
			</form>
		</div>
	</div>
</div>
</body>
</html>