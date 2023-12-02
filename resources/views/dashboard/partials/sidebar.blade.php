<!-- partial:partials/_sidebar.html -->
<nav class="sidebar">
    <div class="sidebar-header">
        <a href="{{route('flights.index')}}" class="sidebar-brand">
            Fl<span>ie</span>ghts
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">
            <li class="nav-item nav-category">Flights</li>
            <li class="nav-item">
                <a href="{{route('flights.index')}}" class="nav-link">
                    <i class="link-icon" data-feather="search"></i>
                    <span class="link-title">Search</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('travels.index')}}" class="nav-link">
                    <i class="link-icon" data-feather="book-open"></i>
                    <span class="link-title">My flights</span>
                </a>
            </li>
            <!li class="nav-item nav-category">Management</li>

            <li class="nav-item">
                <a href="#" class="nav-link">

                    <span class="link-icon mdi mdi-needle"></span>

                    <span class="link-title">Contact us</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="link-icon" data-feather="message-square"></i>
                    <span class="link-title">About Flieghts</span>
                </a>
            </li>


        </ul>
    </div>
</nav>
