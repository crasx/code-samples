
table.main{
background-color:transparent;
}

table.stdForm{
margin-left: auto;
margin-right: auto;
text-align:right;
}

div.stdList{
margin-left: auto;
margin-right: auto;
}
.ui-widget-content{
	background:none;
}


.sidebar-right #content-inner {
	padding-right:0px;
}

table.tdtop td{
vertical-align:top;
}

a img{
	border:none;
}
 
.dataTables_wrapper {
	position: relative;
	/*min-height: 302px;*/
	clear: both;
	_height: 302px;
	zoom: 1; /* Feeling sorry for IE */
}

.dataTables_processing {
	position: absolute;
	top: 50%;
	left: 50%;
	width: 250px;
	height: 30px;
	margin-left: -125px;
	margin-top: -15px;
	padding: 14px 0 2px 0;
	border: 1px solid #ddd;
	text-align: center;
	color: #999;
	font-size: 14px;
	background-color: white;
}

.dataTables_length {
	width: 40%;
	float: left;
}

.dataTables_filter {
	/*width: 50%;*/
	float: right;
	text-align: right;
}

.dataTables_info {
	width: 60%;
	float: left;
}

.dataTables_paginate {
	width: 44px;
	* width: 50px;
	float: right;
	text-align: right;
    color:#000;
}

/* Pagination nested */
.paginate_disabled_previous, .paginate_enabled_previous, .paginate_disabled_next, .paginate_enabled_next {
	height: 19px;
	width: 19px;
	margin-left: 3px;
	float: left;
}

.paginate_disabled_previous {
	background-image: url('/images/back_disabled.jpg');
}

.paginate_enabled_previous {
	background-image: url('/images/back_enabled.jpg');
}

.paginate_disabled_next {
	background-image: url('/images/forward_disabled.jpg');
}

.paginate_enabled_next {
	background-image: url('/images/forward_enabled.jpg');
}



/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * DataTables display
 */
table.sorted {
	margin: 0 auto;
	width: 100%;
	clear: both;
    background-color:#fff;
    color:#000;
}

table.sorted table, table.sorted tr, table.sorted td, table.sorted thead, table.sorted tbody,
table.report table, table.report tr, table.report td, table.report thead, table.report tbody{
    border:1px  solid;
}
table.report th, table.report tbody th{
	border:1px solid #000;
    text-align:center;
    background: rgb(87, 188, 244);
    }

table.sorted thead th {
	padding: 3px 18px 3px 10px;
	border-bottom: 1px solid black;
	font-weight: bold;
	cursor: pointer;
    text-align:center;
	* cursor: hand;
}

table.sorted tfoot th {
	padding: 3px 10px;
	border-top: 1px solid black;
	font-weight: bold;
}

table.sorted tr.heading2 td {
	border-bottom: 1px solid #aaa;
}

table.sorted td {
	padding: 3px 10px;
}

table.sorted td.center {
	text-align: center;
}



/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * DataTables sorting
 */

.sorting_asc {
	background: url('/images/sort_asc.png') no-repeat center right;
    padding-right:20px;
}

.sorting_desc {
	background: url('/images/sort_desc.png') no-repeat center right;
    padding-right:20px;
}

.sorting {
	background: url('/images/sort_both.png') no-repeat center right;    
    padding-right:20px;
}

.sorting_asc_disabled {
	background: url('/images/sort_asc_disabled.png') no-repeat center right;
    padding-right:20px;
}

.sorting_desc_disabled {
	background: url('/images/sort_desc_disabled.png') no-repeat center right;
    padding-right:20px;
}





/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * DataTables row classes
 */
tr.odd {
	background-color: #E2E4FF;
}

tr.even {
}





/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Misc
 */
.top, .bottom {
	padding: 15px;
	background-color: #F5F5F5;
	border: 1px solid #CCCCCC;
}

.top .dataTables_info {
	float: none;
}

.clear {
	clear: both;
}

.dataTables_empty {
	text-align: center;
}

tfoot input {
	margin: 0.5em 0;
	width: 100%;
	color: #444;
}

tfoot input.search_init {
	color: #999;
}

td.group {
	background-color: #d1cfd0;
	border-bottom: 2px solid #A19B9E;
	border-top: 2px solid #A19B9E;
}

td.details {
	background-color: #d1cfd0;
	border: 2px solid #A19B9E;
}


.example_alt_pagination div.dataTables_info {
	width: 40%;
}

.paging_full_numbers {
	width: 400px;
	height: 22px;
	line-height: 22px;
}

.paging_full_numbers span.paginate_button,
 	.paging_full_numbers span.paginate_active {
	border: 1px solid #aaa;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	padding: 2px 5px;
	margin: 0 3px;
	cursor: pointer;
	*cursor: hand;
}

.paging_full_numbers span.paginate_button {
	background-color: #ddd;
}

.paging_full_numbers span.paginate_button:hover {
	background-color: #ccc;
}

.paging_full_numbers span.paginate_active {
	background-color: #99B3FF;
}

table.sorted tr.even.row_selected td {
	background-color: #B0BED9;
}

table.sorted tr.odd.row_selected td {
	background-color: #9FAFD1;
}


/*
 * Sorting classes for columns
 */
/* For the standard odd/even */
tr.odd td.sorting_1 {
}

tr.odd td.sorting_2 {
}

tr.odd td.sorting_3 {
}

tr.even td.sorting_1 {
}

tr.even td.sorting_2 {
}

tr.even td.sorting_3 {
}


/*
 * Row highlighting example
 */
.ex_highlight #example tbody tr.even:hover, #example tbody tr.even td.highlighted {
	background-color: #ECFFB3;
}

.ex_highlight #example tbody tr.odd:hover, #example tbody tr.odd td.highlighted {
	background-color: #E6FF99;
}


/*
 * KeyTable
 */
table.KeyTable td {
	border: 3px solid transparent;
}

table.KeyTable td.focus {
	border: 3px solid #3366FF;
}


div.box {
	height: 100px;
	padding: 10px;
	overflow: auto;
	border: 1px solid #8080FF;
	background-color: #E5E5FF;
}

input.redborder, textarea.redborder { border: 3px solid red; }

table.report{
margin-left:auto; margin-right:auto;
background-color:white;
color:#000;
}
table.report th{
font-weight:bold;
}

tbody{
border-top:none;
}

/*td{padding:2px;}
*/
#middle-wrapper input{margin-top:0px;}

table{width:auto;}
