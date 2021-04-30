<div>
    <p class="text-white-light mb-0 px-4 pt-4 font-13 py-3">Asignación de Usuarios</p>
    <div class="menu-option">
        <a href="{{route('asignacionindividual')}}" class="px-4 waves-effect {{ request()->is('asignacionindividual') ? 'menu-active' : '' }}"><i class="fa fa-user pr-2"></i> Asignación Individual</a>
        <a href="{{route('asignacionmultiple')}}" class="px-4 waves-effect {{ request()->is('asignacionmultiple') ? 'menu-active' : '' }}"><i class="fa fa-users pr-2"></i>Asignación Múltiple</a>
        <a href="{{route('intercambio')}}" class="px-4 waves-effect {{ request()->is('intercambio') ? 'menu-active' : '' }}"><i class="fa fa-exchange-alt pr-2"></i>Intercambio de Usuarios</a>
        <a href="{{route('bitacoraasignacion')}}" class="px-4 waves-effect {{ request()->is('bitacoraasignacion') ? 'menu-active' : '' }}"><i class="fa fa-list pr-2"></i>Bitácora Asignación</a>
    </div>
</div>
