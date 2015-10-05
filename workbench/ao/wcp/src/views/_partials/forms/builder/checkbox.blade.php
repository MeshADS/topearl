<div class="myFormWizard">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs nav-tabs-linetriangle nav-tabs-separator">
        <li class="active">
            <a data-toggle="tab" href="#cNCBtab1"><i class="fa fa-info tab-icon"></i> <span>Basic Info</span></a>
        </li>
        <li class="">
            <a data-toggle="tab" href="#cNCBtab2"><i class="fa  fa-list tab-icon"></i> <span>List</span></a>
        </li>
        <li class="">
            <a data-toggle="tab" href="#cNCBtab3"><i class="fa fa-lock tab-icon"></i> <span>Rules</span></a>
        </li>
        <li class="">
            <a data-toggle="tab" href="#cNCBtab4"><i class="pg-grid tab-icon"></i> <span>Layout</span></a>
        </li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content" style="padding-left:0px; padding-right:0px;">
        <div class="tab-pane active slide" id="cNCBtab1">
         <div class="form-group form-group-default">
                {{ Form::label("name", "Name") }}
                <input type="text" name="name" class="form-control" placeholder="E.g. Newsletter Options, Car types, etc." value="" id="name">
            </div>
            <div class="form-group form-group-default form-group-attached">
                {{ Form::label("position", "Position") }}
                <input type="number" name="position" class="form-control" min="0" value="" id="position">
                <small>This determines where this element with appear on your form</small>
            </div>
            <div class="form-group form-group-default">
                {{ Form::label("group", "Group") }}
                <input type="text" name="group" class="form-control" placeholder="This will help group your elements on the form (Optional)" value="" id="group">
            </div>
        </div>
        <div class="tab-pane slide" id="cNCBtab2">
            <div class="panel panel-transparent">
                <div class="panel-body listValues" id="listvaluesNCB" style="padding:0px 0px;">
                  
                </div>

                <div class="panel-body" style="padding-left:0px; padding-right:0px;">
                    <p class="text-center" id="echeckboxModalHelperNCB">
                        <small>
                            Click the plus sign to add a new checkbox
                        </small>
                    </p>
                    <button type="button" class="btn btn-block btn-complete addListvalue" data-id="NCB" data-helper="#echeckboxModalHelperNCB" data-target="#listvaluesNCB" title="Add a checkbox">
                        <i class="fa fa-plus-circle"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="tab-pane slide" id="cNCBtab3">
            <div class="panel panel-transparent">
                <div class="panel-body" id="checkboxRules" style="padding:0px 0px;">
                    
                </div>

                <div class="panel-body" style="padding-left:0px; padding-right:0px;">
                    <div class="row p-r-35 m-t-10" id="checkbox|ID|">
                        <div class="col-md-12">
                            <div class="form-group form-group-default">
                                {{ Form::label("rules[]", "Required") }}
                                <input type="checkbox" name="rules[]" value="required">
                                <small><i>This element is required?</i></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane slide" id="cNCBtab4">
            <div class="panel panel-transparent">
                <div class="panel-body" style="padding-left:0px; padding-right:0px;">
                    <p>
                      <small>
                          <strong>
                              The selected layout will only be applied on PC/Large displays.
                          </strong>
                      </small>  
                    </p>
                    @foreach( $elementsizes as $k => $v)
                    <!-- Begin row -->
                        <div class="row">
                            <!-- Begin column -->
                            <div class="{{$k}}">
                                <div class="panel panel-default m-b-10">
                                    <div class="panel-body p-t-5 p-b-5">
                                        <div class="radio radio-success">
                                          <input type="radio" 
                                                 value="{{ $k }}" 
                                                 name="size" 
                                                 id="{{ $k }}cbx"
                                                 {{ ($v == 12) ? 'checked' : '' }}>
                                          <label for="{{ $k }}cbx">Size {{$v}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End column -->
                        </div>
                    <!-- End row -->
                    @endforeach
                </div>
            </div>
        </div>

        <ul class="pager wizard">
            <li class="next">
                <button class="btn btn-warning btn-cons pull-right" type="button">
                    <span>Next</span>
                </button>
            </li>
            <li class="next finish" style="display:none;">
                <button class="btn btn-success btn-cons pull-right" type="submit">
                    <span>Finish</span>
                </button>
            </li>
            <li class="previous first" style="display:none;">
                <button class="btn btn-white btn-cons pull-right" type="button">
                    <span>First</span>
                </button>
            </li>
            <li class="previous">
                <button class="btn btn-white btn-cons pull-right" type="button">
                    <span>Previous</span>
                </button>
            </li>
        </ul>
    </div>
</div>