@php
	// @formatter:off

	/** @var \Symfony\Component\Console\Command\Command $command */

	dd($_GET);

@endphp

<html>

<script>

function commandSelected() {
  index = document.getElementById("commands").value;
  document.getElementById("selector").submit();

}
</script>

<form id="selector" method="get">
	<select id="command" onchange="commandSelected()">
		@foreach($commands as $index => $command)
			<option value={{ $index }}>{{ $command['name'] }}</option>
		@endforeach


	</select>
</form>

</html>