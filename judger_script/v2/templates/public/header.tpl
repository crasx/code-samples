<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<head>
<link rel="stylesheet" href="/loads/css.css" />
<title>Competition Judge{{$title}}</title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="shortcut icon" type="image/x-icon" href="{{$smarty.const.BASE}}/img/favicon.ico" />
<link rel="apple-touch-icon" href="{{$smarty.const.BASE}}/img/iphone.png"/>
<link rel="icon" type="image/x-icon" href="{{$smarty.const.BASE}}/img/favicon.ico" />
<link rel="stylesheet" href="{{$smarty.const.BASE}}/css/css.php" />
<script type="text/javascript">
BASE='{{$smarty.const.BASE}}';
PUB={{$smarty.const.PUB}};
{{if "JSHEADER"|defined}}
  {{$smarty.const.JSHEADER}}
{{/if}}
</script>
<link rel="stylesheet" href="/css/smoothness/jquery-ui-1.8.4.custom.css" />
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/jquery.color.js"></script>
<script type="text/javascript" src="/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/mc.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.8.4.custom.min.js"></script>
<link rel="stylesheet" href="/css/colorbox.css" />
<script type="text/javascript" src="/js/jquery.colorbox-min.js"></script>

</head>
<body>

<table style="width:100%" class="main"><tr>
<td>&nbsp;</td><td>
<h1>Competition Judge - {{$competitionTitle}} public portal</h1>
</td></tr><tr>
<td style="vertical-align:top" width="211px">
{{include file="public/menu/create.tpl"}}
</td>
<td style="{{if $smarty.const.NOCENTER neq 1}}text-align:center;{{/if}} vertical-align:top;">
