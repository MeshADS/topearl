<div class="myFormWizard">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs nav-tabs-linetriangle nav-tabs-separator">
        <li class="active">
            <a data-toggle="tab" href="#UR{{$result->id}}tab1"><i class="fa fa-info tab-icon"></i> <span>Basic Info</span></a>
        </li>
        <li class="">
            <a data-toggle="tab" href="#UR{{$result->id}}tab2"><i class="fa  fa-list tab-icon"></i> <span>List</span></a>
        </li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content" style="padding-left:0px; padding-right:0px;">
        <div class="tab-pane active slide" id="UR{{$result->id}}tab1">

            <div class="form-group form-group-default">
                {{ Form::label("program_id", "Program") }}
                <select name="program_id" id="program_id" name="program_id" class="program_id form-control">
                    <option value="">Select program</option>
                    @foreach($item->programs as $program)
                        <option value="{{ $program->id }}" {{ ($program->id == $result->program_id) ? ' selected ' : '' }} > {{ $program->name }} </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group form-group-default m-t-10">
                {{ Form::label("year", "Year") }}
                <select name="year" id="year" name="year" class="year form-control">
                    <option value="">Select Year</option>
                    @for($i = date("Y"); $i >= 2000; $i--)
                        <option value="{{ $i }}" {{ ($i == $result->year) ? ' selected ' : '' }} > {{ $i }} </option>
                    @endfor
                </select>
            </div>

            <div class="form-group form-group-default">
                {{ Form::label("semester_id", "Semester") }}
                <select name="semester_id" id="semester_id" name="semester_id" class="semester_id form-control">
                    <option value="">Select semester</option>
                    @foreach($semesters as $k => $v)
                        <option value="{{ $k }}" {{ ($k == $result->semester_id) ? ' selected ' : '' }} > {{ $v }} </option>
                    @endforeach
                </select>
            </div>

        </div>
        <div class="tab-pane slide" id="UR{{$result->id}}tab2">
            <div class="panel panel-transparent">
                <div class="panel-body listValues" id="listvalues{{ $result->id }}" style="padding:0px 0px;">
                    @foreach($result->resultslist as $lv)
                        <div class="row p-r-35 m-t-10 listvalues" id="elist{{ $result->id }}-{{ $lv->id }}">
                            <div class="col-md-4">
                                <div class="form-group form-group-default">
                                    <label for="list">
                                        Name
                                    </label>
                                    <input type="text" name="olist[]" class="form-control" value="{{$lv->name}}" placeholder="List item name">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-default">
                                    <label for="value">
                                        Value
                                    </label>
                                    <input type="text" name="ovalue[]" class="form-control" value="{{ $lv->value }}" placeholder="List item value">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-default">
                                    <label for="position">
                                        Position
                                    </label>
                                    <input type="text" name="oposition[]" class="form-control" value="{{ $lv->position }}" placeholder="List item position">
                                </div>
                                <input type="hidden" name="oid[]" value="{{$lv->id}}">
                                <input type="hidden" name="delete[]" id="deleteThis" value="0">
                            </div>
                            <a href="javascript:;" title="Delete!" class="new-element-dl-btn remove-olistvalue" data-target="#elist{{ $result->id }}-{{ $lv->id }}">
                                <i class="fa fa-times"></i>
                            </a>
                            <div class="delMessage p-l-15 p-r-15 p-t-20">
                                This option will be deleted after you save changes.
                                <a href="#" title="Cancel this action" class="cancel-remove-olistvalue" data-target="#elist{{ $result->id }}-{{ $lv->id }}">[Cancel]</a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="panel-body" style="padding-left:0px; padding-right:0px;">
                    <p class="text-center" id="selectModalHelper{{$result->id}}" style="{{ (count($result->resultslist) > 0) ? 'display:none;' :'' }}">
                        <small>
                            Click the plus sign to add a new select option
                        </small>
                    </p>
                    <button type="button" class="btn btn-block btn-success addListvalue" data-id="{{ $result->id }}" data-helper="#selectModalHelper{{$result->id}}" data-target="#listvalues{{ $result->id }}" title="Add a select option">
                        <i class="fa fa-plus-circle"></i>
                    </button>
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
                    <span>Update</span>
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