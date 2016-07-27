@extends('layouts.main')

@section('add_title')
    Изученные слова
@endsection

@section('page_title_ico')
    ico-check
@endsection

<script type="text/javascript">
    var loadid = 21;
</script>

@section('page_title')
    Изученные слова
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
				</tr>
                @foreach ($words as $word)
                <tr>
					<td>{{ $word->en }}</td>
					<td data-ru="{{ $word->ru }}"><a class="show-ru" href="#">-- показать --</a></td>
					<td>
						<div class="btn-group">
                          <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                            Действия
                            <span class="caret"></span>
                          </a>
                          <ul class="dropdown-menu">
                                <li><a class="btn btn-success j-to-dict" data-dict-id="{{$word->id}}">Вернуть в общий словарь</a></li>
                                <li><a class="btn btn-warning j-to-repeat" data-dict-id="{{$word->id}}">Переместить в "Нужно повторять"</a></li>
                          </ul>
                        </div>
					</td>
				</tr>
                @endforeach
			</table>
		</div>
    <!-- end: Container -->
@endsection

@section('add_script')
<script type="text/javascript">
    isLearning = true;
</script>
@endsection