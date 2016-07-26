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
					$('.j-word-table').append('<tr><td>' + data['list'][key]['en'] + '</td><td data-ru=" ' + data['list'][key]['ru'] + '"><a class="show-ru" onclick="return showRu(this);" href="#">-- показать --</a></td><td><div class="btn-group"><a class="btn dropdown-toggle" data-toggle="dropdown" href="#">Действия<span class="caret"></span></a><ul class="dropdown-menu"><li><a class="btn btn-success j-to-learning" data-dict-id="' + data['list'][key]['id'] + '" onclick="return toLearnings(this);">Убрать в изученные</a></li><li><a class="btn btn-warning j-to-repeat" data-dict-id="' + data['list'][key]['id'] + '" onclick="return toRepeat(this);">Хочу повторять чаще</a></li><li><a class="btn btn-info">Предложить другой перевод</a></li></ul></td></tr>');
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
				$('#myModal').modal();
			}
		}
	});
	return false;
}