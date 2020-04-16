@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default statistics-header">
                    <div class="panel-heading">
                        Statistics
                        <a href="{{ route('upload') }}" class="btn btn-success float-right">Upload CSV</a>
                    </div>
                </div>
                @foreach ($statistics as $statistic)
                    <div class="panel panel-default statistic">
                        <div class="panel-body">
                            <div>Keyword: {{ $statistic->keyword }}</div>
                            <div>Number of Results: {{ $statistic->total_search_results }}</div>
                            <div>Number of Adwords: {{ $statistic->total_adwords }}</div>
                            <div>Number of Links: {{ $statistic->total_links }}</div>
                            <div>HTML Code: <a target="_blank" href="{{ route('statistic_content', $statistic->id) }}">Open it</a></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection