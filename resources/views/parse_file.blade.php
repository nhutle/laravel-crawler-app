@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">CSV Parsed</div>

                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('process_file') }}">
                            @csrf
                            <input type="hidden" name="file_id" value="{{ $csv_data_file->id }}" />

                            <div class="file-content">
                                @foreach ($keywords as $keyword)
                                    {{ $keyword }}
                                    <br>
                                @endforeach
                            </div>

                            <button type="submit" class="btn btn-primary">
                                Start Cron Job
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection