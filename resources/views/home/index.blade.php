@extends('layouts.main')

@section('add_title')
    Список слов
@endsection

@section('page_title_ico')
    ico-book
@endsection

<script type="text/javascript">
    var loadid = 21;
</script>

@section('page_title')
    Частотный словарь английских слов
@endsection

@section('content')
    <!-- start: Container -->
    <div class="container">
		@can('auth')
        <div style="margin-bottom: 25px;">
				<a class="btn btn-success j-add-word">Добавить новое слово (будет сразу добавлено в "Повторять чаще")</a>
			</div>
        @endcan
            <div style="margin-bottom: 25px;">
                <button class="btn btn-large btn-block btn-info j-change-first" type="button" data-to-change=@if ('en' == $firstlang) "ru" @else "en" @endif><i class="icon-refresh"></i> Переключить вид на @if ('en' == $firstlang) Русский - Английский @else Английский - Русский @endif</button>
            </div>
			<table class="table table-striped j-word-table">
				<tr>
					<th><i class="icon-book"></i> Слово</th>
					<th><i class="icon-tags"></i> Перевод</th>
					<th></th>
				</tr>
                @foreach ($words as $word)
                <tr>
					<td class="j-word-en">@if ($word->repeatId)<i class="icon-star-empty"></i> @endif{{ $word->en }}</td>
					<td data-ru="{{ $word->ru }}"><a class="show-ru" href="#">-- показать --</a></td>
					<td>
                        @can('auth')
						<div class="btn-group">
                          <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                            Действия
                            <span class="caret"></span>
                          </a>
                          <ul class="dropdown-menu">
                                <li><a class="btn j-to-learning" data-dict-id="{{$word->id}}"><i class="icon-check"></i> Убрать в изученные</a></li>
                                @if (!$word->repeatId)
                                <li><a class="btn j-to-repeat" data-dict-id="{{$word->id}}"><i class="icon-repeat"></i> Хочу повторять чаще</a></li>
                                @endif
                                <li><a class="btn"><i class="icon-pencil"></i> Предложить другой перевод</a></li>
                          </ul>
                        </div>
                        @endcan
					</td>
				</tr>
                @endforeach
			</table>
			<div class="center">
                @can('auth')
			    <a class="btn btn-link j-load-more">Загрузить еще...</a>
                @else
                    <span class="text-error" style="font-size: 18px;">
                        <a href="/login" class="white">Войдите</a> или <a href="/register">Зарегистрируйтесь</a>, чтобы смотреть все слова. <span class="text-success">Это бесплатно!</span><br /><br />
                        <span class="text-info">У авторизованного пользователя есть возможность управлять словарем - убирать слова в "Изученные", добавлять в список избранных для частого повторения...</span>
                    </span>
                @endcan
			</div>
		</div>
    <!-- end: Container -->

    @can('auth')
        <div id="modalAddWord" class="modal hide fade">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>Добавить новое слово</h3>
          </div>
          <div class="modal-body">
              <div class="error hidden">
              </div>
              <p class="text-info">Слово будет сразу добавлено в "Нужно повторять"</p>
              <form class="form-horizontal">
                  <div class="control-group">
                    <label class="control-label" for="j-add-word-input-en">Слово по английски</label>
                    <div class="controls">
                      <input style="width: 300px;" type="text" id="j-add-word-input-en" placeholder="По английски">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="j-add-word-input-ru">Перевод</label>
                    <div class="controls">
                      <input style="width: 300px;" type="text" id="j-add-word-input-ru" placeholder="Перевод">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="j-add-word-input-desc">Описание</label>
                    <div class="controls">
                        <textarea style="width: 300px;" rows="5" id="j-add-word-input-desc" placeholder="Описание (необязательно)"></textarea>
                    </div>
                  </div>
                </form>
          </div>
          <div class="modal-footer">
              <a href="#" class="btn j-modal-close">Отмена</a>
              <a href="#" class="btn j-add-word-submit">Добавить</a>
          </div>
        </div>
    @endcan

@endsection