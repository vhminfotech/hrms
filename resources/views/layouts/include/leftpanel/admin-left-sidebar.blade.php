@php

$currentRoute = Route::current()->getName();

$session = Session::all();
$roles = Session::get('userRole');
$roles  = array_values($roles);
if (!empty(Auth()->guard('admin')->user())) {
$data = Auth()->guard('admin')->user();
}
if (!empty(Auth()->guard('company')->user())) {
$data = Auth()->guard('company')->user();
}
if (!empty(Auth()->guard('employee')->user())) {
$data = Auth()->guard('employee')->user();
}

$filename= url('uploads/client/'.$data['user_image']);
$file_headers = @get_headers($filename);

@endphp
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element"> 
                    <span>
                        @if($data['user_image'] != '' || $data['user_image'] != NULL)
                            <img class="img-circle" width="50" src="{{ asset('uploads/client/'.$data['user_image']) }}" alt="User's Profile Picture">
                        @else
                            <img class="img-circle" width="50" src="{{ asset('img/profile_small.jpg') }}" alt="User's Profile Picture">
                        @endif
                    </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="clear"> <span class="text-muted text-xs block"><strong class="font-bold"> {{ $data['name'] }} </strong> <b class="caret"></b></span> </span> </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="{{ route('update-profile') }}">Update Profile</a></li>
                        <li><a href="{{ route('change-password') }}">Change Password</a></li>
                        <li class="divider"></li>
                        <li><a href="{{ route('logout') }}">Logout</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    HRMS
                </div>
            </li>
            <li class="{{ ($currentRoute == 'admin-calendar' ? 'active' : '') }}">
                <a href="{{ route('admin-calendar') }}"><i class="fa fa-calendar"></i>
                    <span class="nav-label">Calendar</span></a>
            </li>
            @if($roles == NULL)
            <li class="{{ ($currentRoute == 'admin-dashboard'  ? 'active' : '') }}">
                <a href="{{ route('admin-dashboard') }}"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span> </a>
               
            </li>
            
            <li class="{{ ($currentRoute == 'list-demo'  ? 'active' : '') }}">
                <a href="{{ route('list-demo') }}"><i class="fa fa-th-large"></i> <span class="nav-label">Demo</span></a>
            </li>
            
            
            <li class="{{ ($currentRoute == 'list-company'  ? 'active' : '') }} {{ ($currentRoute == 'add-company'  ? 'active' : '') }} {{ ($currentRoute == 'edit-company'  ? 'active' : '') }}">
                <a href="{{ route('list-company') }}"><i class="fa fa-industry"></i> <span class="nav-label">Company</span></a>
            </li> 
            
            
