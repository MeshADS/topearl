<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<img src="{{ URL::to($basicdata['logo']) }}" alt="{{ $basicdata['shortname'] }}" style="width:128px;">
		<h2>{{ $subject }}</h2>
		<p>
			{{ $body }}
		</p>
		<div style="position:relative; 
					float:left; 
					width:100%; 
					padding: 15px 15px; 
					background-color:#eee;
					color: #666;
					border-top:solid 1px #ccc; 
					font-size:12px;">
			<p><strong>{{ $basicdata['fullname'] }}</strong></p>
			<p><small>{{ Config::get("settings.contact_info.phone") }}</small></p>
			<p><small>{{ Config::get("settings.contact_info.address") }}</small></p>
		</div>
	</body>
</html>
