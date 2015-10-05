<div class="myFormWizard">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs nav-tabs-linetriangle nav-tabs-separator">
        <li class="active">
            <a data-toggle="tab" href="#titab1"><i class="fa fa-info tab-icon"></i> <span>Basic Info</span></a>
        </li>
        <li class="">
            <a data-toggle="tab" href="#titab2"><i class="fa fa-lock tab-icon"></i> <span>Rules</span></a>
        </li>
        <li class="">
            <a data-toggle="tab" href="#titab4"><i class="pg-grid tab-icon"></i> <span>Layout</span></a>
        </li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content" style="padding-left:0px; padding-right:0px;">
        <div class="tab-pane active slide" id="titab1">
            <div class="form-group form-group-default">
            	{{ Form::label("name", "Name") }}
				{{ Form::text("name", "", ["placeholder"=>"E.g. Name, Email, Password, etc.", "class"=>"form-control", "required"]) }}
			</div>
            <div class="form-group form-group-default form-group-attached">
                {{ Form::label("position", "Position") }}
                {{ Form::number("position", "", ["placeholder"=>"Enter a numeric value", "min"=>"0", "class"=>"form-control", "required"]) }}
                <small>This determines where this element with appear on your form</small>
            </div>
            <div class="form-group form-group-default">
                {{ Form::label("group", "Group") }}
                {{ Form::text("group", "", ["placeholder"=>"This will help group your elements on the form (Optional)", "class"=>"form-control", "required"]) }}
            </div>
        </div>
        <div class="tab-pane slide" id="titab2">
             <div class="panel panel-transparent">

                <div class="panel-body" style="padding-left:0px; padding-right:0px;">
                    <!-- Begin row -->
                    <div class="row">
                        <!-- Begin column -->
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                {{ Form::label("rules[]", "Required") }}
                                <input type="checkbox" name="rules[]" value="required">
                                <small><i>This element is required?</i></small>
                            </div>
                        </div>
                        <!-- End column -->
                        <!-- Begin column -->
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                {{ Form::label("rules[]", "Email") }}
                                <input type="checkbox" name="rules[]" value="email">
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
                                    <input type="checkbox" name="rules[]" value="url">
                                    <small><i>Value must be a url?</i></small>
                                </div>
                            </div>
                            <!-- End column -->
                            <!-- Begin column -->
                            <div class="col-md-6">
                                <div class="form-group form-group-default">
                                    {{ Form::label("rules[]", "Date") }}
                                    <input type="checkbox" name="rules[]" value="date">
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
                                    <input type="checkbox" name="rules[]" value="digit">
                                    <small><i>Value must be numeric without decimal numbers?</i></small>
                                </div>
                            </div>
                            <!-- End column -->
                            <!-- Begin column -->
                            <div class="col-md-6">
                                <div class="form-group form-group-default">
                                    {{ Form::label("rules[]", "Number") }}
                                    <input type="checkbox" name="rules[]" value="number">
                                    <small><i>Value must be numeric and accepts decimal numbers?</i></small>
                                </div>
                            </div>
                            <!-- End column -->
                        </div>
                    <!-- End row -->
                </div>
            </div>
        </div>
        <div class="tab-pane slide" id="titab4">
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
                                                 id="{{ $k }}txi"
                                                 {{ ($v == 12) ? 'checked' : '' }}>
                                          <label for="{{ $k }}txi">Size {{$v}}</label>
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