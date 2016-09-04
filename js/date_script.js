$(document).ready(function() {
	var day=['Неділя','Понеділок','Вівторок','Середа','Четвер','П`ятниця','Субота'],
	   month=['Січня','Лютого','Березня','Квітня','Травня','Червня','Липня','Серпня','Вересня','Жовтня','Листопада','Грудня'];
   SetData();
   function SetData() {
	   var now = new Date();
	   $('.date').html(day[now.getDay()]+', ');
	   $('.date').append(now.getDate()+' ');
	   $('.date').append(' '+month[now.getMonth()]+' ');
	   
	   $('.date').append(now.getFullYear()+' &nbsp; &nbsp; ');
	   hour=now.getHours();
	   minutes=now.getMinutes();
	   if (minutes<10) {minutes='0'+minutes};
	   $('.date').append(hour+':'+minutes);
	}
  	setInterval(SetData,60);

});