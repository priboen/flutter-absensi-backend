<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('home') }}">Kedokteran Umum</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('home') }}">KU</a>
        </div>
        <ul class="sidebar-menu">
            <li class="nav-item  ">
                <a href="{{ route('home') }}" class="nav-link "><i class="fa-solid fa-house"></i><span>Beranda</span></a>
            </li>
            <li class="menu-header">GENERAL</li>
            <li class="nav-item ">
                <a href="{{ route('users.index') }}" class="nav-link "><i class="fa-solid fa-users"></i>
                    <span>Pengguna</span></a>
            </li>
            <li class="menu-header">KURIKULUM</li>
            <li class="nav-item ">
                <a href="{{ route('classes.index') }}" class="nav-link "><i class="fa-solid fa-book-open-reader"></i>
                    <span>Rencana Studi Mahasiswa</span></a>
            </li>
            <li class="nav-item ">
                <a href="{{ route('courses.index') }}" class="nav-link "><i class="fa-solid fa-book"></i>
                    <span>Mata Kuliah</span></a>
            </li>
            <li class="nav-item ">
                <a href="{{ route('classrooms.index') }}" class="nav-link "><i class="fa-solid fa-building"></i>
                    <span>Ruang Kelas</span></a>
            </li>
            <li class="menu-header">PRESENSI</li>
            <li class="nav-item ">
                <a href="{{ route('attendances.index') }}" class="nav-link "><i class="fa-solid fa-clipboard-user"></i>
                    <span>Presensi Kuliah</span></a>
            </li>
            <li class="nav-item ">
                <a href="{{ route('permissions.index') }}" class="nav-link "><i class="fa-solid fa-clipboard-user"></i>
                    <span>Perizinan Kuliah</span></a>
            </li>
            {{--             <li class="nav-item ">
                <a href="{{ route('study-enroll.index') }}" class="nav-link "><i
                        class="fa-duotone fa-solid fa-book-open-reader"></i>
                    <span>Rencana Studi Mahasiswa</span></a>
            </li>
            <li class="nav-item ">
                <a href="{{ route('course-receipts.index') }}" class="nav-link "><i class="fa-solid fa-rupiah-sign"></i>
                    <span>Data Pembayaran</span></a>
            </li>
            <li class="menu-header">PROGRAM STUDI</li>
            <li class="nav-item ">
                <a href="{{ route('classroom.index') }}" class="nav-link "><i class="fa-solid fa-building"></i>
                    <span>Ruang Kelas</span></a>
            </li>
            <li class="nav-item ">
                <a href="{{ route('courses.index') }}" class="nav-link "><i class="fa-solid fa-book"></i>
                    <span>Mata Kuliah</span></a>
            </li>
            <li class="nav-item ">
                <a href="{{ route('schedules.index') }}" class="nav-link "><i class="fa-solid fa-calendar-days"></i>
                    <span>Jadwal Kuliah</span></a>
            </li>
            <li class="nav-item ">
                <a href="{{ route('attendance.index') }}" class="nav-link "><i class="fa-solid fa-clipboard-user"></i>
                    <span>Presensi</span></a>
            </li>
            <li class="nav-item ">
                <a href="{{ route('permit.index') }}" class="nav-link "><i class="fa-solid fa-clipboard-user"></i>
                    <span>Izin</span></a>
            </li>
            <li class="menu-header">PERANGKAT</li>
            <li class="nav-item ">
                <a href="" class="nav-link "><i class="fa-brands fa-bluetooth"></i>
                    <span>Perangkat Bluetooth</span></a>
            </li>
            <li class="nav-item ">
                <a href="" class="nav-link "><i class="fa-solid fa-location-dot"></i>
                    <span>Area Absensi</span></a>
            </li> --}}

            {{-- <li class="nav-item">
                <a href="{{ route('companies.show', 1) }}" class="nav-link">
                    <i class="fas fa-columns"></i>
                    <span>Company</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('attendances.index') }}" class="nav-link">
                    <i class="fas fa-columns"></i>
                    <span>Attendances</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('permissions.index') }}" class="nav-link">
                    <i class="fas fa-columns"></i>
                    <span>Permission</span>
                </a>
            </li> --}}
            {{-- <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-map-marker-alt"></i> <span>Google
                        Maps</span></a>
                <ul class="dropdown-menu">
                    <li><a href="gmaps-advanced-route.html">Advanced Route</a></li>
                    <li><a href="gmaps-draggable-marker.html">Draggable Marker</a></li>
                    <li><a href="gmaps-geocoding.html">Geocoding</a></li>
                    <li><a href="gmaps-geolocation.html">Geolocation</a></li>
                    <li><a href="gmaps-marker.html">Marker</a></li>
                    <li><a href="gmaps-multiple-marker.html">Multiple Marker</a></li>
                    <li><a href="gmaps-route.html">Route</a></li>
                    <li><a href="gmaps-simple.html">Simple</a></li>
                </ul>
            </li> --}}
    </aside>
</div>
