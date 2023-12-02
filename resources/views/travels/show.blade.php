@extends('dashboard.dashboard')

@section('custom-css')
    <style>
        .size-image {
            width: 300px; /* Adjust as needed */
            height: auto; /* Maintain aspect ratio */
        }

        .content {
            /* background-color: #404040;*/
            width: 940px;
            margin: 0 auto;
            padding: 20px 10px;
            overflow: hidden;
            border-bottom-left-radius: 6px;
            border-bottom-right-radius: 6px;
        }

        .photo {
            border-radius: 6px;
            border: 1px solid #ccc;
            width: 298px;
            height: 298px;
            margin-top: 10px;
            margin-left: 10px;
            float: left;
            overflow: hidden;
            position: relative;
        }

        .photo-name {
            position: absolute;
            bottom: 15px;
            width: 100%;
            padding: 5px 0;
            font-size: 20px;
            color: white;
            text-align: center;
            background-color: #666666;
            font-style: italic;
            font-family: sans-serif;
        }
    </style>
@endsection

@section('main')

    <div class="page-content">

        <nav class="page-breadcrumb">
            @include('dashboard.notifications.notification')
            <ol class="breadcrumb">
                <form method="post" action="{{ route('travels.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="travel_id" value="{{$travel_id}}">
                    <input type="file" name="image" accept="image/jpeg, image/png, image/gif" required >
                    <input type="submit" class="btn btn-success" value="Upload Image">
                </form>

            </ol>
        </nav>


        <div class="card">
            <div class="card-body">
                <h4 class="card-title">My Photos</h4>
                <div class='content'>
                    @forelse ($filenames as $key => $value)
                        <div class='photo'>
                            <div class='image'>
                                <a href='#'>
                                    <img src="{{ Storage::disk('s3')->url($value['path']) }}"
                                         alt="alt image travel" class="size-image">
                                </a>
                            </div>
                            <div class='photo-name'>{{$value['name']}}</div>
                        </div>
                    @empty
                        <p>No images to show, try upload an image!!!</p>
                    @endforelse
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
