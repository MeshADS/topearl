<div class="myFormWizard">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs nav-tabs-linetriangle nav-tabs-separator">
        <li class="active">
            <a data-toggle="tab" href="#rb{{$item->id}}tab1"><i class="fa fa-info tab-icon"></i> <span>Basic Info</span></a>
        </li>
        <li class="">
            <a data-toggle="tab" href="#rb{{$item->id}}tab2"><i class="fa  fa-list tab-icon"></i> <span>List</span></a>
        </li>
        <li class="">
            <a data-toggle="tab" href="#rb{{$item->id}}tab3"><i class="fa fa-lock tab-icon"></i> <span>Rules</span></a>
        </li>
        <li class="">
            <a data-toggle="tab" href="#rb{{$item->id}}tab4"><i class="pg-grid tab-icon"></i> <span>Layout</span></a>
        </li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content" style="padding-left:0px; padding-right:0px;">
        <div class="tab-pane active slide" id="rb{{$item->id}}tab1">
         <div class="form-group form-group-default">
                {{ Form::label("name", "Name") }}
                <input type="text" name="name" class="form-control" placeholder="E.g. Newsletter Options, Car types, etc." value="{{ $item->name }}" id="name" required>
            </div>
            <div class="form-group form-group-default form-group-attached">
                {{ Form::label("position", "Position") }}
                <input type="number" name="position" class="form-control" min="0" value="{{ $item->position }}" id="position" required>
                <small>This determines where this element with appear on your form</small>
            </div>
            <div class="form-group form-group-default">
                {{ Form::label("group", "Group") }}
                <input type="text" name="group" class="form-control" placeholder="This will help group your elements on the form (Optional)" value="{{ $item->groupie }}" id="group" required>
            </div>
        </div>
        <div class="tab-pane slide" id="rb{{$item->id}}tab2">
            <div class="panel panel-transparent">
                <div class="panel-body listValues" id="listvalues{{ $item->id }}" style="padding:0px 0px;">
                    @foreach($item->listValues as $lv)
                        <div class="row p-r-35 m-t-10 listvalues" id="eradiobutton{{ $item->id }}-{{ $lv->id }}">
                            <div class="col-md-6">
                                <div class="form-group form-group-default">
                                    <label for="list">
                                        Name
                                    </label>
                                    <input type="text" name="olist[]" class="form-control" value="{{$lv->name}}" placeholder="List item name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-group-default">
                                    <label for="value">
                                        Value
                                    </label>
                                    <input type="text" name="ovalue[]" class="form-control" value="{{ $lv->value }}" placeholder="List item value">
                                </div>
                                <input type="hidden" name="oid[]" value="{{$lv->id}}">
                                <input type="hidden" name="delete[]" id="deleteThis" value="0">
                            </div>
                            <a href="javascript:;" title="Delete!" class="new-element-dl-btn remove-olistvalue" data-target="#eradiobutton{{ $item->id }}-{{ $lv->id }}">
                                <i class="fa fa-times"></i>
                            </a>
                            <div class="delMessage p-l-15 p-r-15 p-t-20">
                                This option will be deleted after you save changes.
                                <a href="#" title="Cancel this action" class="cancel-remove-olistvalue" data-target="#eradiobutton{{ $item->id }}-{{ $lv->id }}">[Cancel]</a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="panel-body" style="padding-left:0px; padding-right:0px;">
                    <p class="text-center" id="radiobuttonModalHelper{{$item->id}}" style="{{ (count($item->listValues) > 0) ? 'display:none;' :'' }}">
                        <small>
                            Click the plus sign to add a new radio button
                        </small>
                    </p>
                    <button type="button" class="btn btn-block btn-complete addListvalue" data-id="{{ $item->id }}" data-helper="#radiobuttonModalHelper{{$item->id}}" data-target="#listvalues{{ $item->id }}" title="Add a radio button">
                        <i class="fa fa-plus-circle"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="tab-pane slide" id="rb{{$item->id}}tab3">
             <div class="panel panel-transparent">
                <div class="panel-body" id="radiobuttonRules" style="padding:0px 0px;">
                    
                </div>

                <div class="panel-body" style="padding-left:0px; padding-right:0px;">
                    <div class="row p-r-35 m-t-10" id="radiobutton|ID|">
                        <div class="col-md-12">
                            <div class="form-group form-group-default">
                                {{ Form::label("rules[]", "Required") }}
                                <?php $item->rules = unserialize($item->rules); $reqired = 0; ?>
                                @foreach( $item->rules as $rule )
                                    <?php if($rule == "required") { $reqired++; } ?>
                                @endforeach
                                <input type="checkbox" name="rules[]" value="required" {{ ($reqired > 0) ? 'checked' : ''  }}>
                                <small><i>This element is required?</i></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane slide" id="rb{{$item->id}}tab4">
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
                                                 id="{{ $k.$item->id }}"
                                                 {{ ($k == $item->size) ? 'checked' : '' }}>
                                          <label for="{{ $k.$item->id }}">Size {{$v}}</label>
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