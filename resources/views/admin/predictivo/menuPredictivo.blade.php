<div>
    <p class="text-white-light mb-0 px-4 pt-4 font-13 py-3">Elastix</p>
    <div class="menu-option">
        <a href="#submenuPredictivo" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseExample" class="d-flex justify-content-between px-4 waves-effect">
            <div>
                <i class="fa fa-headset pr-2"></i>Predictivo
            </div>
            <i class="fa fa-angle-down pt-1"></i>
        </a>
        <div class="collapse" id="submenuPredictivo" class="bg-blue-bold">
            <a href="{{route('crearpredictivo')}}" class="bg-blue-bold text- px-5 waves-effect {{ request()->is('crearpredictivo') ? 'menu-active' : '' }}">Crear Campaña</a>
            <a href="{{route('campanaspredictivo')}}" class="bg-blue-bold text- px-5 waves-effect {{ request()->is('campanaspredictivo') ? 'menu-active' : '' }}">Campañas</a>
        </div>
        <a href="{{route('crearivr')}}" class="px-4 waves-effect {{ request()->is('crearivr') ? 'menu-active' : '' }}"><i class="fa fa-microphone-alt pr-2"></i>IVR</a>

        <!-- <a href="#submenuIvr" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseExample" class="d-flex justify-content-between px-4 waves-effect">
            <div>
                <i class="fa fa-microphone-alt pr-2"></i>IVR
            </div>
            <i class="fa fa-angle-down pt-1"></i>
        </a>
        <div class="collapse" id="submenuIvr" class="bg-blue-bold">
            <a href="{{route('crearivr')}}" class="bg-blue-bold text- px-5 waves-effect {{ request()->is('crearivr') ? 'menu-active' : '' }}">Crear Campaña</a>
            <a href="{{route('campanasivr')}}" class="bg-blue-bold text- px-5 waves-effect {{ request()->is('campanasivr') ? 'menu-active' : '' }}">Campañas</a>
        </div> -->
    </div>
</div>
