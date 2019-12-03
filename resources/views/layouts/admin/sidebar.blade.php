=============================================== -->

<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
<!--             <div class="pull-left image">
                <img src="{{ asset('img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
            </div> -->
            <!-- <div class="pull-left info">
                <p>{{ $user->name }}</p>
            </div> -->
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <!-- <li class="header">Inicio</li> -->
            <!-- <li><a href="{{ route('admin.dashboard') }}"> <i class="fa fa-home"></i> <span>Inicio</span></a></li> -->
            <li class="header">Vendas</li>
            <li class="treeview @if(request()->segment(2) == 'products' || request()->segment(2) == 'attributes' || request()->segment(2) == 'brands') active @endif">
                <a href="#">
                    <i class="fa fa-gift"></i> <span>Produtos</span>
                    <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    @if($user->hasPermission('view-product'))<li><a href="{{ route('admin.products.index') }}"><i class="fa fa-circle-o"></i> Listar produtos</a></li>@endif
                    @if($user->hasPermission('create-product'))<li><a href="{{ route('admin.products.create') }}"><i class="fa fa-plus"></i> Criar produtos</a></li>@endif
                    <!-- <li class="@if(request()->segment(2) == 'attributes') active @endif">
                    <a href="#">
                        <i class="fa fa-gear"></i> <span>Attributes</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('admin.attributes.index') }}"><i class="fa fa-circle-o"></i> List attributes</a></li>
                        <li><a href="{{ route('admin.attributes.create') }}"><i class="fa fa-plus"></i> Create attribute</a></li>
                    </ul>
                    </li>
                    <li class="@if(request()->segment(2) == 'brands') active @endif">
                    <a href="#">
                        <i class="fa fa-tag"></i> <span>Brands</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('admin.brands.index') }}"><i class="fa fa-circle-o"></i> List brands</a></li>
                        <li><a href="{{ route('admin.brands.create') }}"><i class="fa fa-plus"></i> Create brand</a></li>
                    </ul>
                    </li> -->
                </ul>
            </li>
            <li class="treeview @if(request()->segment(2) == 'categories') active @endif">
                <a href="#">
                    <i class="fa fa-folder"></i> <span>Categorias</span>
                    <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.categories.index') }}"><i class="fa fa-circle-o"></i> List categorias</a></li>
                    <li><a href="{{ route('admin.categories.create') }}"><i class="fa fa-plus"></i> Criar categoria</a></li>
                </ul>
            </li>
            <li class="treeview @if(request()->segment(2) == 'customers' || request()->segment(2) == 'addresses') active @endif">
                <a href="#">
                    <i class="fa fa-user"></i> <span>Clientes</span>
                    <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.customers.index') }}"><i class="fa fa-circle-o"></i> Listar clientes</a></li>
                    <li><a href="{{ route('admin.customers.create') }}"><i class="fa fa-plus"></i> Create customer</a></li>
                    <!-- <li class="@if(request()->segment(2) == 'addresses') active @endif">
                        <a href="#"><i class="fa fa-map-marker"></i> Endereço
                            <span class="pull-right-container">
                              <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('admin.addresses.index') }}"><i class="fa fa-circle-o"></i> Listar Endereço</a></li>
                            <li><a href="{{ route('admin.addresses.create') }}"><i class="fa fa-plus"></i> Criar Endereço</a></li>
                        </ul>
                    </li> -->
                </ul>
            </li>
             <li class="treeview @if(request()->segment(2) == 'customers' || request()->segment(2) == 'addresses') active @endif">
                <a href="#">
                    <i class="fa fa-plus-square"></i> <span>Anúncios</span>
                    <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.offers.index') }}"><i class="fa fa-circle-o"></i> Listar anúncios</a></li>
                    <li><a href="{{ route('admin.offers.create') }}"><i class="fa fa-plus"></i> Criar anúncio</a></li>
                </ul>
            </li>
            <li class="header">Pedidos</li>
            <li class="treeview @if(request()->segment(2) == 'orders') active @endif">
                <a href="#">
                    <i class="fa fa-money"></i> <span>Pedidos</span>
                    <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.orders.index') }}"><i class="fa fa-circle-o"></i> Listar pedidos</a></li>
                </ul>
            </li>
           <!--  <li class="treeview @if(request()->segment(2) == 'order-statuses') active @endif">
                <a href="#">
                    <i class="fa fa-anchor"></i> <span>status do pedido</span>
                    <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.order-statuses.index') }}"><i class="fa fa-circle-o"></i> Listar status do pedido</a></li>
                    <li><a href="{{ route('admin.order-statuses.create') }}"><i class="fa fa-plus"></i> Criar status do pedido</a></li>
                </ul>
            </li>
            <li class="header">Delivery</li>
            <li class="treeview @if(request()->segment(2) == 'couriers') active @endif">
                <a href="#">
                    <i class="fa fa-truck"></i> <span>Couriers</span>
                    <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.couriers.index') }}"><i class="fa fa-circle-o"></i> List couriers</a></li>
                    <li><a href="{{ route('admin.couriers.create') }}"><i class="fa fa-plus"></i> Create courier</a></li>
                </ul>
            </li> -->
           <!--  <li class="header">CONFIGURAÇÕES</li>
            @if($user->hasRole('admin|superadmin'))
                <li class="treeview @if(request()->segment(2) == 'employees' || request()->segment(2) == 'roles' || request()->segment(2) == 'permissions') active @endif">
            <a href="#">
                <i class="fa fa-star"></i> <span>Funcionários</span>
                <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="{{ route('admin.employees.index') }}"><i class="fa fa-circle-o"></i> Listar Funcionários</a></li>
                <li><a href="{{ route('admin.employees.create') }}"><i class="fa fa-plus"></i> Criar Funcionários</a></li>
                <li class="@if(request()->segment(2) == 'roles') active @endif">
                    <a href="#">
                        <i class="fa fa-star-o"></i> <span>Atribuições</span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('admin.roles.index') }}"><i class="fa fa-circle-o"></i> Listar atribuições</a></li>
                    </ul>
                </li>
                <li class="@if(request()->segment(2) == 'permissions') active @endif">
                    <a href="#">
                        <i class="fa fa-star-o"></i> <span>Permissão</span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('admin.permissions.index') }}"><i class="fa fa-circle-o"></i> Listar permissão</a></li>
                    </ul>
                </li>
            </ul>
        </li>
            @endif -->
           <!--  <li class="treeview @if(request()->segment(2) == 'countries' || request()->segment(2) == 'provinces') active @endif">
                <a href="#">
                    <i class="fa fa-flag"></i> <span>Países</span>
                    <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.countries.index') }}"><i class="fa fa-circle-o"></i> Lista</a></li>
                </ul>
            </li> -->
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

<!-- ===============================================