$(function(){
	$('.show-ru').on('click', function(){
		$(this).parent().html($(this).parent().attr('data-ru'));
		return false;
	});

	$('.j-load-more').on('click', {nextid: loadid, _token: csrf}, function () {
		/*
		$.post('/load', function(data) {
			alert('aaa response');
		});
		*/
		$.ajax({
			url: '/load',
			type: 'post',
			data: {},
			headers: {
				'X-CSRF-TOKEN': csrf
			},
			dataType: 'json',
			success: function (data) {
				for(key in data.list) {
					$('.j-word-table').append('<tr><td>' + data['list'][key]['en'] + '</td><td data-ru=" ' + data['list'][key]['ru'] + '"><a class="show-ru" onclick="return showRu(this);" href="#">-- показать --</a></td><td><div class="btn-group"><a class="btn dropdown-toggle" data-toggle="dropdown" href="#">Действия<span class="caret"></span></a><ul class="dropdown-menu"><li><a class="btn btn-success">Убрать в изученные</a></li><li><a class="btn btn-warning">Хочу повторять чаще</a></li><li><a class="btn btn-info">Предложить другой перевод</a></li></ul></td></tr>');
				}
			}
		});

		return false;
	});
});

function showRu (obj) {
	$(obj).parent().html($(obj).parent().attr('data-ru'));
	return false;
}