<div>
    <p class="text-white-light mb-0 px-4 pt-4 font-13 py-3">Indicadores de Gestión</p>
    <div class="menu-option">
        <!-- Estructuras -->
        <a href="#submenuEstructura" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseExample" class="px-4 d-flex justify-content-between waves-effect {{ request()->is('sms') ? 'menu-active' : '' }}">
           <div>
                <i class="fa fa-sitemap pr-2"></i>Estructuras
           </div>
           <i class="fa fa-angle-down pt-1"></i>
        </a>
        <div class="collapse" id="submenuEstructura" class="bg-blue-bold">
            <a href="{{route('estructuracartera')}}" class="bg-blue-bold text- px-5 waves-effect {{ request()->is('estructuracartera') ? 'menu-active' : '' }}">Estructura Por Cartera</a>
            <a href="{{route('estructuragestor')}}" class="bg-blue-bold text- px-5 waves-effect {{ request()->is('estructuragestor') ? 'menu-active' : '' }}">Estructura Por Gestor</a>
        </div>
        <!-- Indicadores -->
        <a href="{{route('indicadoresoperativos')}}" class="px-4 waves-effect {{ request()->is('indicadoresoperativos') ? 'menu-active' : '' }}"><i class="fa fa-chart-line pr-2"></i>Indicadores Operativos</a>
        <!-- Plan de Trabajo -->
        <a href="#submenuPlan" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseExample" class="d-flex justify-content-between px-4 waves-effect {{ request()->is('smscampanas') ? 'menu-active' : '' }}">
            <div>
                <i class="fa fa-layer-group pr-2"></i>Plan de Trabajo
            </div>
           <i class="fa fa-angle-down pt-1"></i>
        </a>
        <div class="collapse" id="submenuPlan" class="bg-blue-bold">
            <a href="{{route('crearplantrabajo')}}" class="bg-blue-bold text- px-5 waves-effect {{ request()->is('crearplantrabajo') ? 'menu-active' : '' }}">Crear Plan</a>
            <a href="{{route('seguimientoplantrabajo')}}" class="bg-blue-bold text- px-5 waves-effect {{ request()->is('seguimientoplantrabajo') ? 'menu-active' : '' }}">Seguimiento</a>
            @if(auth()->user()->emp_tip_acc==5 || auth()->user()->emp_tip_acc==6)
            <a href="{{route('reporteplantrabajo')}}" class="bg-blue-bold text- px-5 waves-effect {{ request()->is('reporteplantrabajo') ? 'menu-active' : '' }}">Información Resumen</a>
            @endif
        </div>
        <!-- Reportes -->
        <a href="#submenureporte" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseExample" class="d-flex justify-content-between px-4 waves-effect {{ request()->is('smscampanas') ? 'menu-active' : '' }}">
            <div>
                <i class="fa fa-list-alt pr-2"></i>Reportes
            </div>
           <i class="fa fa-angle-down pt-1"></i>
        </a>
        <div class="collapse" id="submenureporte" class="bg-blue-bold">
            <a href="{{route('reportegeneral')}}" class="bg-blue-bold text- px-5 waves-effect {{ request()->is('reportegeneral') ? 'menu-active' : '' }}">Reporte General</a>
            <a href="{{route('reportegestor')}}" class="bg-blue-bold text- px-5 waves-effect {{ request()->is('reportegestor') ? 'menu-active' : '' }}">Gest. Telefónica por Gestor</a>
            <a href="{{route('reporteprimerayultimagestion')}}" class="bg-blue-bold text- px-5 waves-effect {{ request()->is('reporteprimerayultimagestion') ? 'menu-active' : '' }}">Primera y Última Gestión</a>
            <a href="{{route('reportegestionhora')}}" class="bg-blue-bold text- px-5 waves-effect {{ request()->is('reportegestionhora') ? 'menu-active' : '' }}">Cant. Gestiones por Hora</a>
            <a href="{{route('resumengestion')}}" class="bg-blue-bold text- px-5 waves-effect {{ request()->is('resumengestion') ? 'menu-active' : '' }}">Resumen Gestión Diaria</a>
            <a href="{{route('resumengestionconsolidada')}}" class="bg-blue-bold text- px-5 waves-effect {{ request()->is('resumengestionconsolidada') ? 'menu-active' : '' }}">Resumen Gestión Consolidada</a>
            <a href="{{route('comparativocartera')}}" class="bg-blue-bold text- px-5 waves-effect {{ request()->is('comparativocartera') ? 'menu-active' : '' }}">Comparativo</a>
            <a href="{{route('reporteestandar')}}" class="bg-blue-bold text- px-5 waves-effect {{ request()->is('reporteestandar') ? 'menu-active' : '' }}">Reporte Estándar</a>
        </div>
        <!-- timing y proyectado -->
        <a href="{{route('timingyproyectado')}}" class="px-4 waves-effect {{ request()->is('timingyproyectado') ? 'menu-active' : '' }}"><i class="fa fa-stopwatch pr-2"></i>Timing y Proyectado</a>
        <!-- analisis de pdps -->
        <a href="#submenupdp" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseExample" class="d-flex justify-content-between px-4 waves-effect {{ request()->is('smscampanas') ? 'menu-active' : '' }}">
            <div>
                <i class="fa fa-handshake pr-2"></i>PDPS
            </div>
           <i class="fa fa-angle-down pt-1"></i>
        </a>
        <div class="collapse" id="submenupdp" class="bg-blue-bold">
            <a href="{{route('listadopdps')}}" class="bg-blue-bold text- px-5 waves-effect {{ request()->is('listadopdps') ? 'menu-active' : '' }}">Listado de PDPS</a>
            <a href="{{route('pdps')}}" class="bg-blue-bold text- px-5 waves-effect {{ request()->is('pdps') ? 'menu-active' : '' }}">Plazos de Pagos</a>
            <a href="{{route('estadospdps')}}" class="bg-blue-bold text- px-5 waves-effect {{ request()->is('estadospdps') ? 'menu-active' : '' }}">Cumplimiento de PDPS</a>
            <a href="{{route('estandarpdps')}}" class="bg-blue-bold text- px-5 waves-effect {{ request()->is('estandarpdps') ? 'menu-active' : '' }}">Estándar de PDPS</a>
            @if(auth()->user()->emp_tip_acc==5 || auth()->user()->emp_tip_acc==6)
            <a href="{{route('comparativapdps')}}" class="bg-blue-bold text- px-5 waves-effect {{ request()->is('comparativapdps') ? 'menu-active' : '' }}">Comparativa de PDPS</a>
            <a href="{{route('comparativapagos')}}" class="bg-blue-bold text- px-5 waves-effect {{ request()->is('comparativapagos') ? 'menu-active' : '' }}">Comparativa de Pagos</a>
            @endif
        </div>

        <!-- Actualizaciones -->
        <a href="{{route('listadoactualizaciones')}}" class="px-4 waves-effect {{ request()->is('listadoactualizaciones') ? 'menu-active' : '' }}"><i class="fa fa-sync pr-2"></i>Actualizaciones</a>       
        <!-- Reporte control -->
        <a href="{{route('control')}}" class="px-4 waves-effect {{ request()->is('control') ? 'menu-active' : '' }}"><i class="fa fa-desktop pr-2"></i>Reporte de Control</a>       

    </div>
</div>
