
// Example starter JavaScript for disabling form submissions if there are invalid fields

$(document).ready(function(){
  $('.stage-field select').change(function () {
    $(this).closest('.stage-field').removeClass('lead-stage')
    .removeClass('quote-meeting')
    .removeClass('work-in-progress')
    .removeClass('cancelled')
    .removeClass('completed')
    .removeClass('follow-up')
    if ($(this).val() == 'Lead') {
        $(this).closest('.stage-field').addClass('lead-stage')
    } else if ($(this).val() == 'Quote Meeting' || $(this).val() == 'Meeting Scheduled') {
        $(this).closest('.stage-field').addClass('quote-meeting')
    } else if ($(this).val() == 'Work in Progress') {
        $(this).closest('.stage-field').addClass('work-in-progress')
    } else if ($(this).val() == 'Cancelled') {
        $(this).closest('.stage-field').addClass('cancelled')
    } else if ($(this).val() == 'Completed') {
        $(this).closest('.stage-field').addClass('completed')
    } else if ($(this).val() == 'Follow Up') {
      $(this).closest('.stage-field').addClass('follow-up')
  } 
})

$('.hour-item.active .custom-control-input, .active.custom-control-input').click();
$('.hour-item .custom-control-input').click(function(){
  if($(this).prop('checked')){
    $(this).closest('.hour-item').removeClass('active')
  } else {
    $(this).closest('.hour-item').addClass('active')
  }
})

$('.form-control').change(function(){
  if($(this).val() == ''){
    $(this).addClass('gray')
    $(this).closest('.form-group').find('label').fadeOut(0)
  } else {
    $(this).removeClass('gray')
    $(this).closest('.form-group').find('label').fadeIn(0)
  }
})

$('.stage-field select').change();
$('select').change();

$('.btn.dropdown-toggle').click(function(){
    $(this).closest('.dropdown').find('.dropdown-menu').slideToggle();
})
$('.dropdown .dropdown-item').click(function(){
    var getView = $(this).attr('data-view')
    if(getView == 'list') {
        $('.funnel-wrap').fadeOut(0)
        $('.table-wrap').fadeIn();
    } else {
        $('.table-wrap').fadeOut(0); 
        $('.funnel-wrap').fadeIn()           
    }
    $(this).closest('.dropdown').attr('data-show-view',getView).find('.dropdown-menu').slideToggle();
    $(this).closest('.dropdown').find('.dropdown-toggle').text($(this).text())
    $('.dropdown .dropdown-item').removeClass('active')
    $(this).addClass('active');
})
$('.nav-calls .nav-link').click(function(){
    var takeCalls = $(this).attr('aria-controls')
    if(takeCalls == 'all') {
        $('.call-history-item').fadeIn();
    } else {
        $('.call-history-item').fadeOut(0);
        $('.call-history-item[data-call-type="'+ takeCalls +'"]').fadeIn();
    }
})

$('.close-btn').click(function(){
    $(this).closest('.close-target').fadeOut();
})

$('.message-item').click(function(){
    var userId = $(this).attr('data-user');
    $('.message-item').removeClass('active')
    $(this).addClass('active')
})

$('.send-message').keyup(function(){
   
    if($(this).val() == ''){
        $('.send-btn').fadeOut();
    } else {
        $('.send-btn').fadeIn();
    }
})
$( ".message-search #dial-us").blur(function(){
    $('.message-search .add-user').fadeIn();
});
$( ".message-search #dial-users" ).keyup(function(){  
    var takeElement = $(this);
    setTimeout(function(){
        if($(takeElement).closest('.message-search').find('.ui-menu').is(':visible')){
            $('.message-search .add-user').fadeOut();
         } else {
             $('.message-search .add-user').fadeIn();
         }
    },300)
})

$('.btns-wrap .btn').click(function(){
  $('.btns-wrap .btn').removeClass('btn-bd-left')
  .removeClass('btn-small')
  .removeClass('btn-default')
  .addClass('btn-text')
  if($(this).hasClass('btn-left')){
    $('.btn-right').addClass('btn-bd-left')
  } else if ($(this).hasClass('btn-right')){
    $('.btn-center').addClass('btn-bd-left')
  }
  $(this).removeClass('btn-text')
  .addClass('btn-small')
  .addClass('btn-default')
})

$('.add-image').click(function(){
  $(this).closest('.user-message').find('.add-image-area').fadeIn().addClass('active');  
})
$('.add-image-area .btn-close').click(function(){
    $('.add-image-area').fadeOut(0).removeClass('active');
  })

  $( '.larger-calendar .fc-toolbar-chunk:nth-of-type(3) .fc-button' ).click(function() {
    //$( '.larger-calendar .fc-toolbar-chunk:nth-of-type(3) .fc-button' ).fadeIn();
    $(this).parent().addClass('showed')
     if(!$(this).hasClass('.fc-button-active')) {
       console.log('fade')
      $( '.larger-calendar .fc-toolbar-chunk:nth-of-type(3) .fc-button:not(.fc-button-active)' ).fadeIn();
     } else {
      console.log('hide')
      $( '.larger-calendar .fc-toolbar-chunk:nth-of-type(3) .fc-button:not(.fc-button-active)' ).fadeOut();
   }
  });

  $('.select2-selection__arrow').each(function(){
    $(this).html('<svg width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">' + 
    '<path fill-rule="evenodd" clip-rule="evenodd" d="M11.7042 0.295188C11.3106 -0.0983958 10.6725 -0.0983963 10.2789 0.295188L6.00274 4.57135L1.72152 0.295263C1.32769 -0.098085 0.689186 -0.0980849 0.295365 0.295263C-0.0984549 0.688611 -0.0984553 1.32635 0.295365 1.7197L5.2761 6.69446C5.2808 6.69935 5.28555 6.7042 5.29037 6.70901C5.68395 7.1026 6.32208 7.1026 6.71566 6.70902L11.7042 1.72048C12.0978 1.3269 12.0978 0.688773 11.7042 0.295188Z" fill="#86969E"></path>'+
  '</svg>');
  })

    $('.form-group').click(function(){
      $(this).find('.form-control').focus();
    })

    // function checkFunct(currentLink){
    //   if($(currentLink).hasClass('fc-button-active')){
    //     $( '.larger-calendar .fc-button-group .fc-button:not(.fc-button-active)' ).fadeIn();
    //     console.log('Fade')
    //   } else {
    //     $( '.larger-calendar .fc-button-group .fc-button:not(.fc-button-active)' ).fadeOut();
    //     console.log('Hide')
    //   }      
    // }

})



