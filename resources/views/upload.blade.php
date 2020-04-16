@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">CSV Import</div>

                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('parse_file') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group{{ $errors->has('csv_file') ? ' has-error' : '' }}">
                                <div class="col-md-6">
                                    <input id="csv_file" type="file" class="form-control" name="csv_file" accept=".csv" required>

                                    @if ($errors->has('csv_file'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('csv_file') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Parse CSV
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection