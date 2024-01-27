{{-- Admin --}}
<li class="menu-header">Admin</li>
<li class="{{ set_active('period.index') }}"><a class="nav-link" href="{{ route('period.index') }}"><i class="far fa-calendar-alt"></i> <span>Periode Pembukaan</span></a></li>
<li class="{{ set_active('usulan.index') }}"><a class="nav-link" href="{{ route('usulan.index') }}"><i class="fas fa-file-upload"></i> <span>Usulan Proposal</span></a></li>
<li class="{{ set_active('recap.index') }}"><a class="nav-link" href="{{ route('recap.index') }}"><i class="fas fa-clipboard-list"></i> <span>Rekapitulasi</span></a></li>

<li class="menu-header">Monitoring</li>
<li class="#"><a class="nav-link" href="#"><i class="fas fa-clipboard-list"></i> <span>Log Activity</span></a></li>
<li class="{{ set_active('monitoring.log-error') }}"><a class="nav-link" href="{{ route('monitoring.log-error') }}"><i class="fas fa-bug"></i> <span>Log Error</span></a></li>
<li class="#"><a class="nav-link" href="#"><i class="fas fa-sort-numeric-up-alt"></i> <span>Queue</span></a></li>

<li class="menu-header">Manajemen</li>
<li class="nav-item dropdown">
  <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-users"></i> <span>Pengguna</span></a>
  <ul class="dropdown-menu">
    <li class="{{ set_active(['user.index', 'user.create', 'user.show']) }}"><a class="nav-link" href="{{ route('user.index') }}">Data Pengguna</a></li>
    <li class="#"><a class="nav-link" href="#">Grup</a></li>
    <li class="#"><a class="nav-link" href="#">Hak Akses</a></li>
  </ul>
</li>
<li class="{{ set_active('major.index') }}"><a class="nav-link" href="{{ route('major.index') }}"><i class="fas fa-university"></i> <span>Data Prodi</span></a></li>
<li class="{{ set_active('refskema.index') }}"><a class="nav-link" href="{{ route('refskema.index') }}"><i class="fas fa-layer-group"></i> <span>Skema</span></a></li>
