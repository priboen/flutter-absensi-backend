<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('home') }}">Kedokteran Umum</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('home') }}">KU</a>
        </div>
        <ul class="sidebar-menu">
            <li class="nav-item">
                <a href="{{ route('home') }}" class="nav-link"><i class="fa-solid fa-house"></i><span>Beranda</span></a>
            </li>
            <li class="menu-header">GENERAL</li>
            <li class="nav-item">
                <a href="{{ route('users.index') }}" class="nav-link"><i
                        class="fa-solid fa-users"></i><span>Pengguna</span></a>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"><i
                        class="fa-solid fa-book-open-reader"></i><span>Kurikulum</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('classes.index') }}">Rencana Studi Mahasiswa</a></li>
                    <li><a class="nav-link" href="{{ route('courses.index') }}">Mata Kuliah</a></li>
                    <li><a class="nav-link" href="{{ route('classrooms.index') }}">Ruang Kelas</a></li>
                    <li><a class="nav-link" href="{{ route('groups.index') }}">Rombongan Belajar</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fa-solid fa-clipboard-user"></i><span>
                        Presensi</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('attendance-schedules.index') }}">Daftar Presensi</a></li>
                    <li><a class="nav-link" href="{{ route('attendances.index') }}">Presensi Mahasiswa</a></li>
                    <li><a class="nav-link" href="{{ route('permissions.index') }}">Perizinan Kuliah</a></li>
                </ul>
            </li>
        </ul>
    </aside>
</div>
