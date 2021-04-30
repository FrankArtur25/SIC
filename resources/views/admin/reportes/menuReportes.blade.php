<div>
    <p class="text-white-light mb-0 px-4 pt-4 font-13 py-3">Reportes Generales</p>
    <div class="menu-option">
        <a href="{{route('reporteconfirmaciones')}}" class="px-4 waves-effect {{ request()->is('reporteconfirmaciones') ? 'menu-active' : '' }}"><i class="fa fa-coins pr-2"></i> Reporte Confirmaciones</a>
        <a href="{{route('reportepdps')}}" class="px-4 waves-effect {{ request()->is('reportepdps') ? 'menu-active' : '' }}"><i class="fa fa-handshake pr-2"></i>Reporte Pdps</a>
        <a href="{{route('reporteranking')}}" class="px-4 waves-effect {{ request()->is('reporteranking') ? 'menu-active' : '' }}"><i class="fa fa-chart-line pr-2"></i> Reporte Ranking Gestor</a>
        <a href="{{route('reporteconfirmacionespagos')}}" class="px-4 waves-effect {{ request()->is('reporteconfirmacionespagos') ? 'menu-active' : '' }}"><i class="fa fa-greater-than pr-2"></i> Confirmaciones vs Pagos</a>
        <a href="{{route('gestionesFicticias')}}" class="px-4 waves-effect {{ request()->is('gestionesFicticias') ? 'menu-active' : '' }}"><i class="fa fa-plus-square pr-2"></i> Gestiones Ficticias</a>
    </div>
</div>
