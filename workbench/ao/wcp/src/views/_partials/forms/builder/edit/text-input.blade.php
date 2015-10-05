<div class="myFormWizard">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs nav-tabs-linetriangle nav-tabs-separator">
        <li class="active">
            <a data-toggle="tab" href="#ti{{$item->id}}tab1"><i class="fa fa-info tab-icon"></i> <span>Basic Info</span></a>
        </li>
        <li class="">
            <a data-toggle="tab" href="#ti{{$item->id}}tab2"><i class="fa fa-lock tab-icon"></i> <span>Rules</span></a>
        </li>
        <li class="">
            <a data-toggle="tab" href="#ti{{$item->id}}tab4"><i class="pg-grid tab-icon"></i> <span>Layout</span></a>
        </li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content" style="padding-left:0px; padding-right:0px;">
        <div class="tab-pane active slide" id="ti{{$item->id}}tab1">
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
        <div class="tab-pane slide" id="ti{{$item->id}}tab2">
             <div class="panel panel-transparent">
                <div class="panel-body" id="checkboxRules" style="padding:0px 0px;">
                    
                </div>

                <div class="panel-body" style="padding-left:0px; padding-right:0px;">
                    <?php 
                        $item->rules = unserialize($item->rules); 
                        $is_reqired = 0; 
                        $is_url = 0; 
                        $is_email = 0; 
                        $is_date = 0; 
                        $is_number = 0; 
                        $is_digit = 0; 
                    ?>
                    @foreach( $item->rules as $rule )
                        <?php if($rule == "required") { $is_reqired++; } ?>
                        <?php if($rule == "url") { $is_url++; } ?>
                        <?php if($rule == "email") { $is_email++; } ?>
                        <?php if($rule == "digit") { $is_digit++; } ?>
                        <?php if($rule == "number") { $is_number++; } ?>
                        <?php if($rule == "date") { $is_date++; } ?>
                    @endforeach
                    <!-- Begin row -->
                    <div class="row">
                        <!-- Begin column -->
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                {{ Form::label("rules[]", "Required") }}
                                <input type="checkbox" name="rules[]" value="required" {{ ($is_reqired > 0) ? 'checked' : ''  }}>
                                <small><i>This element is required?</i></small>
                            </div>
                        </div>
                        <!-- End column -->
                        <!-- Begin column -->
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                {{ Form::label("rules[]", "Email") }}
                                <input type="checkbox" name="rules[]" value="email" {{ ($is_email > 0) ? 'checked' : ''  }}>
                                <small><i>Value must be an email address?</i></small>
                            </div>
                        </div>
                        <!-- End column -->
                    </div>
                    <!-- End row -->
                    <!-- Begin row -->
                        <div class="row">
                            <!-- Begin column -->
                            <div class="col-md-6">
                                <div class="form-group form-group-default">
                                    {{ Form::label("rules[]", "URL") }}
                                    <input type="checkbox" name="rules[]" value="url" {{ ($is_url > 0) ? 'checked' : ''  }}>
                                    <small><i>Value must be a url?</i></small>
                                </div>
                            </div>
                            <!-- End column -->
                            <!-- Begin column -->
                            <div class="col-md-6">
                                <div class="form-group form-group-default">
                                    {{ Form::label("rules[]", "Date") }}
                                    <input type="checkbox" name="rules[]" value="date" {{ ($is_date > 0) ? 'checked' : ''  }}>
                                    <small><i>Value must be a date (mm/dd/yyyy)?</i></small>
                                </div>
                            </div>
                            <!-- End column -->
                        </div>
                    <!-- End row -->
                    <!-- Begin row -->
                        <div class="row">
                            <!-- Begin column -->
                            <div class="col-md-6">
                                <div class="form-group form-group-default">
                                    {{ Form::label("rules[]", "Digit") }}
                                    <input type="checkbox" name="rules[]" value="digit" {{ ($is_digit > 0) ? 'checked' : ''  }}>
                                    <small><i>Value must be numeric without decimal numbers?</i></small>
                                </div>
                            </div>
                            <!-- End column -->
                            <!-- Begin column -->
                            <div class="col-md-6">
                                <div class="form-group form-group-default">
                                    {{ Form::label("rules[]", "Number") }}
                                    <input type="checkbox" name="rules[]" value="number" {{ ($is_number > 0) ? 'checked' : ''  }}>
                                    <small><i>Value must be numeric and accepts decimal numbers?</i></small>
                                </div>
                            </div>
                            <!-- End column -->
                        </div>
                    <!-- End row -->
                </div>
            </div>
        </div>

        <div class="tab-pane slide" id="ti{{$item->id}}tab4">
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
                                                 id="{{ $k.$item->size }}"
                                                 {{ ($k == $item->size) ? 'checked' : '' }}>
                                          <label for="{{ $k.$item->size }}">Size {{$v}}</label>
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