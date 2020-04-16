@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">CSV Parse</div>

                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('process_file') }}">
                            @csrf
                            <input type="hidden" name="csv_data_file_id" value="{{ $csv_data_file->id }}" />

                            <div class="file-content">
                                @foreach ($csv_data as $row)
                                    @foreach ($row as $key => $value)
                                        {{ $value }}
                                        <br>
                                    @endforeach
                                @endforeach
                            </div>

                            <button type="submit" class="btn btn-primary">
                                Import Data
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection