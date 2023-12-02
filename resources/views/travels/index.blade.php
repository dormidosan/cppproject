@extends('dashboard.dashboard')

@section('custom-css')
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{asset('assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css')}}">
    <link href="{{ asset('assets/vendors/select2/select2.min.css')}}" rel="stylesheet"/>

@endsection

@section('main')

    <div class="page-content">

        <nav class="page-breadcrumb">

        </nav>
        @forelse ($travels as $key => $value)
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">

                                <div class="row">
                                    <div class="col-md-4">
                                        <span style="text-transform: none;">
                                            Trip:
                                        </span>
                                        {{$value->departure_iata}} - {{$value->arrival_iata}}
                                    </div>
                                    <div class="col-md-4">
                                        <span style="text-transform: none;">
                                            Price:
                                        </span>
                                        {{$value->price}} EUR
                                    </div>
                                    <div class="col-md-4">
                                        <a href="{{ route('travels.show', $value->id) }}" class="btn btn-inverse-success">Photos</a>
                                    </div>
                                </div>
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="departureDate">Departure Date</label>
                                    <span  type="date" class="form-control" id="departureDate" name="departureDate" style="padding-left: 0px;border: 0px;">
                                        {{ date('D d/M h:m A', strtotime($value->departure_date)) }}
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <label for="arrivalDate">Arrival Date</label>
                                    <span  type="date" class="form-control" id="arrivalDate" name="arrivalDate" style="padding-left: 0px;border: 0px;">
                                        {{ date('D d/M h:m A', strtotime($value->arrival_date)) }}
                                    </span>

                                </div>
                                <div class="col-md-4">
                                    <label for="imagescount">Images uploaded</label>
                                    <span  type="date" class="form-control" id="imagescount" name="imagescount" style="padding-left: 0px;border: 0px;">
                                        {{$value->images()->count()}}
                                    </span>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>No flights had been purchased</p>
        @endforelse




            @if (Session::has('success'))
                <div class="alert alert-success alert-flights">
                    {{ Session::get('success') }}
                </div>
            @endif


    </div>



    @if (Session::has('error'))
        <div class="alert alert-danger alert-flights">
            {{ Session::get('error') }}
        </div>
    @endif

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
            console.log('script index');
            $('#origin').select2({
                placeholder: 'select origin',
                //allowClear: 'false',
                //tags: 'false', allow to leave the select with data entered
                ajax: {
                    url: '{{route('flights.iata')}}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
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
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
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
