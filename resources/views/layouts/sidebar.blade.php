<div class="sidebar" id="sidebar">
    <nav>
        <ul>
            <li>
                <a href="{{ route('main') }}">
                    <i class="bi bi-house-door-fill iconoSidebar"></i><span>Home</span>
                </a>
            </li>

            <!-- Menú dinámico -->
            @foreach ($menus as $menu)
                <li class="menu-item">
                    <a href="{{ empty($menu->href) || $menu->href == '#' ? '#' : url($menu->href) }}" class="parent-menu">
                        <i class="bi {{ str_replace('icon-', 'bi-', $menu->icono) }}"></i><span>{{ $menu->descripcion }}</span>
                    </a>                    
                    <!-- Submenú (nivel 2) -->
                    @if ($menu->hijos->count())
                        <ul class="submenu" style="display: none;">
                            @foreach ($menu->hijos as $hijo)
                                <li>
                                    <a href="{{ url($hijo->href) }}">
                                        <i class="bi-caret-right-fill"></i><span>{{ $hijo->descripcion }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>

        <div class="logout">
            <ul>
                <li>
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-left"></i><span>Cerrar Sesión</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </nav>
</div>