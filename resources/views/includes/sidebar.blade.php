<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{asset('images/restaurant.svg' )}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Tukad Jangga Coffee</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
            @if(Auth::user()->hasRole('super admin|admin'))
            <a href="{{route('category.index')}}" class="nav-link {{(request()->is('*category')) ? 'active' : '' }}">
                <i class="fas fa-tasks"></i>
                <p>Category</p>
            </a>
            @endif
        </li>
        <li class="nav-item">
            @if(Auth::user()->hasRole('super admin|admin'))
            <a href="{{route('menu.index')}}" class="nav-link {{(request()->is('menu*')) ? 'active' : '' }}">
              <i class="fas fa-hamburger"></i>
                <p>Menu</p>
            </a>
            @endif
        </li>
        <li class="nav-item">
            @if(Auth::user()->hasRole('super admin|admin|cashier|members'))
            <a href="{{route('cashier.index')}}" class="nav-link {{(request()->is('cashier*')) ? 'active' : '' }}">
              <i class="fas fa-cash-register" style="margin-left: -0.15rem"></i>
                <p>@if(Auth::user()->hasRole('members')) Order @else Cashier @endif</p>
            </a>
            @endif
        </li>
        <li class="nav-item">
          @if(Auth::user()->hasRole('super admin|admin'))
          <a href="{{route('table.index')}}" class="nav-link {{(request()->is('*table')) ? 'active' : '' }}"><i class="fas fa-chair"></i>
              <p>Table</p>
          </a>
          @endif
        </li>
        <li class="nav-item">
          @if(Auth::user()->hasRole('super admin|admin'))
          <a href="{{route('voucher.index')}}" class="nav-link {{(request()->is('voucher*')) ? 'active' : '' }}"><i class="fas fa-tag"></i>
              <p>Voucher</p>
          </a>
          @endif
        </li>
        <li class="nav-item">
          @if(Auth::user()->hasRole('super admin|admin|cashier'))
          <a href="{{route('roombooking.index')}}" class="nav-link {{(request()->is('bookingroom*')) ? 'active' : '' }}"><i class="far fa-clone"></i>
              <p>Room's Booking</p>
          </a>
          @endif
        </li>
        <li class="nav-item">
          @if(Auth::user()->hasRole('super admin|admin|cashier'))
          <a href="{{route('kitchen.index')}}" class="nav-link {{(request()->is('kitchen*')) ? 'active' : '' }}"><i class="fas fa-utensils"></i>
              <p>Kitchen</p>
          </a>
          @endif
        </li>
        <li class="nav-item">
          @if(Auth::user()->hasRole('super admin|admin'))
          <a href="{{route('user.index')}}" class="nav-link {{(request()->is('*user')) ? 'active' : '' }}"><i class="fas fa-user-friends"></i>
              <p>Users</p>
          </a>
          @endif
        </li>

        <li class="nav-item">
          @if(Auth::user()->hasRole('super admin|admin'))
          <a href="{{route('inventory.index')}}" class="nav-link {{(request()->is('inventory*')) ? 'active' : '' }}"><i class="far fa-hdd"></i>
              <p>Inventory</p>
          </a>
          @endif
        </li>
        <li class="nav-item">
          @if(Auth::user()->hasRole('super admin|admin'))
          <a href="{{route('inventmenu.index')}}" class="nav-link {{(request()->is('inventmenu*')) ? 'active' : '' }}"><i class="far fa-hdd"></i>
            {{-- <i class="fas fa-truck-moving"></i> --}}
              <p>Inventory To Menu</p>
          </a>
          @endif
        </li>
        <li class="nav-item">
          @if(Auth::user()->hasRole('super admin|admin'))
          <a href="{{route('ppn.index')}}" class="nav-link {{(request()->is('ppn*')) ? 'active' : '' }}">
            <i class="fas fa-money-check-alt"></i>
              <p>PPN</p>
          </a>
          @endif
        </li>
        <li class="nav-item">
          @if(Auth::user()->hasRole('super admin|admin'))
          <a href="{{route('supplier.index')}}" class="nav-link {{(request()->is('*supplier')) ? 'active' : '' }}">
            <i class="fas fa-truck-moving"></i>
              <p>Purchase</p>
          </a>
          @endif
        </li>
        <li class="nav-item">
            @if(Auth::user()->hasRole('super admin|finance'))
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-chart-line" style="margin-left: -0.2rem"></i>
            <p style="margin-left: -0.4rem">
              Report
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              @if(Auth::user()->hasRole('super admin|finance'))
              <a href="{{route('report.index')}}" class="nav-link {{(request()->is('report*')) ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sale Report By Date</p>
              </a>
              @endif
            </li>
            <li class="nav-item">
              @if(Auth::user()->hasRole('super admin|finance'))
              <a href="{{route('report.indexmonth')}}" class="nav-link {{(request()->is('month*')) ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sale Report By Month</p>
              </a>
              @endif
            </li>
            <li class="nav-item">
              @if(Auth::user()->hasRole('super admin|finance'))
              <a href="{{route('report.employee')}}" class="nav-link {{(request()->is('employee*')) ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Employee Report</p>
              </a>
              @endif
            </li>
            <li class="nav-item">
                @if(Auth::user()->hasRole('super admin|finance'))
              <a href="{{route('purchase.index')}}" class="nav-link {{(request()->is('purchase*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Purchase Report</p>
              </a>
                @endif
            </li>
            <li class="nav-item">
                @if(Auth::user()->hasRole('super admin|finance'))
              <a href="{{route('customer.index')}}" class="nav-link {{(request()->is('customer*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Data Customers</p>
              </a>
                @endif
            </li>
            <li class="nav-item">
                @if(Auth::user()->hasRole('super admin|finance'))
              <a href="{{route('member.index')}}" class="nav-link {{(request()->is('member*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Members Customers</p>
              </a>
                @endif
            </li>
          </ul>
          @endif
        </li>
        </ul>
      </nav>
    </div>
  </aside>
