//Check Year
isCheckYear = (year) => {
    return (year % 4 === 0 && year % 100 !== 0 && year % 400 !== 0) ||
        (year % 100 === 0 && year % 400 === 0)
};

getFebDays = (year) => {
    return isCheckYear(year) ? 29 : 28
};

var calendar = document.querySelector('.calendar');
var monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
var monthPicker = document.querySelector('#month-picker');

monthPicker.onclick = () => {
    monthList.classList.add('show')
};

//Generate Calendar
generateCalendar = (month, year) => {
    var calendarDay = document.querySelector('.calendar-day');
    calendarDay.innerHTML = '';

    var calendarHeaderYear = document.querySelector('#year');
    var daysOfMonth = [31, getFebDays(year), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    var currDate = new Date();

    monthPicker.innerHTML = monthNames[month];
    calendarHeaderYear.innerHTML = year;

    var firstDay = new Date(year, month, 1);

    for (var i = 0; i <= daysOfMonth[month] + firstDay.getDay() - 1; i++) {
        var day = document.createElement('div')
        if (i >= firstDay.getDay()) {
            day.classList.add('calendarDayHover')
            day.innerHTML = i - firstDay.getDay() + 1
            day.innerHTML += `<span></span>
                             <span></span>
                             <span></span>
                             <span></span>`
            if (i - firstDay.getDay() + 1 === currDate.getDate() && year === currDate.getFullYear() && month === currDate.getMonth()) {
                day.classList.add('currDate')
            }
        }
        calendarDay.appendChild(day)
    };
};

var monthList = calendar.querySelector('.month-list');
monthNames.forEach((e, index) => {
    var month = document.createElement('div')
    month.innerHTML = `<div>${e}</div>`
    month.onclick = () => {
        monthList.classList.remove('show')
        currMonth.value = index
        generateCalendar(currMonth.value, currYear.value)
    }
    monthList.appendChild(month)
});

document.querySelector('#prev-year').onclick = () => {
    --currYear.value
    generateCalendar(currMonth.value, currYear.value)
};

document.querySelector('#next-year').onclick = () => {
    ++currYear.value
    generateCalendar(currMonth.value, currYear.value)
};

var currDate = new Date();
var currMonth = { value: currDate.getMonth() };
var currYear = { value: currDate.getFullYear() };

generateCalendar(currMonth.value, currYear.value);