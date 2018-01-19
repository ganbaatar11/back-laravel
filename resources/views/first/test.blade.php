<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<form action="">
		<select name="contest_id" id="contest_id">
			@foreach($contests as $contest)
			<option value="{{$contest->yCode}}">{{$contest->start_time}}</option>
			@endforeach
		</select>
	</form>
</body>
</html>