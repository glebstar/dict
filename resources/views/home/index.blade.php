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
				<a class="btn btn-success">Добавить новое слово (будет сразу добавлено в "Повторять чаще")</a>
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
					<td>@if ($word->repeatId)<b>@endif{{ $word->en }}@if ($word->repeatId)</b>@endif</td>
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
@endsection