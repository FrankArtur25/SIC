<div>
    <p class="text-white-light mb-0 px-4 pt-4 font-13 py-3">Reporte de Incidencias</p>
    <div class="menu-option">
        <a href="{{route('nuevaincidencia')}}" class="px-4 waves-effect {{ request()->is('nuevaincidencia') ? 'menu-active' : '' }}"><i class="fa fa-exclamation-triangle pr-2"></i>Registrar Incidencia</a>
        <a href="{{route('incidencias')}}" class="px-4 waves-effect {{ request()->is('incidencias') ? 'menu-active' : '' }}"><i class="fa fa-tasks pr-2"></i>Lista de Incidencia</a>
    </div>
</div>
