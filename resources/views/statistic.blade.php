@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Statistics</div>
                    <div class="panel-body">
                        @foreach ($statistics as $statistic)
                            <div>
                                <p>Keyword: {{ $statistic->keyword }}</p>
                                <p>Number of Results: {{ $statistic->total_search_results }}</p>
                                <p>Number of Adwords: {{ $statistic->total_adwords }}</p>
                                <p>Number of Links: {{ $statistic->total_links }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection