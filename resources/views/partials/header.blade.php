<!-- START TOPBAR -->
<div class='page-topbar '>
    <a href="{{url('/dashboard')}}">
        <div class='logo-area'>
        </div>
    </a>
    <div class='quick-area'>
        <div class='pull-left'>
            <ul class="info-menu left-links list-inline list-unstyled">
                <li class="sidebar-toggle-wrap">
                    <a href="javascript:void(0);" data-toggle="sidebar" class="sidebar_toggle">
                        <i class="fa fa-bars"></i>
                    </a>
                </li>

                <li class="hidden-sm hidden-xs searchform">
                    <form action="ui-search.html" method="post">
                        <div class="input-group">
                            <span class="input-group-addon">

                            </span>
                            <input type="text" class="form-control animated fadeIn" placeholder="Search & Enter">
                        </div>
                        <input type='submit' value="">
                    </form>
                </li>
            </ul>
        </div>		
        <div class='pull-right'>
            <ul class="info-menu right-links list-inline list-unstyled" style="margin-right: 11px;">
                <li class="profile">
                    <a href="#" data-toggle="dropdown" class="toggle">                        
                        {{-- <img src="{{asset('images/admin-profile.jpg')}}" alt="admin-image" class="img-circle img-inline" /> --}}
                        <span>Welcome Admin <i class="fa fa-angle-down"></i></span>
                    </a>
                    <ul class="dropdown-menu profile animated fadeIn">
                        <li>
                            <a href="{{ url('change-password') }}">
                                <i class="fa fa-wrench"></i>
                                Change Password
                            </a>
                        </li>
                        <li class="last">
                            <a href="{{ url('logout') }}">
                                <i class="fa fa-lock"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </li>                
            </ul>			
        </div>		
    </div>
</div>
<!-- END TOPBAR -->