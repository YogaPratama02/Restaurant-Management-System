<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    {{-- <a href="index3.html" class="brand-link">
      <img src="{{url('/dashboard/dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a> --}}

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{url('/dashboard/dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">The Proffesor's Caffe</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
            @if(Auth::user()->checkAdmin())
            <a href="/management/category" class="nav-link {{(request()->is('management/category')) ? 'active' : '' }}">
                <i class="fas fa-tasks"></i>
                <p>Category</p>
            </a>
            @endif
        </li>
        <li class="nav-item">
            @if(Auth::user()->checkAdmin())
            <a href="/management/menu" class="nav-link {{(request()->is('management/menu')) ? 'active' : '' }}">
              <i class="fas fa-hamburger"></i>
                <p>Menu</p>
            </a>
            @endif
        </li>
        <li class="nav-item">
            <a href="{{route('cashier.index')}}" class="nav-link {{(request()->is('cashier*')) ? 'active' : '' }}">
              <i class="fas fa-cash-register" style="margin-left: -0.15rem"></i>
                {{-- <img width="18px" src="{{asset('images/cashier.svg')}}" style="background: white"> --}}
                <p>Cashier</p>
            </a>
        </li>
        <li class="nav-item">
          @if(Auth::user()->checkAdmin())
          <a href="/management/table" class="nav-link {{(request()->is('management/table')) ? 'active' : '' }}"><i class="fas fa-chair"></i>
              <p>Table</p>
          </a>
          @endif
        </li>
        <li class="nav-item">
          @if(Auth::user()->checkAdmin())
          <a href="{{route('roombooking.index')}}" class="nav-link {{(request()->is('bookingroom*')) ? 'active' : '' }}"><i class="far fa-clone"></i>
              <p>Room's Booking</p>
          </a>
          @endif
        </li>
        <li class="nav-item">
          @if(Auth::user()->checkAdmin())
          <a href="{{route('kitchen.index')}}" class="nav-link {{(request()->is('kitchen*')) ? 'active' : '' }}"><i class="fas fa-utensils"></i>
              <p>Kitchen</p>
          </a>
          @endif
        </li>
        <li class="nav-item">
          @if(Auth::user()->checkAdmin())
          <a href="/management/user" class="nav-link {{(request()->is('management/user')) ? 'active' : '' }}"><i class="fas fa-user-friends"></i>
              <p>Users</p>
          </a>
          @endif
        </li>
        <li class="nav-item">
          @if(Auth::user()->checkAdmin())
          <a href="{{route('inventory.index')}}" class="nav-link {{(request()->is('inventory*')) ? 'active' : '' }}"><i class="far fa-hdd"></i>
              <p>Inventory</p>
          </a>
          @endif
        </li>
        <li class="nav-item">
          @if(Auth::user()->checkAdmin())
          <a href="{{route('inventmenu.index')}}" class="nav-link {{(request()->is('inventmenu*')) ? 'active' : '' }}"><i class="far fa-hdd"></i>
            {{-- <i class="fas fa-truck-moving"></i> --}}
              <p>Inventory To Menu</p>
          </a>
          @endif
        </li>
        <li class="nav-item">
          @if(Auth::user()->checkAdmin())
          <a href="{{route('ppn.index')}}" class="nav-link {{(request()->is('ppn*')) ? 'active' : '' }}">
            <i class="fas fa-money-check-alt"></i>
              <p>VAT</p>
          </a>
          @endif
        </li>
        <li class="nav-item">
          @if(Auth::user()->checkAdmin())
          <a href="{{route('supplier.index')}}" class="nav-link {{(request()->is('')) ? 'active' : '' }}">
            <i class="fas fa-truck-moving"></i>
              <p>Purchase</p>
          </a>
          @endif
        </li>
        <li class="nav-item">
            @if(Auth::user()->checkAdmin())
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-chart-line" style="margin-left: -0.2rem"></i>
            <p style="margin-left: -0.4rem">
              Report
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              @if(Auth::user()->checkAdmin())
              <a href="{{route('report.index')}}" class="nav-link {{(request()->is('report*')) ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Income Daily Report</p>
              </a>
              @endif
            </li>
            <li class="nav-item">
              @if(Auth::user()->checkAdmin())
              <a href="{{route('report.month')}}" class="nav-link {{(request()->is('month*')) ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Income Month Report</p>
              </a>
              @endif
            </li>
            <li class="nav-item">
              @if(Auth::user()->checkAdmin())
              <a href="{{route('report.employee')}}" class="nav-link {{(request()->is('employee*')) ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Employee Report</p>
              </a>
              @endif
            </li>
            <li class="nav-item">
              <a href="{{route('purchase.index')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Purchase Report</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="pages/charts/uplot.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>uPlot</p>
              </a>
            </li>
          </ul>
          @endif
        </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
