<div class="myFormWizard">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs nav-tabs-linetriangle nav-tabs-separator">
        <li class="active">
            <a data-toggle="tab" href="#URtab1"><i class="fa fa-info tab-icon"></i> <span>Basic Info</span></a>
        </li>
        <li class="">
            <a data-toggle="tab" href="#URtab2"><i class="fa  fa-list tab-icon"></i> <span>List</span></a>
        </li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content" style="padding-left:0px; padding-right:0px;">
        <div class="tab-pane active slide" id="URtab1">

            <div class="form-group form-group-default">
                {{ Form::label("program_id", "Program") }}
                <select name="program_id" id="program_id" name="program_id" class="program_id form-control">
                    <option value="">Select program</option>
                    @foreach($item->programs as $program)
                        <option value="{{ $program->id }}"> {{ $program->name }} </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group form-group-default m-t-10">
                {{ Form::label("year", "Year") }}
                <select name="year" id="year" name="year" class="year form-control">
                    <option value="">Select Year</option>
                    @for($i = date("Y"); $i >= 2000; $i--)
                        <option value="{{ $i }}"> {{ $i }} </option>
                    @endfor
                </select>
            </div>

            <div class="form-group form-group-default">
                {{ Form::label("semester_id", "Semester") }}
                <select name="semester_id" id="semester_id" name="semester_id" class="semester_id form-control">
                    <option value="">Select semester</option>
                    @foreach($semesters as $k => $v)
                        <option value="{{ $k }}"> {{ $v }} </option>
                    @endforeach
                </select>
            </div>

        </div>
        <div class="tab-pane slide" id="URtab2">
            <div class="panel panel-transparent">
                <div class="panel-body listValues" id="listvaluesUR" style="padding:0px 0px;">
                  
                </div>

                <div class="panel-body" style="padding-left:0px; padding-right:0px;">
                    <p class="text-center" id="eselectoptionsModalHelperUR">
                        <small>
                            Click the plus sign to add a new select option
                        </small>
                    </p>
                    <button type="button" class="btn btn-block btn-success addListvalue" data-id="UR" data-helper="#eselectoptionsModalHelperUR" data-target="#listvaluesUR" title="Add a select option">
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