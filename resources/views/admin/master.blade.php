<!DOCTYPE html>
<html lang="en">
@include('admin.includes.head')
<body>
    @include('admin.includes.leftsidebar')
    <div class="main-content">
        @include('admin.includes.header')
        <div class="page-content-wrap">
        <!-- Container Fluid-->
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            @include('admin.includes.footer')
        </div>

    </div>
@include('admin.includes.logout_modal')
@include('admin.includes.script')
@include('sweetalert::alert')
</body>
</html>
