<style>
.logout {
  position: absolute;
  top: 20px;
  right: 20px;
}
</style>

<div class="logout">
	<form action="{{ $request_uri }}" method="post">
		<input type="submit" value="Logout"/>
		<input type="hidden" name="type" value="logout"/>
		<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
	</form>
</div>
