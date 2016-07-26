@extends('layouts.main')

@section('add_title')
    Список слов
@endsection

@section('page_title_ico')
    ico-check
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
			<table class="table table-striped j-word-table">
				<tr>
					<th>English</th>
					<th>По русски</th>
					<th></th>
				</tr>
                @foreach ($words as $word)
                <tr>
					<td>{{ $word->en }}</td>
					<td data-ru="{{ $word->ru }}"><a class="show-ru" href="#">-- показать --</a></td>
					<td>
                        @can('auth')
						<div class="btn-group">
                          <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                            Действия
                            <span class="caret"></span>
                          </a>
                          <ul class="dropdown-menu">
                                <li><a class="btn btn-success j-to-learning" data-dict-id="{{$word->id}}">Убрать в изученные</a></li>
                                <li><a class="btn btn-warning">Хочу повторять чаще</a></li>
                                <li><a class="btn btn-info">Предложить другой перевод</a></li>
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
                        <a href="/login" class="white">Войдите</a> или <a href="/register">Зарегистрируйтесь</a>, чтобы смотреть все слова. Это бесплатно!
                    </span>
                @endcan
			</div>
		</div>
    <!-- end: Container -->
@endsection