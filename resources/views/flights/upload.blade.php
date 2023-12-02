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
                        <h4 class="card-title">Create New Flight</h4>

                        {{-- Display validation errors if any --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="https://x3ojbpahgh.execute-api.us-east-1.amazonaws.com/CPPAPIv2/storage" enctype="multipart/form-data" >
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">Name:</label>
                                        <input type="text" id="name" name="name" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date">Date:</label>
                                        <input type="date" id="date" name="date" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="file">Upload File:</label>
                                        <input type="file" id="file" name="file" class="form-control-file" required>
                                    </div>
                                </div>
                            </div>






                            <button type="submit" class="btn btn-primary">Upload File</button>
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
