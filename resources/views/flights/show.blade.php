@extends('dashboard.dashboard')

@section('custom-css')

@endsection

@section('main')

    <div class="page-content">

        <nav class="page-breadcrumb">
            <ol class="breadcrumb">

            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">flight Details</h4>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <strong>First Name:</strong> {{ $flight->first_name }}
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <strong>Last Name:</strong> {{ $flight->last_name }}
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <strong>Date of Birth:</strong> {{ $flight->date_of_birth }}
                                </div>
                            </div><!-- Col -->
                        </div><!-- Row -->

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <strong>Gender:</strong> {{ $flight->gender }}
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <strong>Email:</strong> {{ $flight->email }}
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <strong>Phone:</strong> {{ $flight->phone }}
                                </div>
                            </div><!-- Col -->
                        </div><!-- Row -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <strong>Address:</strong> {{ $flight->address }}
                                </div>
                            </div><!-- Col -->
                        </div><!-- Row -->
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <strong>City:</strong> {{ $flight->city }}
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <strong>State:</strong> {{ $flight->state }}
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <strong>Zip Code:</strong> {{ $flight->zip_code }}
                                </div>
                            </div><!-- Col -->
                        </div><!-- Row -->



                        <a href="{{ route('flights.edit', $flight) }}" class="btn btn-inverse-warning">Edit</a>
                        <form action="{{ route('flights.destroy', $flight) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-inverse-danger" onclick="return confirm('Are you sure you want to delete this flight?')">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>


@endsection



@section('libraries')

@endsection


@section('scripts')
    <script>

    </script>
@endsection
