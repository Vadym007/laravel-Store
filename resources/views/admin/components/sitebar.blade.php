<section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
        <div class="pull-left image">
        <img src="/admin/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
        <p>{{$CurrentUser->getFullName()}}</p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
    </div>
    <!-- search form -->
    <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Search...">
            <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
            </span>
        </div>
    </form>
    <!-- /.search form -->
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu">
        <li class="header">Меню</li>
        <li class="active">
            <a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> <span>Админ-панель</span></a>
        </li>
        <li><a href="{{route('admin.imports.index')}}"><i class="fa fa-print"></i> <span>Импорт товаров</span></a></li>
        <li><a href="{{route('admin.products.index')}}"><i class="fa fa-cubes"></i> <span>Товары</span></a></li>
        <li><a href="{{route('admin.categories.index')}}"><i class="fa fa-list-ul"></i> <span>Категории</span></a></li>
        <li><a href="{{route('admin.tags.index')}}"><i class="fa fa-tags"></i> <span>Теги</span></a></li>
        <li class="treeview">
            <a href="#">
              <i class="fa fa-pie-chart"></i>
              <span>Характеристики</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu" style="display: none;">
              <li><a href="{{ route('admin.characteristics.colors.index') }}"><i class="fa fa-circle-o"></i> Цвета</a></li>
              <li><a href="{{ route('admin.characteristics.sizes.index') }}"><i class="fa fa-circle-o"></i> Размеры</a></li>
            </ul>
          </li>
        {{-- <li>
            <a href="#">
                <i class="fa fa-commenting"></i> <span>Комментарии</span>
                <span class="pull-right-container">
                    <small class="label pull-right bg-green">5</small>
                </span>
            </a>
        </li> --}}
        <li><a href="{{route('admin.providers.index')}}"><i class="fa fa-cart-arrow-down"></i> <span>Поставщики</span></a></li>
        <li><a href="{{route('admin.users.index')}}"><i class="fa fa-users"></i> <span>Пользователи</span></a></li>
        {{-- <li><a href="#"><i class="fa fa-user-plus"></i> <span>Подписчики</span></a></li> --}}
    </ul>
</section>
