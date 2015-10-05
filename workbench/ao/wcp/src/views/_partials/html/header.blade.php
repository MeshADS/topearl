<!-- START HEADER -->
<div class="header ">
  <!-- START MOBILE CONTROLS -->
  <!-- LEFT SIDE -->
  <div class="pull-left full-height visible-sm visible-xs">
    <!-- START ACTION BAR -->
    <div class="sm-action-bar">
      <a href="#" class="btn-link toggle-sidebar" data-toggle="sidebar">
        <span class="icon-set menu-hambuger"></span>
      </a>
    </div>
    <!-- END ACTION BAR -->
  </div>
  <!-- RIGHT SIDE -->
  <div class="pull-right full-height visible-sm visible-xs">
    <!-- START ACTION BAR -->
    <div class="sm-action-bar">
      <a href="#" class="btn-link" data-toggle="quickview" data-toggle-element="#quickview">
        <span class="icon-set menu-hambuger-plus"></span>
      </a>
    </div>
    <!-- END ACTION BAR -->
  </div>
  <!-- END MOBILE CONTROLS -->
  <div class=" pull-left sm-table">
    <div class="header-inner">
      <div class="brand inline">
        <img src="{{ URL::to($basic_info->logo) }}" style="width:50px;" alt="logo" data-src="{{ URL::to($basic_info->logo) }}" data-src-retina="{{ URL::to($basic_info->logo_2x) }}">
      </div>
      
    </div>
  </div>
  <div class=" pull-right">
    <!-- START User Info-->
    <div class="visible-lg visible-md m-t-10">
      <div class="pull-left p-r-10 p-t-10 fs-16 font-heading">
        @if(!is_null($user_data->first_name))
          <span class="semi-bold">{{$user_data->first_name}}</span> <span class="text-master">{{ $user_data->last_name }}</span>
        @else
          <span class="semi-bold">{{$user_data->name}}</span>
        @endif
      </div>
      <div class="thumbnail-wrapper d32 circular inline m-t-5">
        <img src="{{ (!is_null($user_data->avatar)) ? URL::to($user_data->avatar) : URL::to('assets/wcp/img/profiles/avatar.jpg') }}" alt="" data-src="{{ (!is_null($user_data->avatar)) ? URL::to($user_data->avatar) : URL::to('assets/wcp/img/profiles/avatar.jpg') }}" data-src-retina="{{ (!is_null($user_data->avatar)) ? URL::to($user_data->avatar) : URL::to('assets/wcp/img/profiles/avatar.jpg') }}" width="32" height="32">
      </div>
    </div>
    <!-- END User Info-->
  </div>
</div>
<!-- Begin Modals -->
  {{-- Begin Password Account Modal --}}
    <div class="modal fade slide-down disable-scroll" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">

      <div class="modal-dialog">

        <div class="modal-content">
          {{ Form::open(["url"=>"admin/accounts/".$user_data->id."/change_password", "role"=>"form", "method"=>"put"]) }}
            <div class="modal-header">
              <button class="close" data-dismiss="modal" aria-label="close">
                <span aria-hidden="true"><i class="fa fa-times"></i></span>
              </button>
              <h4 class="modal-title">
                Change Password
              </h4>
            </div>

            <div class="modal-body">

              <div class="form-group form-group-default">
                {{ Form::label("password", "Password") }}
                <input type="password" name="password" class="form-control" id="password" placeholder="&#149;&#149;&#149;&#149;&#149;&#149;&#149;&#149;">
              </div>

              <div class="form-group form-group-default">
                {{ Form::label("password_confirmation", "Repeat Password") }}
                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="&#149;&#149;&#149;&#149;&#149;&#149;&#149;&#149;">
              </div>

            </div>

            <div class="modal-footer">
              <button type="submit" class="btn btn-warning pull-right">
                Change
              </button>
              <a href="#" class="btn btn-default pull-right m-r-10" data-dismiss="modal">
                Cancel
              </a>
            </div>
          {{ Form::close() }}

        </div>

      </div>

    </div>
  {{-- End Password Account Modal --}}
  {{-- Begin Edit Account Modal --}}
    <div class="modal fade slide-down disable-scroll" id="myAccountModal" tabindex="-1" role="dialog" aria-labelledby="myAccountModalLabel" aria-hidden="true">

      <div class="modal-dialog">

        <div class="modal-content">
          {{ Form::open(["url"=>"admin/accounts/".$user_data->id, "role"=>"form", "method"=>"put"]) }}
            <div class="modal-header">
              <button class="close" data-dismiss="modal" aria-label="close">
                <span aria-hidden="true"><i class="fa fa-times"></i></span>
              </button>
              <h4 class="modal-title">
                My Account
              </h4>
            </div>

            <div class="modal-body">

              <div class="form-group form-group-default">
                {{ Form::label("first_name", "First Name") }}
                <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Enter first name" value="{{ $user_data->first_name }}">
              </div>

              <div class="form-group form-group-default">
                {{ Form::label("last_name", "Last Name") }}
                <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Enter last name" value="{{ $user_data->last_name }}">
              </div>

            </div>

            <div class="modal-footer">
              <input type="hidden" name="email" value="{{ $user_data->email }}">
              <input type="hidden" name="group" value="{{ $user_data->groups[0]->id }}">
              <button type="submit" class="btn btn-warning pull-right">
                Update
              </button>
              <a href="#" class="btn btn-default pull-right m-r-10" data-dismiss="modal">
                Close
              </a>
            </div>
          {{ Form::close() }}

        </div>

      </div>

    </div>
  {{-- End Edit Account Modal --}}
<!-- End Modals -->
<!-- END HEADER -->