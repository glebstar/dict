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
			<div style="margin-bottom: 25px;">
				<a class="btn btn-success">Добавить новое слово (будет сразу добавлено в "Повторять чаще")</a>
			</div>
			<table class="table table-striped j-word-table">
				<tr>
					<th>English</th>
					<th>По русски</th>
					<th>Управление</th>
				</tr>
                @foreach ($words as $word)
				<tr>
					<td>{{ $word->en }}</td>
					<td data-ru="{{ $word->ru }}"><a class="show-ru" href="#">-- показать --</a></td>
					<td>
						<a href="#" class="btn btn-success">Убрать в изученные</a>
						<a href="#" class="btn btn-warning">Хочу повторять чаще</a>
						<a href="#" class="btn btn-info">Предложить другой перевод</a>
					</td>
				</tr>
                @endforeach
			</table>
			<div class = "center">
			<a class="btn btn-link j-load-more">Загрузить еще...</a>
			</div>
		</div>
    <!-- end: Container -->
@endsection