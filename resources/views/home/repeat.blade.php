@extends('layouts.main')

@section('add_title')
    Слова для повторения
@endsection

@section('page_title_ico')
    ico-repeat
@endsection

<script type="text/javascript">
    var loadid = 21;
</script>

@section('page_title')
    Слова для повторения
@endsection

@section('content')
    <!-- start: Container -->
    <div class="container">
            <div style="margin-bottom: 25px;">
                <button class="btn btn-large btn-block btn-info j-change-first" type="button" data-to-change=@if ('en' == $firstlang) "ru" @else "en" @endif><i class="icon-refresh"></i> Переключить вид на @if ('en' == $firstlang) Русский - Английский @else Английский - Русский @endif</button>
            </div>
			<table class="table table-striped j-word-table">
				<tr>
					<th><i class="icon-book"></i> Слово</th>
					<th><i class="icon-tags"></i> Перевод</th>
					<th></th>
                    <th></th>
				</tr>
                @foreach ($words as $word)
                <tr>
					<td>{{ $word->en }}@if($word->trans && 'en' == $firstlang) [{{$word->trans}}]@endif</td>
					<td data-ru="{{ $word->ru }}@if($word->trans && 'en' != $firstlang) [{{$word->trans}}]@endif"><a class="show-ru" href="#">-- показать --</a></td>
                    <td><i class="icon-zoom-in j-show-description" style="cursor: pointer" data-description="<?php echo nl2br($word->description); ?>" data-word="{{ $word->en }} - {{ $word->ru }}"></i></td>
					<td>
						<div class="btn-group">
                          <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                            Действия
                            <span class="caret"></span>
                          </a>
                          <ul class="dropdown-menu">
                                <li><a class="btn j-to-learning" data-dict-id="{{$word->id}}"><i class="icon-check"></i> Убрать в изученные</a></li>
                                <li><a class="btn j-to-dict" data-dict-id="{{$word->id}}"><i class="icon-book"></i> Вернуть в общий словарь</a></li>
                          </ul>
                        </div>
					</td>
				</tr>
                @endforeach
			</table>
		</div>
    <!-- end: Container -->
@endsection