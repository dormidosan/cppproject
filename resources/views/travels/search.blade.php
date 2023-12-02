@extends('dashboard.dashboard')

@section('custom-css')
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{asset('assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css')}}">
    <link href="{{ asset('assets/vendors/select2/select2.min.css')}}" rel="stylesheet"/>

@endsection

@section('main')

    <div class="page-content">

        <nav class="page-breadcrumb">
            <h6 class="card-title">Results</h6>
        </nav>
        @if (Session::has('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif

        @forelse ($flights['FlightDetails'] as $key => $value)
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">

                                <div class="row">
                                    <div class="col-md-4">
                                        {{$value['FLSDepartureCode']}}-{{$value['FLSDepartureName']}} =>
                                        {{$value['FLSArrivalCode']}}-{{$value['FLSArrivalName']}}
                                    </div>
                                    <div class="col-md-6">
                                        {{date('d/M h:m A', strtotime($value['FLSDepartureDateTime']))}} =>
                                        {{ date('d/M h:m A', strtotime($value['FLSArrivalDateTime'])) }}
                                    </div>
                                    <div class="col-md-2">
                                        <form method="POST" action="{{ route('flights.store') }}">
                                            @csrf
                                            <input type="hidden" name="flight_data" value="{{ json_encode($value) }}">
                                            <input   type="submit" class="btn btn-success" value="Save">
                                        </form>
                                    </div>
                                </div>
                            </h6>
                            <div class="row">
                                <div class="col-md-3">
                                    TotalFlightTime {{str_replace(['PT', 'H', 'M'], ['', 'h ', 'm'],$value['TotalFlightTime'])}}
                                </div>
                                <div class="col-md-3">
                                    TotalMiles {{$value['TotalMiles']}}
                                </div>
                                <div class="col-md-3">
                                    TotalTripTime {{str_replace(['PT', 'H', 'M'], ['', 'h ', 'm'],$value['TotalTripTime'])}}
                                </div>
                                <div class="col-md-3">
                                    <h5>Price {{$value['FLSPrice']}}</h5>
                                </div>
                            </div>
                            <br/>
                            @foreach($value['FlightLegDetails'] as $kFlightLegDetails => $FlightLegDetail )

                                <div class="row">
                                    <div class="col-md-4">
                                        {{date('h:m A', strtotime($FlightLegDetail['DepartureDateTime'])) }}
                                        - {{date('h:m A', strtotime($FlightLegDetail['ArrivalDateTime'])) }}
                                    </div>
                                    <div class="col-md-4">FlightNumber :{{$FlightLegDetail['FlightNumber']}} </div>
                                    <div class="col-md-4">
                                        JourneyDuration:{{str_replace(['PT', 'H', 'M'], ['', 'h ', 'm'], $FlightLegDetail['JourneyDuration']) }} </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        {{ $FlightLegDetail['DepartureAirport']['LocationCode'] }}
                                        - {{ $FlightLegDetail['ArrivalAirport']['LocationCode'] }}
                                    </div>
                                    <div class="col-md-4">{{ $FlightLegDetail['MarketingAirline']['CompanyShortName'] }}
                                        - {{ $FlightLegDetail['Equipment']['AirEquipType'] }} </div>
                                    <div class="col-md-4">Reference:{{$FlightLegDetail['FLSUUID']}} </div>
                                </div>
                                <br/>
                            @endforeach


                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>No flights available for your criteria</p>
        @endforelse


    </div>

@endsection



@section('libraries')
    <script src="{{ asset('assets/vendors/datatables.net/jquery.dataTables.js')}}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js')}}"></script>
    <script src="{{ asset('assets/vendors/select2/select2.min.js')}}"></script>

@endsection


@section('scripts')
    <script>
        $(function () {
            'use strict';


        });
    </script>
@endsection
