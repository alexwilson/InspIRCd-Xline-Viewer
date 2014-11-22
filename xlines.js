$(document).ready(function() {
	$.extend($.fn.dataTableExt.oSort, {
		"date-euro-pre": function( a ) {
			var x; 
			if ($.trim(a) !== '' && $.trim(a) !== 'Never') {
				var frDatea = $.trim(a).split(' ');
				var frTimea = frDatea[1].split(':');
				var frDatea2 = frDatea[0].split('/');
				x = (frDatea2[2] + frDatea2[1] + frDatea2[0] + frTimea[0] + frTimea[1] + frTimea[2]) * 1;
			} else {
				x = Infinity;
			}

			return x;
		},

		"date-euro-asc": function(a, b) {
			return a - b;
		},

		"date-euro-desc": function(a, b) {
			return b - a;
	    }
	});
	$('#lines').dataTable({
		"ajax": "xlines.php",
		"columns": [
			{"data": "type", "orderable": false },
			{"data": "host"},
			{"data": "start", "type": "date-euro"},
			{"data": "end", "type": "date-euro"},
			{"data": "reason"}
		],
		"order": [[2, "desc"]],
		"saveState": true,
		"initComplete": filterListInit
	});
	function filterListInit() {
		$("#lines thead th[data-filter]").each(function(i, node) {
			var node = $.clone(node),
			    select = $('<select><option value="">'+$(node).text()+'</option></select>')
			.appendTo($(this).empty())
			.on('change', function() {
				var val = $(this).val();
				$('#lines').DataTable().column(i)
				.search(val ? '^'+$(this).val()+'$' : val, true, false)
				.draw();
			});
			//.addClass('form-control');

			$('#lines').DataTable().column(i).data().unique().sort().each(function(d, j) {
				select.append('<option value="'+d+'">'+d+'</option>');
			});
		});

	}
});