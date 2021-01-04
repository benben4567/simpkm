{{-- Mahasiswa --}}
              <li class="menu-header">Mahasiswa</li>
              <li class="{{ set_active('profile.index') }}" ><a class="nav-link" href="{{ route('profile.index') }}"><i class="fas fa-user"></i> <span>Data Diri</span></a></li>
              <li class="{{ set_active(['proposal.index','proposal.create', 'proposal.edit', 'proposal.member']) }}"><a class="nav-link" href="{{ route('proposal.index') }}"><i class="fas fa-book"></i> <span>Usulan PKM</span></a></li>
              <li class="{{ set_active('panduan.index') }}"><a class="nav-link" href="{{ route('panduan.index') }}"><i class="fas fa-exclamation-circle"></i> <span>Panduan SIM</span></a></li>
