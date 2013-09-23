<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<head>
<title>Competition Judge{{$title}}</title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link type="text/css" rel="stylesheet" media="all" href="http://competitionjudge.com/modules/system/defaults.css?g" />
<link type="text/css" rel="stylesheet" media="all" href="http://competitionjudge.com/modules/system/system.css?g" />
<link type="text/css" rel="stylesheet" media="all" href="http://competitionjudge.com/modules/system/system-menus.css?g" />
<link type="text/css" rel="stylesheet" media="all" href="http://competitionjudge.com/themes/pixture_reloaded/layout.css?g" />
<link type="text/css" rel="stylesheet" media="all" href="http://competitionjudge.com/{{$smarty.const.PIXTURECSS}}" />
<link type="text/css" rel="stylesheet" media="all" href="http://competitionjudge.com/themes/pixture_reloaded/sf/css/superfish.css?g" />


<link rel="shortcut icon" type="image/x-icon" href="{{$smarty.const.BASE}}/img/favicon.ico" />
<link rel="apple-touch-icon" href="{{$smarty.const.BASE}}/img/iphone.png"/>
<link rel="icon" type="image/x-icon" href="{{$smarty.const.BASE}}/img/favicon.ico" />

<script type="text/javascript">
BASE='{{$smarty.const.BASE}}';
PUB={{if $smarty.const.PUB}}true{{else}}false{{/if}};
RSS={{if $smarty.const.RSS}}true{{else}}false{{/if}};
{{if "JSHEADER"|defined}}
  {{$smarty.const.JSHEADER}}
{{/if}}
</script>

{{if $smarty.const.LOADADMINJS eq 1}}
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/jquery.color.js"></script>
<script type="text/javascript" src="/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/admin.js"></script>
{{elseif $smarty.const.LOADADMINUJS eq 1}}
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/admin.user.js"></script>
{{elseif $smarty.const.LOADADMINCRJS eq 1}}
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/admin.criteria.js"></script>
{{elseif $smarty.const.LOADADMINCUJS eq 1}}
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/admin.custom.js"></script>
{{elseif $smarty.const.LOADADMINCOJS eq 1}}
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/admin.competitions.js"></script>
{{elseif $smarty.const.LOADADMINGJS eq 1}}
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/admin.group.js"></script>
{{elseif $smarty.const.LOADADMINVJS eq 1}}
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/admin.visibility.js"></script>
{{elseif $smarty.const.LOADREGJS eq 1}}
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/register.js"></script>
<script type="text/javascript" src="/js/ajaxFileUpload.js"></script>
{{elseif $smarty.const.LOADMCJS eq 1}}
<link rel="stylesheet" href="/css/smoothness/jquery-ui-1.8.4.custom.css" />
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/jquery.color.js"></script>
<script type="text/javascript" src="/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/mc.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.8.4.custom.min.js"></script>
<link rel="stylesheet" href="/css/colorbox.css" />
<script type="text/javascript" src="/js/jquery.colorbox-min.js"></script>
{{elseif $smarty.const.LOADJJS eq 1}}
<link rel="stylesheet" href="/css/smoothness/jquery-ui-1.8.4.custom.css" />
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/jquery.color.js"></script>
<script type="text/javascript" src="/js/judge.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.8.4.custom.min.js"></script>

{{/if}}

<script type="text/javascript" src="http://competitionjudge.com/themes/pixture_reloaded/sf/js/superfish.js?g"></script>
<link rel="stylesheet" href="{{$smarty.const.BASE}}/css/css.php" />

</head>
<body id="pixture-reloaded" class="front logged-in page-node one-sidebar sidebar-right no-logo">


    <div id="page" style="width: 85%;">

      <div id="header">

        
        <div id="head-elements">

          
          <div id="branding">
                           
                <h1 id="site-name"><a href="/" title="Home page" rel="home">{{$competitionTitle}}</a></h1>
                                      
                          <div id="site-slogan"><em>Competition Judge</em></div>
                      </div> <!-- /#branding -->

        </div> <!-- /#head-elements -->

	  <div id="superfish"><div id="superfish-inner">
  {{include file="menu/create.tpl"}}
	
             </div> <!-- /inner -->

          </div> <!-- /primary || superfish -->
        
    </div> <!--/#header -->


    
    <div id="main" class="clear-block no-header-blocks">

      <div id="content"><div id="content-inner">

        
        
        <div id="content-header" class="clearfix">
                    <a name="main-content" id="main-content"></a>

                                                </div> <!-- /#content-header -->

        <div id="content-area">
          <div id="node-1" class="node node-mine node-teaser node-type-page">
  <div class="node-inner-0"><div class="node-inner-1">
    <div class="node-inner-2"><div class="node-inner-3" style="overflow:auto;" >


{{if !$user->isValid() }}
	{{include file="login.tpl"}}
{{/if}}
