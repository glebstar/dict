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
						var wordcode = '<td data-en="' +  data['list'][key]['en'] + '" class="j-word-en"><i class="icon-star-empty"></i> ' + data['list'][key]['en'] + '</td>';
					} else {
						var repeatcode = '<li><a class="btn j-to-repeat" data-dict-id="' + data['list'][key]['id'] + '" onclick="return toRepeat(this);"><i class="icon-repeat"></i> Хочу повторять чаще</a></li>';
						var wordcode = '<td data-en="' +  data['list'][key]['en'] + '" class="j-word-en">' + data['list'][key]['en'] + '</td>';
					}

					var transcode = '<td class="j-word-ru" data-ru="' + data['list'][key]['ru'] + '"><a class="show-ru" onclick="return showRu(this);" href="#">-- показать --</a></td>';

					var description = '<td class="j-word-description" data-description="' + data['list'][key]['description'] + '"><i class="icon-zoom-in j-show-description" style="cursor: pointer" data-description="' + nl2br(data['list'][key]['description']) + '" data-word="' + data['list'][key]['en'] + ' - ' + data['list'][key]['ru'] + '" onclick="showDescription(this);"></i></td>';

					var editcode = '';
					if (isEditor) {
						editcode = '<td><i style="cursor: pointer" class="icon-pencil j-edit-word" onclick="return editWordShow(this);"></i></td>';
					}

					$('.j-word-table').append('<tr data-word-id="' + data['list'][key]['id'] + '">' + wordcode + transcode + description + '<td><div class="btn-group"><a class="btn dropdown-toggle" data-toggle="dropdown" href="#">Действия<span class="caret"></span></a><ul class="dropdown-menu">' + learningcode + repeatcode + '</ul></td>' + editcode + '</tr>');
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
		$('#modalAddWord h3').html('Добавить новое слово');
		$('#modalAddWord p.j-add-word-info').html('Слово будет сразу добавлено в "Нужно повторять"');
		$('#j-add-word-input-en').val('');
		$('#j-add-word-input-ru').val('');
		$('#j-add-word-input-desc').val('');
		$('#modalAddWord div.error').addClass('hidden').html('');
		$('#modalAddWord .j-add-word-submit').show();
		$('#modalAddWord .j-edit-word-submit').hide();
		$('#modalAddWord').modal();
	});

	$('.j-add-word-submit').on('click', function(){
		$.ajax({
			url: '/addword',
			type: 'post',
			data: {
				'en': $('#j-add-word-input-en').val(),
				'ru': $('#j-add-word-input-ru').val(),
				'description': $('#j-add-word-input-desc').val(),
			},
			headers: {
				'X-CSRF-TOKEN': csrf
			},
			dataType: 'json',
			success: function (data) {
				if (data.result == 'ok') {
					$('#modalAddWord').modal('hide');
					$('#myModal .j-my-modal-header').html('Успешно!');
					$('#myModal .j-my-modal-body').html('<p>Новое слово добавлено в словарь!</p>');
					$('#myModal').modal();
				} else {
					$('#modalAddWord div.error').removeClass('hidden');
					for(key in data.errors) {
						$('#modalAddWord div.error').append('<p class="text-error">' + data.errors[key] + '</p>');
					}
				}
			}
		});

		return false;
	});

	$('.j-show-description').on('click', function(){
		return showDescription(this);
	});

	$('.j-edit-word').on('click', function(){
		return editWordShow(this);
	});

	$('.j-edit-word-submit').on('click', function(){
		$.ajax({
			url: '/editword',
			type: 'post',
			data: {
				'id': $('#j-edit-word-id').html(),
				'en': $('#j-add-word-input-en').val(),
				'ru': $('#j-add-word-input-ru').val(),
				'description': $('#j-add-word-input-desc').val(),
			},
			headers: {
				'X-CSRF-TOKEN': csrf
			},
			dataType: 'json',
			success: function (data) {
				if (data.result == 'ok') {
					$('#modalAddWord').modal('hide');
				} else {
					$('#modalAddWord div.error').removeClass('hidden');
					for(key in data.errors) {
						$('#modalAddWord div.error').append('<p class="text-error">' + data.errors[key] + '</p>');
					}
				}
			}
		});
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
				$(obj).closest('tr').children('.j-word-en').first().html('<i class="icon-star-empty"></i> ' + $(obj).closest('tr').children('.j-word-en').first().html());
			}
		}
	});
	return false;
}

function showDescription (obj) {
	$('#myModal .j-my-modal-header').html('Дополнительная информация');
	$('#myModal .j-my-modal-body').html('<p class="text-info">' + $(obj).attr('data-word') +'</p>'  + $(obj).attr('data-description'));
	$('#myModal').modal();
	return false;
}

function editWordShow (obj) {
	if('en' != firstlang) {
		alert('Переключи словари, уважаемый!');
		return false;
	}

	$('#modalAddWord h3').html('Редактировать слово');
	$('#modalAddWord p.j-add-word-info').html('');
	$('#j-edit-word-id').html('').html($(obj).closest('tr').first().attr('data-word-id'));
	$('#j-add-word-input-en').val($(obj).closest('tr').children('.j-word-en').first().attr('data-en'));
	$('#j-add-word-input-ru').val($(obj).closest('tr').children('.j-word-ru').first().attr('data-ru'));
	$('#j-add-word-input-desc').val($(obj).closest('tr').children('.j-word-description').first().attr('data-description'));
	$('#modalAddWord div.error').addClass('hidden').html('');
	$('#modalAddWord .j-add-word-submit').hide();
	$('#modalAddWord .j-edit-word-submit').removeClass('hidden').show();
	$('#modalAddWord').modal();
}

function nl2br( str ) {
	return str.replace(/([^>])\n/g, '$1<br/>');
}
