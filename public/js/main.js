$(function(){
	$('.show-ru').on('click', function(){
		$(this).parent().html($(this).parent().attr('data-ru'));
		return false;
	});

	$('.j-load-more').on('click', {nextid: loadid, _token: csrf}, function () {
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
					var learningcode = '<li><a class="btn j-to-learning" data-dict-id="' + data['list'][key]['id'] + '" onclick="return toLearnings(this);"><i class="icon-check"></i> Убрать в изученные</a></li>';
					if (data['list'][key]['repeatId']) {
						var repeatcode = '';
						var wordcode = '<td ><b>' + data['list'][key]['en'] + '</b></td>';
					} else {
						var repeatcode = '<li><a class="btn j-to-repeat" data-dict-id="' + data['list'][key]['id'] + '" onclick="return toRepeat(this);"><i class="icon-repeat"></i> Хочу повторять чаще</a></li>';
						var wordcode = '<td>' + data['list'][key]['en'] + '</td>';
					}
					var editcode = '<li><a class="btn"><i class="icon-pencil"></i> Предложить другой перевод</a></li>';

					$('.j-word-table').append('<tr>' + wordcode + '<td data-ru=" ' + data['list'][key]['ru'] + '"><a class="show-ru" onclick="return showRu(this);" href="#">-- показать --</a></td><td><div class="btn-group"><a class="btn dropdown-toggle" data-toggle="dropdown" href="#">Действия<span class="caret"></span></a><ul class="dropdown-menu">' + learningcode + repeatcode + editcode + '</ul></td></tr>');
				}
			}
		});

		return false;
	});

	$('.j-to-learning').on('click', function(){
		return toLearnings(this);
	});

	$('.j-to-repeat').on('click', function(){
		return toRepeat(this);
	});

	$('.j-to-dict').on('click', function(){
		var self = this;

		$.ajax({
			url: '/todict',
			type: 'post',
			data: {
				'dictid': $(this).attr('data-dict-id')
			},
			headers: {
				'X-CSRF-TOKEN': csrf
			},
			dataType: 'json',
			success: function (data) {
				$(self).parents('tr').remove();
			}
		});
		return false;
	});

	$('.j-modal-close').on('click', function(){
		$('#' + $(this).parents('div.modal').attr('id')).modal('hide');
		return false;
	});

	$('.j-change-first').on('click', function(){
		$.ajax({
			url: '/changefirst',
			type: 'post',
			data: {
				'first': $(this).attr('data-to-change')
			},
			headers: {
				'X-CSRF-TOKEN': csrf
			},
			dataType: 'json',
			success: function (data) {
				location.reload();
			}
		});
	});

	$('.j-add-word').on('click', function(){
		$('#modalAddWord').modal();
	});

	$('.j-add-word-submit').on('click', function(){
		$('#modalAddWord').modal('hide');
		$('#myModal .j-my-modal-header').html('Трудимся над этим...');
		$('#myModal .j-my-modal-body').html('Функционал в разработке, скоро будет.');
		$('#myModal').modal();
	});
});

function showRu (obj) {
	$(obj).parent().html($(obj).parent().attr('data-ru'));
	return false;
}

function toLearnings(obj) {
	$.ajax({
		url: '/tolearning',
		type: 'post',
		data: {
			'dictid': $(obj).attr('data-dict-id')
		},
		headers: {
			'X-CSRF-TOKEN': csrf
		},
		dataType: 'json',
		success: function (data) {
			$(obj).parents('tr').remove();
		}
	});
	return false;
}

function toRepeat(obj) {
	$.ajax({
		url: '/torepeat',
		type: 'post',
		data: {
			'dictid': $(obj).attr('data-dict-id')
		},
		headers: {
			'X-CSRF-TOKEN': csrf
		},
		dataType: 'json',
		success: function (data) {
			if (isLearning) {
				$(obj).parents('tr').remove();
			} else {
				$('#myModal .j-my-modal-header').html('Успешно');
				$('#myModal .j-my-modal-body').html('Слово добавлено в список "Повторять чаще"');
				$('#myModal').modal();
			}
		}
	});
	return false;
}