document.addEventListener('DOMContentLoaded', function() {

    
    //circle start
    var projects = [
      {
        value: "Andrew Smith",
        label: "Andrew Smith",
        cost: "$300.00",
        phone: "380684496",
        desc: "Lead",
        icon: "jquery_32x32.png"
      },
      {
          value: "Andrew Smith 2",
          label: "Andrew Smith",
          cost: "$400.00",
          phone: "380684496",
          desc: "Lead",
          icon: "jquery_32x32.png"
        },
        {
          value: "Andrew Smith 3",
          label: "Andrew Smith",
          cost: "$500.00",
          phone: "380684496",
          desc: "Lead",
          icon: "jquery_32x32.png"
        },
        {
          value: "Andrew Smith 3",
          label: "Andrew Smith",
          cost: "$500.00",
          phone: "380684496",
          desc: "Lead",
          icon: "jquery_32x32.png"
        },
        {
          value: "Andrew Smith 3",
          label: "Andrew Smith",
          cost: "$500.00",
          phone: "380684496",
          desc: "Lead",
          icon: "jquery_32x32.png"
        }           
    ];      
      
      $( "#dial-users" ).autocomplete({
        minLength: 0,
        appendTo: ".users-list",
        source: projects,
        focus: function( event, ui ) {
          $( "#project" ).val( ui.item.label );
          return false;
        },
        select: function( event, ui ) {
          $( "#project" ).val( ui.item.label );
          $( "#project-id" ).val( ui.item.value );
          $( "#project-description" ).html( ui.item.desc );
   
          return false;
        }
      })
      .autocomplete( "instance" )._renderItem = function( ul, item ) {
        return $( "<li>" )
          .append( "<div class='info'>" + 
          "<div class='title'>" + item.label + "</div>" +
          "<div class='value'> " + item.cost + " · </div>" +
          "<div class='desc'> " + item.desc + "</div>" + "</div>" +
          "<a href='tel:" + item.phone + "' class='btn call-btn'>" +
            "<img src='img/_src/svg/call-phone-icon.svg' alt='call-phone'>" +
         "</a>"
          )
          .appendTo( ul );
      };

 
    

    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth'
    });
    calendar.render();
    var calendarEl = document.getElementById('large-calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      headerToolbar: {
        left: 'title,prev,next',
        center: 'addNewEventCalendar,today',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      slotDuration: '01:00',
      slotLabelFormat: {
        hour: '2-digit',
        minute: '2-digit',
        omitZeroMinute: false,
        meridiem: false
      },   
      customButtons: {
        addNewEventCalendar: {
          text: 'Add New',
          click: function() {
            $('.add-new-event-calendar').click();
          }
        }
      },      
      navLinks: true, // can click day/week names to navigate views
      selectable: true,
      selectMirror: true,
      select: function(arg) {
        $('.add-new-event-calendar').click();
        // var title = prompt('Event Title:');
        if (title) {
          calendar.addEvent({
            title: title,
            start: arg.start,
            end: arg.end,
            allDay: arg.allDay
          })
        }
        calendar.unselect()
      },
      eventClick: function(info) {
        $('.event-box').removeClass('quote-meeting').removeClass('work').removeClass('other').fadeIn();
       // $('.fc-event').removeClass('quote-meeting').removeClass('work').removeClass('other')
       // $(info.el).addClass(info.event.extendedProps.type)
        $('.event-box').addClass(info.event.extendedProps.type);
        $('.event-box').css({
          'left': info.jsEvent.pageX,
          'top': info.jsEvent.pageY
        })

        if(info.event.extendedProps.typeLabel != undefined) {
          $('.event-box .title').text(info.event.title + ' · ' + info.event.extendedProps.typeLabel);
        } else {
          $('.event-box .title').text(info.event.title);
        }

        if(info.event.end != null) {
          $('.event-box .event-date').text(info.event.start + "-" + info.event.end);
        } else {
          $('.event-box .event-date').text(info.event.start);
        }
       
        if(info.event.extendedProps.address != ''){
          $('.event-box .location').fadeIn(0);
          $('.event-box .location .value').text(info.event.extendedProps.address)
        } else {
          $('.event-box .location').fadeOut(0)
        }

        if(info.event.extendedProps.categories != ''){
          $('.event-box .categories').fadeIn(0);
          $('.event-box .categories .value').text(info.event.extendedProps.categories)
        } else {
          $('.event-box .categories').fadeOut(0)
        }        
        

        // $('.add-new-event-calendar').click();
      },
      eventDidMount: function(info) {
        console.log(info.event.extendedProps);
        // {description: "Lecture", department: "BioChemistry"}
      },      
      popover: {
        year: false
      },
      editable: true,
      dayMaxEvents: true, // allow "more" link when too many events
      events: [
        {
          title: 'Andrew 1',
          start: '2021-05-03',
          end: '2021-05-03',
          classNames: ['quote-meeting'],
          extendedProps: {
            categories: 'Follow Up',
            type: 'quote-meeting',
            typeLabel: 'Quote Meeting',
            city: 'Kiev',
            zip: 'NSW 2031',
            address: '50 Missenden Rd',
            upfrontValue: '$300',
            ongoingValue: '$400',
            phone: '+38096009753',
            chatLink: 'google.com.ua'
          }
        },
        {
          title: 'Andrew 2',
          start: '2021-05-03T14:20:00',
          end: '2021-05-03T16:00:00',
          classNames: ['work'],
          extendedProps: {
            type: 'work',
            typeLabel: 'Work',
            city: 'Kiev',
            zip: 'NSW 2031',
            address: '50 Missenden Rd',
            upfrontValue: '$300',
            ongoingValue: '$400',
            phone: '+38096009753',
            chatLink: 'google.com.ua'
          }
        },    
        {
          title: 'Andrew 2',
          start: '2021-05-03T14:20:00',
          end: '2021-05-03T16:00:00',
          classNames: ['work'],
          extendedProps: {
            type: 'work',
            typeLabel: 'Work',
            city: 'Kiev',
            zip: 'NSW 2031',
            address: '50 Missenden Rd',
            upfrontValue: '$300',
            ongoingValue: '$400',
            phone: '+38096009753',
            chatLink: 'google.com.ua'
          }
        },
        {
          title: 'Andrew 2',
          start: '2021-05-03T14:20:00',
          end: '2021-05-03T16:00:00',
          classNames: ['work'],
          extendedProps: {
            type: 'work',
            typeLabel: 'Work',
            city: 'Kiev',
            zip: 'NSW 2031',
            address: '50 Missenden Rd',
            upfrontValue: '$300',
            ongoingValue: '$400',
            phone: '+38096009753',
            chatLink: 'google.com.ua'
          }
        },         
        {
          title: 'Andrew 3',
          start: '2021-05-05T14:00:00',
          end: '2021-05-05T16:00:00',
          classNames: ['other'],
          extendedProps: {
            type: 'other',
            typeLabel: 'Other',
            city: 'Kiev',
            zip: 'NSW 2031',
            address: '',
            upfrontValue: '$300',
            ongoingValue: '$400',
            phone: '+38096009753',
            chatLink: 'google.com.ua'
          }
        },          
        {
          title: 'Andrew 4',
          start: '2021-05-03',
          end: '2021-05-08',
          classNames: ['reminder'],
          extendedProps: {
            type: 'reminder',
            typeLabel: 'Reminder',
            city: 'Kiev',
            zip: 'NSW 2031',
            address: '50 Missenden Rd',
            upfrontValue: '$300',
            ongoingValue: '$400',
            phone: '+38096009753',
            chatLink: 'google.com.ua'
          }
        },
        {
          title: 'Andrew 4',
          start: '2021-05-03',
          end: '2021-05-08',
          classNames: ['reminder'],
          extendedProps: {
            type: 'reminder',
            typeLabel: 'Reminder',
            city: 'Kiev',
            zip: 'NSW 2031',
            address: '50 Missenden Rd',
            upfrontValue: '$300',
            ongoingValue: '$400',
            phone: '+38096009753',
            chatLink: 'google.com.ua'
          }
        }                                                              
      ]
    });

    calendar.render();




    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);

});	


