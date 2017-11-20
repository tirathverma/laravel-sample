<!-- SIDEBAR - START -->

<div class="page-sidebar pagescroll">
    <!-- MAIN MENU - START -->
    <div class="page-sidebar-wrapper" id="main-menu-wrapper"> 
        <ul class='wraplist'>
            <li class='menusection'>
                Main
            </li>
            {{-- <li class="@if(Request::segment(2)== 'dashboard') open @endif"> 
			<a href="{{url('/dashboard')}}">
            <i class="fa fa-dashboard"></i>
            <span class="title">Dashboard</span>
            </a>
            </li> --}}

            <li class="@if(Request::segment(1)== 'invoices') open @endif"> 
                <a href="{{url('/invoices')}}">
                    <i class="fa fa-file"></i>
                    <span class="title">Invoices</span>
                </a>
            </li>	

            <li class="@if(Request::segment(1)== 'stocks') open @endif"> 
                <a href="{{url('/stocks')}}">
                    <i class="fa fa-hdd-o"></i>
                    <span class="title">Stocks</span>
                </a>
            </li>	

            <li class="@if(Request::segment(1)== 'customers') open @endif"> 
                <a href="{{url('/customers')}}">
                    <i class="fa fa-user"></i>
                    <span class="title">Customers</span>
                </a>
            </li>

            <li class="@if(Request::segment(1)== 'employee') open @endif"> 
                <a href="{{url('/employee')}}">
                    <i class="fa fa-suitcase"></i>
                    <span class="title">Employees</span>
                </a>
            </li>	


            <li class="@if(Request::segment(2)== 'sales') open @endif">
                <a href="{{ url('/reports/sales') }}" style="">
                    <i class="fa fa-file-text"></i>
                    <span class="title"> Sales Report</span></a>
            </li>

            <li class="@if(Request::segment(2)== 'customers') open @endif">
                <a href="{{ url('reports/customers') }}" style="">
                    <i class="fa fa-file"></i> 
                    <span class="title">Customer Report</span>
                </a>
            </li>

            <li class="@if(Request::segment(2)== 'stocks') open @endif">
                <a href="{{ url('/reports/stocks') }} " style="">
                    <i class="fa fa-file-o"></i> 
                    <span class="title"> Stock Holding Report</span>
                </a>
            </li>



            <li class="@if(Request::segment(2)== '1') open @endif"> 
                <a href="{{url('/settings/1/edit')}}">
                    <i class="fa fa-cogs"></i>
                    <span class="title">Settings</span>
                </a>
            </li>	


        </ul>

    </div>

</div>

