<style>
:root {
--color-primary: {{ isset($settings['calendar']['calendar_primary_color']) ? $settings['calendar']['calendar_primary_color'] : '#00837B'  }};
--color-secondary: {{ isset($settings['calendar']['calendar_secondary_color']) ? $settings['calendar']['calendar_secondary_color'] : '#75B7BF'  }};
--color-accent:{{ isset($settings['calendar']['calendar_event_color']) ? $settings['calendar']['calendar_event_color'] : '#FF5B36'  }};
--color-dark-primary: #232020;
--color-dark-secondary: #555;
--color-dark-default: #7E7E7E;
--color-light-primary: #fff;
--color-light-secondary:#fafafa;
--color-gray-primary:#eee;
--color-gray-secondary:#ddd;

/* spacing */
--padding-1x:5px;
--padding-2x:10px;
--padding-3x:15px;
--padding-4x:20px;
--padding-5x:25px;
--padding-6x:30px;

}

/* Buttons */
.fc-button-primary,.btn-primary{
background:var(--color-primary) !important;
border-color:var(--color-primary) !important;
text-transform:uppercase;
letter-spacing:0.7px;
font-weight:400 !important;
}
.fc-button-primary:hover,.btn-primary:hover{
filter: brightness(95%);
}



/* Table styles */

/* table header style */
.fc-header-toolbar{
margin:0 !important;
padding: var(--padding-2x) var(--padding-3x) !important;
background: var( --color-primary) !important;
position:relative;
}

/* table Month Name */
.fc-header-toolbar .fc-left h2{
color: var(--color-light-primary) !important;
text-transform:uppercase;
font-size:1.2rem;
font-weight:400;
letter-spacing:1px;
}

/* table navigation */
.fc-header-toolbar .fc-right .fc-button{
border-color:var( --color-primary) !important;
background:var(--color-secondary) !important;
text-transform:uppercase;
letter-spacing:0.3px;
font-size:0.9rem;
line-height:0.9rem;
height:35.09px;
}

/* table day td */
thead .fc-day-header{
padding: var(--padding-2x) var(--padding-3x) !important;
color: var(--color-light-primary) !important;
background: var( --color-secondary);
text-transform:uppercase !important;
font-weight:300 !important;
border-color:var(--color-light-primary) !important;
letter-spacing:1px;
}

.fc-day-grid-event div:first-of-type {
    font-size: 0.8rem !important;
    padding-bottom: 5px !important;
}

.fc-day-grid-event div {
    font-size: 11px;
}

/* table tr zebra effect */
.fc-day-grid > .fc-row:nth-child(odd) .fc-bg td {
    background: var(--color-gray-primary);
    border-color: var(--color-light-primary);
}

.fc-day-grid > .fc-row:nth-child(even) .fc-bg td {
    background: var(--color-gray-secondary);
    border-color: var(--color-light-primary);
}


/* td level */
.fc-day-top .fc-day-number{
float:left !important;
color: var(--color-dark-default);
padding: var(--padding-2x) var(--padding-2x) !important;
font-size:0.8rem;
}

/* EVENT LEVEL */
.fc-day-grid-event{
    background:var(--color-accent) !important;
    border-radius:0 !important;
    padding: var(--padding-1x) var(--padding-2x) !important;
    border-color:var(--color-accent) !important;
    border:none !important;
}

/* EVENTS STRUCTURE */
/* current day bg color */
.fc-day.fc-today{
background:var(--color-secondary) !important;
}
/* current day color */
.fc-today .fc-day-number{
color: var(--color-light-primary);
}

.modal-content{
border:none !important;
}
.modal-content .modal-header{
background: var(--color-secondary);
text-alight:center;
color:var(--color-light-primry);
}
.modal-header .modal-title{
text-align:center;
color:var(--color-light-primary);
width:100%;
text-transform:uppercase;
font-weight:400;
font-size:1.1rem;
}
.modal-body{
color:var(--color-dark-secondary) !important;
font-size:1.1rem;
line-height:2rem !important;
padding:var(--padding-3x) var(--padding-4x) var(--padding-2x) var(--padding-4x)!important;
}
.modal-body br:last-of-type{
display:none !important;
}
.modal-footer .btn{
color:var(--color-dark-secondary);
}
.modal-footer .btn:hover{
color:var(--color-accent);
}
.fc-day-grid-event div:first-of-type{
font-size:1.0rem;
font-weight:600;
line-height:1.05rem;
}
@media screen and (min-width:768px){
.fc-header-toolbar .fc-left{
position:absolute;
top:50%;
left:50%;
transform:translate(-50%,-50%);
}
}
@media screen and (max-width:767px){
.fc-day-grid-container{
height:auto !important;
}
}
</style>