<div>
    <p class="text-white-light mb-0 px-4 pt-4 font-13 py-3">Programación y Envío SMS</p>
    <div class="menu-option">
        <a href="{{route('sms')}}" class="px-4 waves-effect {{ request()->is('sms') ? 'menu-active' : '' }}"><i class="fa fa-desktop pr-2"></i>Panel de Control</a>
        <a href="{{route('smscrearcampana')}}" class="px-4 waves-effect {{ request()->is('smscrearcampana') ? 'menu-active' : '' }}"><i class="fa fa-plus pr-2"></i>Crear Campaña</a>
        <a href="{{route('smscampanas')}}" class="px-4 waves-effect {{ request()->is('smscampanas') ? 'menu-active' : '' }}"><i class="fa fa-sms pr-2"></i>Lista de Campañas</a>
        <a href="{{route('smsbandeja')}}" class="px-4 waves-effect {{ request()->is('smsbandeja') ? 'menu-active' : '' }}"><i class="fa fa-envelope pr-2"></i>Bandeja de Entrada</a>                
        <a href="#submenulista" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseExample" class="d-flex justify-content-between px-4 waves-effect">
            <div>
                <i class="fa fa-trash pr-2"></i>Lista Negra
            </div>
           <i class="fa fa-angle-down pt-1"></i>
        </a>
        <div class="collapse" id="submenulista" class="bg-blue-bold">
            <a href="{{route('smslistanegranumero')}}" class="bg-blue-bold text- px-5 waves-effect {{ request()->is('smslistanegranumero') ? 'menu-active' : '' }}">Registrar Números</a>
            <a href="{{route('smslistanegraarchivo')}}" class="bg-blue-bold text- px-5 waves-effect {{ request()->is('smslistanegraarchivo') ? 'menu-active' : '' }}">Cargar Archivo</a>
            <a href="{{route('smsbuscarlistanegra')}}" class="bg-blue-bold text- px-5 waves-effect {{ request()->is('smsbuscarlistanegra') ? 'menu-active' : '' }}">Búsqueda por Número</a>
        </div>
        
    </div>
</div>
