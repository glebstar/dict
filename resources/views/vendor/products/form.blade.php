<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $name }}</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ $action }}">
                        {{ csrf_field() }}

                        @if('edit' == $type)
                        <input type="hidden" name="_method" value="put">
                        @if ('admin' != app('current_user_type'))
                        <input type="hidden" name="art" value="{{ $productArt }}">
                        @endif
                        @endif
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Наименование</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ $productName }}">

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('art') ? ' has-error' : '' }}">
                            <label for="art" class="col-md-4 control-label">Артикул</label>

                            <div class="col-md-6">
                                <input @if ('admin' != app('current_user_type')) disabled="disabled" @endif id="art" type="text" class="form-control" name="art" value="{{ $productArt  }}">

                                @if ($errors->has('art'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('art') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    @if('edit' == $type)
                                    <i class="fa fa-btn fa-send"></i> Обновить
                                    @else
                                    <i class="fa fa-btn fa-plus"></i> Добавить
                                    @endif
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>