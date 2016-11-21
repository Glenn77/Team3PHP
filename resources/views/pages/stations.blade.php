@extends('layouts.default')
@section('title', 'NMBSTeam WebApp')
@section('content')
    <h2>Stations</h2>

    <div class="container">
        <div class="search-form row">
            {{ Form::open(['url' => '/stations']) }}
            {{ Form::token() }}
            <div class="row">
                <div class="col-sm-2">{{ Form::label('Name', 'Station') }}</div>
                <div class="col-sm-8">{{ Form::text('Name', 'Groenendael') }}</div>
            </div>
            <div class="row">
                <div class="col-sm-2">{{ Form::label('Date', 'Datum') }}</div>
                <div class="col-sm-6">{{ Form::date('Date', \Carbon\Carbon::now()) }}</div>
                <div class="col-sm-2 radio-align">{{ Form::radio('TimeSel', 'DEP', true) }} {{ Form::label('TimeSel', 'Depart') }}</div>
            </div>
            <div class="row">
                <div class="col-sm-2">{{ Form::label('Time', 'Uur') }}</div>
                <div class="col-sm-6">{{ Form::text('Time', \Carbon\Carbon::now()->format('H:i')) }}</div>
                <div class="col-sm-2 radio-align">{{ Form::radio('TimeSel', 'ARR', false) }} {{ Form::label('TimeSel', 'Arrive') }}</div>
            </div>


            <div class="row">
                <div class="col-xs-8 col-xs-offset-2">
                    {{ Form::submit('Search') }}
                </div>
            </div>

            {{ Form::close() }}
        </div>

        <div class="search-results row">

            @if(isset($error))
                @if($error)
                    <span class="error-alert">An error occured!</span>

                @else
                    <div class="search-results-header row">
                        <div class="col-xs-2">Uur</div>
                        <div class="col-xs-8"></div>
                        <div class="col-xs-2">Spoor</div>
                    </div>


                    @foreach($departures->departure as $departure)
                        <a href="#toTreinInfo{{ $departure->vehicle }}">
                            <div class="search-result">
                                <div class="title-primary row">
                                    <div class="col-xs-2">
                                        {{ date('H:i', $departure->time) }}
                                        @if ($departure->delay != 0)
                                            <span class="vertraging">{{ intval($departure->delay)/60 }}</span>
                                        @endif
                                    </div>
                                    <div class="col-xs-8">{{ $departure->stationinfo->standardname }}</div>
                                    <div class="col-xs-2">{{ $departure->platform }}</div>
                                </div>
                            </div>
                        </a>


                    @endforeach
                @endif
            @endif
        </div>
    </div>
@stop
