@php
	$selectedIndex = (int)data_get($_GET, 'command-index');
@endphp

<html lang="en">
<title>KingsCode Dev Tools</title>
<script>

function commandSelected() {
  document.getElementById('selector').submit();
}
</script>

<body>

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

		<div style="width: 750px;">{{$command->getDescription()}}</div>
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
</body>
</html>