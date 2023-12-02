@extends('dashboard.dashboard')

@section('custom-css')
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{asset('assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css')}}">



@endsection

@section('main')

    <div class="page-content">

        <nav class="page-breadcrumb">

        </nav>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('flights.search') }}">
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="mb-3">
                                        <label for="origin">Origin</label>
                                        <select class="form-control" id="origin" name="origin" data-width="100%" required >
                                        </select>
                                    </div>
                                </div><!-- Col -->
                                <div class="col-sm-5">
                                    <div class="mb-3">
                                        <label for="destination">Destination</label>
                                        <select class="form-control" id="destination" name="destination" data-width="100%" required >
                                        </select>
                                    </div>
                                </div><!-- Col -->
                                <div class="col-sm-2">
                                    <div class="mb-3">
                                        <label for="search-btn">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                        <input id="search-btn" name="search-btn"  type="submit" class="btn btn-success" value="Search Flights">
                                    </div>
                                </div><!-- Col -->
                            </div><!-- Row -->
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="mb-3">
                                        <label for="departureDate">Departure Date</label>
                                        <input  type="date" class="form-control" id="departureDate" name="departureDate" required >
                                    </div>
                                </div><!-- Col -->
                                <div class="col-sm-5" style="display:none">
                                    <div class="mb-3" >
                                        <label for="returnDate">Return Date</label>
                                        <input type="date" class="form-control" id="returnDate" name="returnDate">
                                    </div>
                                </div><!-- Col -->
                                <div class="col-sm-2" style="display:none">
                                    <div class="mb-3">
                                        <label for="adultsNumber">Number of Adults</label>
                                        <input type="number" class="form-control" id="adultsNumber" name="adultsNumber" min="1" value="1">
                                    </div>
                                </div><!-- Col -->
                            </div><!-- Row -->
                        </form>


                    </div>
                </div>
            </div>
        </div>
        @include('dashboard.notifications.notification')

    </div>



@endsection



@section('libraries')
    <script src="{{ asset('assets/vendors/datatables.net/jquery.dataTables.js')}}" ></script>
    <script src="{{ asset('assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js')}}"></script>
    <script src="{{ asset('assets/vendors/select2/select2.min.js')}}"></script>

@endsection


@section('scripts')
    <script>
        $(function() {
            'use strict';
            console.log('script index');
            $('#origin').select2({
                placeholder: 'select origin',
                //allowClear: 'false',
                //tags: 'false', allow to leave the select with data entered
                ajax: {
                    url: '{{route('flights.iata')}}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        console.log(data.hit);
                        data = data.airports;
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.iata,
                                    text: item.name + ' - ' + item.country + ' - ' + item.iata
                                };
                            })
                        };
                    },
                    cache: true
                },
                selectOnClose: true,
                minimumInputLength: 2,
                minimumResultsForSearch: 10,

            });

            $('#destination').select2({
                placeholder: 'select destiny',
                //allowClear: 'false',
                //tags: 'false', allow to leave the select with data entered
                ajax: {
                    url: '{{route('flights.iata')}}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        console.log(data.hit);
                        data = data.airports;
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.iata,
                                    text: item.name + ' - ' + item.country + ' - ' + item.iata
                                };
                            })
                        };
                    },
                    cache: true
                },
                selectOnClose: true,
                minimumInputLength: 2,
                minimumResultsForSearch: 10,

            });



        });
    </script>
@endsection