<!--            <li class="{{ ($currentRoute == 'list-cmspage'  ? 'active' : '') }} {{ ($currentRoute == 'edit-cmspage'  ? 'active' : '') }} {{ ($currentRoute == 'edit-company'  ? 'active' : '') }}">
                <a href="{{ route('list-cmspage') }}"><i class="fa fa-industry"></i> <span class="nav-label">CMS Page</span></a>
            </li>-->

            <!--            <li class="{{ ($currentRoute == 'edit-email' || $currentRoute == 'add-email' || $currentRoute == 'list-email'  ? 'active' : '') }} ">
                            <a href="{{ route('list-email') }}"><i class="fa fa-envelope-o"></i> <span class="nav-label">Email</span></a>
                        </li>-->

            <li class="{{ ($currentRoute == 'setting'   ? 'active' : '') }} ">
                <a href="{{ route('setting') }}"><i class="fa fa-cog" ></i> <span class="nav-label">Setting</span></a>
            </li>

            <li class="{{ ($currentRoute == 'set-tax' ? 'active' : '') }}">
                <a href="{{ route('set-tax') }}"><i class="fa fa-percent"></i>
                    <span class="nav-label">Set Tax</span></a>
            </li>


            <!--                <li class="{{ ($currentRoute == 'order-list' ? 'active' : '') }}">
                                <a href="{{ route('order-list') }}"><i class="fa fa-first-order"></i>
                                <span class="nav-label">Order</span></a>
                            </li>-->

            <li class="{{ ($currentRoute == 'order-list' || $currentRoute == 'order-approved-list' ? 'active' : '') }}">
                <a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">Order</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="{{ ($currentRoute == 'order-list' ? 'active' : '') }}">
                        <a href="{{ route('order-list') }}"><i class="fa fa-money" ></i> <span class="nav-label">Order List</span></a>
                    </li>
                    <li class="{{ ($currentRoute == 'order-approved-list' ? 'active' : '') }}">
                        <a href="{{ route('order-approved-list') }}"><i class="fa fa-money" ></i> <span class="nav-label">Order Approved List</span></a>
                    </li>
                </ul>
            </li>
            
            <li class="{{ ($currentRoute == 'admin-communication' ? 'active' : '') }} {{ ($currentRoute == 'admin-sms-list' ? 'active' : '') }} {{ ($currentRoute == 'compose' ? 'active' : '') }} {{ ($currentRoute == 'mail-detail' ? 'active' : '') }} {{ ($currentRoute == 'mail-detail/*' ? 'active' : '') }} {{ ($currentRoute == 'communication' || $currentRoute == 'admin-chat' ? 'active' : '') }} {{ ($currentRoute == 'communication' || $currentRoute == 'new-sms' || $currentRoute == 'send-mail' ? 'active' : '') }}">
                <a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">Communication</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="{{ ($currentRoute == 'admin-communication' ? 'active' : '') }} {{ ($currentRoute == 'compose' ? 'active' : '') }} {{ ($currentRoute == 'mail-detail' ? 'active' : '') }} {{ ($currentRoute == 'mail-detail/*' ? 'active' : '') }} {{ ($currentRoute == 'send-mail' ? 'active' : '') }}">
                        <a href="{{ route('admin-communication') }}"><i class="fa fa-history"></i>
                            <span class="nav-label">Email</span></a>
                    </li>
                    
                    <li class="{{ ($currentRoute == 'admin-sms-list' ? 'active' : '') }} {{ ($currentRoute == 'new-sms' ? 'active' : '') }}">
                        <a href="{{ route('admin-sms-list') }}"><i class="fa fa-envelope"></i>
                            <span class="nav-label">Send SMS</span></a>
                    </li>
                    
                    <li class="{{ ($currentRoute == 'admin-chat' ? 'active' : '') }}">
                        <a href="{{ route('admin-chat') }}"><i class="fa fa-comments"></i>
                            <span class="nav-label">Chat</span></a>
                    </li>
                </ul>
            </li>

            <li class="{{ ($currentRoute == 'payment-list' ? 'active' : '') }}">
                <a href="{{ route('payment-list') }}"><i class="fa fa-percent"></i>
                    <span class="nav-label">Payment</span></a>
            </li>

            <li class="{{ ($currentRoute == 'plan-management' || $currentRoute == 'add-plan' || $currentRoute == 'plan_management-edit' ? 'active' : '') }}">
                <a href="{{ route('plan-management') }}"><i class="fa fa-percent"></i>
                    <span class="nav-label">Plan Management</span></a>
            </li> 
            
            <li class="{{ ($currentRoute == 'admin-notification' ? 'active' : '') }}">
                <a href="{{ route('admin-notification') }}"><i class="fa fa-bell"></i>
                    <span class="nav-label">Notification</span></a>
            </li> 
            
            <li class="{{ ($currentRoute == 'add-role' || $currentRoute == 'edit-role' || $currentRoute == 'role-list' ? 'active' : '') }}">
                <a href="{{ route('role-list') }}"><i class="fa  fa-american-sign-language-interpreting"></i>
                    <span class="nav-label">Role</span></a>
            </li>
            
            

             <li class="{{ ($currentRoute == 'social-media'   ? 'active' : '') }} ">
                <a href="{{ url('').'/admin/social-media' }}"><i class="fa fa-comments"></i>
                    <span class="nav-label">Social Media</span></a>
            </li> 
            <li class="{{ ($currentRoute == 'admin-report-list'   ? 'active' : '') }} ">
        <a href="{{ route('admin-report-list') }}"><i class="fa fa-comments"></i>
            <span class="nav-label">Report</span></a>
    </li>
    
        @else
            <li class="{{ ($currentRoute == 'admin-dashboard'  ? 'active' : '') }}">
                <a href="{{ route('admin-dashboard') }}"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span> </a>
               
            </li>
            @if(in_array(6, $roles))
                <li class="{{ ($currentRoute == 'list-cmspage'  ? 'active' : '') }} {{ ($currentRoute == 'edit-cmspage'  ? 'active' : '') }} {{ ($currentRoute == 'edit-company'  ? 'active' : '') }}">
                    <a href="{{ route('list-cmspage') }}"><i class="fa fa-industry"></i> <span class="nav-label">CMS Page</span></a>
                </li>
            @endif
            
            @if(in_array(7, $roles))
                <li class="{{ ($currentRoute == 'list-company'  ? 'active' : '') }} {{ ($currentRoute == 'add-company'  ? 'active' : '') }} {{ ($currentRoute == 'edit-company'  ? 'active' : '') }}">
                    <a href="{{ route('list-company') }}"><i class="fa fa-industry"></i> <span class="nav-label">Company</span></a>
                </li> 
            @endif
            
            
            @if(in_array(9, $roles))
                 <li class="{{ ($currentRoute == 'sms-list' ? 'active' : '') }} {{ ($currentRoute == 'new-sms' ? 'active' : '') }}">
                <a href="{{ url('').'/admin/sms-list' }}"><i class="fa fa-envelope"></i>
                    <span class="nav-label">Send SMS</span></a>
            </li> 
            @endif
            
            @if(in_array(8, $roles))
                 <li class="{{ ($currentRoute == 'communication' ? 'active' : '') }} {{ ($currentRoute == 'compose' ? 'active' : '') }} {{ ($currentRoute == 'mail-detail' ? 'active' : '') }} {{ ($currentRoute == 'mail-detail/*' ? 'active' : '') }}">
                    <a href="{{ url('').'/admin/communication' }}"><i class="fa fa-history"></i>
                        <span class="nav-label">Communication</span></a>
                </li>
            @endif
            
                       
            
            @if(in_array(11, $roles))
                <li class="{{ ($currentRoute == 'social-media'   ? 'active' : '') }} ">
                    <a href="{{ url('').'/admin/social-media' }}"><i class="fa fa-comments"></i>
                        <span class="nav-label">Social Media</span></a>
                </li> 
            @endif
        @endif
        

</ul>

</div>
</nav>