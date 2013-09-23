
$.fn.dataTableExt.afnSortData['dom-class'] = function  ( oSettings, iColumn )
{
	var aData = [];
	$(oSettings.oApi._fnGetTrNodes(oSettings)).find('td:eq('+iColumn+')').each( function () {
		aData.push( this.bgColor=="#00ff00" ? "1" :this.bgColor=="#ff0000" ? "-1": "0" );
	} );
	return aData;
}



	$(function(){competitionInit();});
		
		var competitionSortedTable;
		function competitionInit(){
		var params={"bAutoWidth":false,"aaSorting": [[ 2, "asc" ]],"sPaginationType":"full_numbers","aoColumns": [{"sType":"html"}, {"sSortDataType":"dom-class"},{"sSortDataType":"dom-class"},{"sSortDataType":"dom-class"}]};
	if(RSS){
		params.aoColumns.push({"sSortDataType": "dom-class" });
		params.aoColumns.push({"sSortDataType": "dom-class" });
	}
	if(PUB){
		params.aoColumns.push({"sSortDataType": "dom-class" });
		params.aoColumns.push({"sSortDataType": "dom-class" });
	}
	 competitionSortedTable=$('#competitionList').dataTable();
	}
	
		function useGroupFor(col){
		if(confirm("Use group setting for "+col+" in all competitions?")){
			$.getJSON(BASE+'/ajax/visibility/allUseG/'+col, '', function(data){
			if(data.success=='False'){					
				alert("Error updating! "+data.error);	
			}else{
				col=data.col;
				if(data.rows ){		
						rows=competitionSortedTable.fnGetNodes();
					$.each(data.rows, function(key, value) { 
						trow=$(rows).filter("#competitionList"+key).children("td:nth-child("+col+")");
						
						trow.html(data.rows[key].text);
						trow.animate({backgroundColor:"'"+data.rows[key].color+"'"}, 500);
						trow.attr("bgcolor",data.rows[key].color);
					});
				}
				
			}
			});		
		}
	}
	
		function updateCompCol(id, col){
		$.getJSON(BASE+'/ajax/visibility/updateCompI/'+id+"?c="+col, '', function(data){
			if(data.success=='False'){					
				alert("Error updating! "+data.error);	
			}else{
				$('#competitionList'+id+' td:nth-child('+col+')').html(data.text);
				$('#competitionList'+id+' td:nth-child('+col+')').animate({backgroundColor:"'"+data.color+"'"}, 500);
				$('#competitionList'+id+' td:nth-child('+col+')').attr("bgcolor",data.color);
			}
			});								
	}	
(function ($) {
    var m = {
            '\b': '\\b',
            '\t': '\\t',
            '\n': '\\n',
            '\f': '\\f',
            '\r': '\\r',
            '"' : '\\"',
            '\\': '\\\\'
        },
        s = {
            'array': function (x) {
                var a = ['['], b, f, i, l = x.length, v;
                for (i = 0; i < l; i += 1) {
                    v = x[i];
                    f = s[typeof v];
                    if (f) {
                        v = f(v);
                        if (typeof v == 'string') {
                            if (b) {
                                a[a.length] = ',';
                            }
                            a[a.length] = v;
                            b = true;
                        }
                    }
                }
                a[a.length] = ']';
                return a.join('');
            },
            'boolean': function (x) {
                return String(x);
            },
            'null': function (x) {
                return "null";
            },
            'number': function (x) {
                return isFinite(x) ? String(x) : 'null';
            },
            'object': function (x) {
                if (x) {
                    if (x instanceof Array) {
                        return s.array(x);
                    }
                    var a = ['{'], b, f, i, v;
                    for (i in x) {
                        v = x[i];
                        f = s[typeof v];
                        if (f) {
                            v = f(v);
                            if (typeof v == 'string') {
                                if (b) {
                                    a[a.length] = ',';
                                }
                                a.push(s.string(i), ':', v);
                                b = true;
                            }
                        }
                    }
                    a[a.length] = '}';
                    return a.join('');
                }
                return 'null';
            },
            'string': function (x) {
                if (/["\\\x00-\x1f]/.test(x)) {
                    x = x.replace(/([\x00-\x1f\\"])/g, function(a, b) {
                        var c = m[b];
                        if (c) {
                            return c;
                        }
                        c = b.charCodeAt();
                        return '\\u00' +
                            Math.floor(c / 16).toString(16) +
                            (c % 16).toString(16);
                    });
                }
                return '"' + x + '"';
            }
        };

	$.toJSON = function(v) {
		var f = isNaN(v) ? s[typeof v] : s['number'];
		if (f) return f(v);
	};
	
	$.parseJSON = function(v, safe) {
		if (safe === undefined) safe = $.parseJSON.safe;
		if (safe && !/^("(\\.|[^"\\\n\r])*?"|[,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t])+?$/.test(v))
			return undefined;
		return eval('('+v+')');
	};
	
	$.parseJSON.safe = false;

})(jQuery);
