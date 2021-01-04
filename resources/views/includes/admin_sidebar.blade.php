              {{-- Admin --}}
              <li class="menu-header">Admin</li>
              <li class="nav-item dropdown {{ set_active(['period.index', 'usulan.index']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-book"></i> <span>Data PKM</span></a>
                <ul class="dropdown-menu">
                  <li class="{{ set_active('period.index') }}"><a class="nav-link" href="{{ route('period.index') }}">Periode Pembukaan</a></li>
                  <li class="{{ set_active('usulan.index') }}"><a class="nav-link" href="{{ route('usulan.index') }}">Usulan Proposal</a></li>
                </ul>
              </li>
              <li class="{{ set_active(['user.index', 'user.create', 'user.show']) }}"><a class="nav-link" href="{{ route('user.index') }}"><i class="fas fa-users"></i> <span>Data User</span></a></li>
              <li class="{{ set_active('recap.index') }}"><a class="nav-link" href="{{ route('recap.index') }}"><i class="fas fa-clipboard-list"></i> <span>Rekapitulasi</span></a></li>
