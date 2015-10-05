/* ============================================================
 * Calendar
 * This is a Demo App that was created using Pages Calendar Plugin
 * We have demonstrated a few function that are useful in creating
 * a custom calendar. Please refer docs for more information
 * ============================================================ */

(function($) {

    'use strict';

    $(document).ready(function() {

        $("#closeCreateModal").on("click", function(event){
            $("#createModal").modal("hide");
            $("#createForm :input").val("");
            event.preventDefault();
        });

        $("#closeEditModal").on("click", function(event){
            $("#editModal").modal("hide");
            $("#editForm :input").val("");
            event.preventDefault();
        });

        $("#closeDeleteModal").on("click", function(event){
            $("#deleteModal").modal("hide");
            $("#deleteForm :input").val("");
            event.preventDefault();
        });

        $("#deleteModalBtn").click(function(event){

            $("#editModal").modal("hide");
            $("#editForm :input").val("");
            event.preventDefault();

            functions.setEventDetailsToDeleteForm(selectedEvent);
            //Open edit modal
            $("#deleteModal").modal({
                backdrop:'static',
                keyboard:false
            });
        });

        // Get events from 
        var loadEvents = JSON.parse(Site.data.events),
            evArr = [],
            url = Site.Config.url,
            token = Site.data.token;

            for (var i = loadEvents.length - 1; i >= 0; i--) {
                loadEvents[i]
                evArr[i] = {
                            title: loadEvents[i].title,
                            class: 'bg-complete-lighter',
                            start: loadEvents[i].schedule_starts,
                            end: loadEvents[i].schedule_ends,
                            other:{
                                slug: loadEvents[i].slug,
                                category_id: loadEvents[i].category_id,
                                id: loadEvents[i].id,
                            }
                          };
            };

        var selectedEvent;
        $('body').pagescalendar({
                ui:{
                year:{
                    visible:true,
                    format:'YYYY',
                    startYear:'2000',
                    endYear:2020,
                    eventBubble:true
                },
                month:{
                    visible:true,
                    format:'MMM',
                    eventBubble:true
                },
                date:{
                    format:'MMMM YYYY, D dddd'
                },
                week:{
                    day:{
                        format:'D'
                    },
                    header:{
                        format:'dd'
                    },
                    eventBubble:true,
                    startOfTheWeek:'Sun'
                },
                grid:{
                    dateFormat:'D dddd',
                    timeFormat:'h A',
                    eventBubble:true,
                    slotDuration:'30'
                }
            },
            header:{
                visible:true,
                dateFormat:'MMM YYYY'
            },
            miniCalendar:{
                visible:true,
                highlightWeek:true,
                showEventBubbles:true
            },
            eventObj:{
                editable:true
            },
            now:null,
            locale:'en',
            timeFormat:'h a',
            dateFormat:'MMMM Do YYYY',
            // Load events
            events: evArr,
            onViewRenderComplete: function() {
                //You can Do a Simple AJAX here and update 
            },
            onEventClick: function(event) {
                //Open edit modal
                 $("#editModal").modal({
                    backdrop:'static',
                    keyboard:false
                });
                selectedEvent = event;
                functions.setEventDetailsToForm(selectedEvent);
            },
            onEventDragComplete: function(event) {
                selectedEvent = event;
                functions.setEventDetailsToUpdate(selectedEvent);
            },
            onEventResizeComplete: function(event) {
                selectedEvent = event;
                functions.setEventDetailsToUpdate(selectedEvent);
            },
            onTimeSlotDblClick: function(timeSlot) {
                // Configure modal
                $("#createModal").modal({
                    backdrop:'static',
                    keyboard:false
                });
                // Adding a new Event on Slot Double Click
                var newEvent = {
                    title: 'my new event',
                    class: 'bg-success-lighter',
                    start: timeSlot.date,
                    end: moment(timeSlot.date).add(1, 'hour').format(),
                    allDay: false,
                    other: {
                        //You can have your custom list of attributes here
                        category_id: '',
                        description: '',

                    }
                };
                selectedEvent = newEvent;
                //$('body').pagescalendar('addEvent', newEvent);

                functions.setEventDetailsToCreatForm(selectedEvent);
            }
        });
        //After the settings Render you Calendar
        $('body').pagescalendar('render');

        // Some Other Public Methods That can be Use are below \
        //console.log($('body').pagescalendar('getEvents'))
        //get the value of a property
        //console.log($('body').pagescalendar('getDate','MMMM'));

        var functions = {

            setEventDetailsToForm: function(event) {
                //Load Event Data To Text Field
                $('#editForm #title').val(event.title);
                $('#editForm #schedule_starts').val(event.start);
                $('#editForm #schedule_ends').val(event.end);
                $('#editForm #category_id').val(event.other.category_id);
                $('#editForm #id').val(event.other.id);
                $('#editForm #index').val(event.index);
            },

            setEventDetailsToCreatForm: function(event) {
                //Load Event Data To Text Field
                $('#createForm #title').val(event.title);
                $('#createForm #schedule_starts').val(event.start);
                $('#createForm #schedule_ends').val(event.end);
                $('#createForm #category_id').val(event.other.category_id);
            },

            setEventDetailsToDeleteForm: function(event) {
                //Load Event Data To Text Field
                $('#deleteForm #id').val(event.other.id);
                $('#deleteForm #index').val(event.index);
            },

            setEventDetailsToUpdate: function(event) {
                //Load Event Data To Text Field
                var eventUpdateData = {
                    title: event.title,
                    schedule_starts:event.start,
                    schedule_ends:event.end,
                    category_id:event.other.category_id,
                    id:event.other.id,
                    url: url+"/calendar/"+event.other.id
                };


                // Perform ajax action with new data
                functions.update_event(eventUpdateData);

                // Notify the user on whats going on
                 $("body").pgNotification({
                            style:'simple',
                            message:'Updating event...',
                            type:'info',
                            timeout:1000,
                            onClose: function(){
                                // do nothing
                            }
                        }).show();
            },
            createAlertMessage: function(message){

                return  '<p class="alert alert-'+message.type+'">'+
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                              '<span aria-hidden="true">&times;</span>'+
                            '</button>'+message.str+
                        '</p>';
            },
            create_nw_event: function(ev){
                $.ajax({
                    url:url+"/calendar",
                    type:"post",
                    data:{
                        category_id:ev.category_id,
                        title:ev.title,
                        slug:ev.slug,
                        _token:token,
                        schedule_starts:ev.schedule_starts,
                        schedule_ends:ev.schedule_ends,
                    }
                })
                .success(function(data){
                    $("#createModal").modal("hide");
                    $("#createForm :input").val("");
                     $("body").pgNotification({
                        style:'simple',
                        message:data.message,
                        type:data.level,
                        timeout:7000
                    }).show();
                    $('body').pagescalendar('addEvent', data.model);
                })
                .error(function(resp){
                    var data = JSON.parse(resp.responseText);
                    $("body").pgNotification({
                        style:'simple',
                        message:data.message,
                        type:data.level
                    }).show();
                })
                .complete(function(){
                     $('#createForm :input').prop("disabled", false);
                });
                
            },

            update_event: function(ev){
                $.ajax({
                    url:ev.url,
                    type:"Post",
                    data:{
                        category_id:ev.category_id,
                        title:ev.title,
                        slug:ev.slug,
                        _token:token,
                        schedule_starts:ev.schedule_starts,
                        schedule_ends:ev.schedule_ends,
                    }
                })
                .success(function(data){
                    $("#editModal").modal("hide");
                     $("body").pgNotification({
                        style:'simple',
                        message:data.message,
                        type:data.level,
                        timeout:7000
                    }).show();
                    $('body').pagescalendar('updateEvent', $('#editForm #index').val(), data.model);
                    $("#editForm :input").val("");
                })
                .error(function(resp){
                    var data = JSON.parse(resp.responseText);
                    $("body").pgNotification({
                        style:'simple',
                        message:data.message,
                        type:data.level
                    }).show();
                })
                .complete(function(){
                     $('#editForm :input').prop("disabled", false);
                });
                
            },

            delete_event: function(ev){
                $.ajax({
                    url:ev.url,
                    type:"Post",
                    data:{
                        _token:token,
                    }
                })
                .success(function(data){
                    $("#deleteModal").modal("hide");
                     $("body").pgNotification({
                        style:'simple',
                        message:data.message,
                        type:data.level,
                        timeout:7000
                    }).show();
                    $('body').pagescalendar('removeEvent', $('#deleteForm #index').val());
                    $("#deleteForm :input").val("");
                })
                .error(function(resp){
                    var data = JSON.parse(resp.responseText);
                    $("body").pgNotification({
                        style:'simple',
                        message:data.message,
                        type:data.level
                    }).show();
                })
                .complete(function(){
                     $('#deleteForm :input').prop("disabled", false);
                });
                
            }
        };


        $('#createForm').submit(function(event) {
            // Prep new data
            var newData = {
                title: $('#createForm #title').val(),
                schedule_starts: $('#createForm #schedule_starts').val(),
                schedule_ends: $('#createForm #schedule_ends').val(),
                category_id: $('#createForm #category_id').val(),
                code:$('#createForm #code').val()
            };
            // Disable form
            $('#createForm :input').prop("disabled", true);

            // Notify the user on whats going on
             $("body").pgNotification({
                        style:'simple',
                        message:'Saving...',
                        type:'info',
                        timeout:1000,
                        onClose: function(){
                            // do nothing
                        }
                    }).show();

            // Perform ajax action with new data
            functions.create_nw_event(newData);

            // Prevent the form from submitting
            event.preventDefault();
        });
        $('#editForm').submit(function(event) {
            // Prep new data
            var newData = {
                title: $('#editForm #title').val(),
                schedule_starts: $('#editForm #schedule_starts').val(),
                schedule_ends: $('#editForm #schedule_ends').val(),
                category_id: $('#editForm #category_id').val(),
                id: $('#editForm #id').val(),
                code:$('#editForm #code').val(),
                url: url+"/calendar/"+$('#editForm #id').val()
            };
            // Disable form
            $('#editForm :input').prop("disabled", true);

            // Notify the user on whats going on
             $("body").pgNotification({
                        style:'simple',
                        message:'Saving updates...',
                        type:'info',
                        timeout:1000,
                        onClose: function(){
                            // do nothing
                        }
                    }).show();

            // Perform ajax action with new data
            functions.update_event(newData);

            // Prevent the form from submitting
            event.preventDefault();
        });
        $('#deleteForm').submit(function(event) {
            // Prep new data
            var newData = {
                id: $('#deleteForm #id').val(),
                url: url+"/calendar/"+$('#deleteForm #id').val()+"/delete"
            };
            // Disable form
            $('#deleteForm :input').prop("disabled", true);

            // Notify the user on whats going on
            $("body").pgNotification({
                        style:'simple',
                        message:'Deleting data...',
                        type:'info',
                        timeout:1000,
                        onClose: function(){
                            // do nothing
                        }
                    }).show();

            // Perform ajax action with new data
            functions.delete_event(newData);

            // Prevent the form from submitting
            event.preventDefault();
        });

    });

})(window.jQuery);