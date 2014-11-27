var _readableDateFormat = "d M, y";
var _systemDateFormat = "ddmmyy";
var _tripType = {
	one: "oneway",
	rnd: "roundtrip",
	route: "route"
};
var _interval;

$(function() {
	// Сворачивание/разворачивание блоков по нажатию на управляющие элементы
	$("body").on("click", ".expander, .minimizer", function() {
		toggleExpandable($(this));
		return false;
	});
	
	// Вид полей ввода по умолчанию
	$("body").on("focus", ".input.fade, input.fade", function() {
		$(this).data("default-value", $(this).val()).val("").removeClass("fade");
		$(this).blur(function() {
			if ($.trim($(this).val()) == "") {
				$(this).val($(this).addClass("fade").data("default-value"));
			}
		});
	});
	
	// Очистка подсвеченных полей и скрытие сообщений об ошибках по активации поля
	$("body").on("focus click", ".input, input", function() {
		$(this).removeClass("error");
		var input = $(this).parents(".input").length > 0 ? $(this).parents(".input").first() : $(this);
		input.siblings(".input-message.error").remove().end().parents("label").siblings(".input-message").remove();	
	});
	
	// Управление перекючаемыми блоками
	$(".selectable-control").click(function() {
		var n = $(this).parent().find(".selectable-control").index($(this));
		$(this).parent().find(".selectable-control").removeClass("current");
		$(this).addClass("current");
		$(this).parents(".selectable-container").first().find(".selectable").addClass("hidden")
		$(this).parents(".selectable-container").first().find(".selectable").eq(n).removeClass("hidden");
		return false;
	});
	
	// Управление кнопками с сохранением нажатия
	$(".btn.radio").click(function() {
		$(this).toggleClass("active");
		return false;
	});
	
	// Управление перекючателями
	$(".selector a").click(function() {
		var n = $(this).parents(".selector").find("li a").index($(this));
		$(this).parents(".selector").find("li").removeClass("current");
		$(this).parents("li").addClass("current");
		if ($(this).is(".pseudo")) return false;
	});
	
	// Скрытие всплывающих окон
	$(".popup-header .btn-x, #message-container .btn.close").click(function() {
		$(this).parents(".popup-wrapper").addClass("hidden");
		$("#overlay").addClass("hidden");
		return false;
	});
	
	// Отображение всплывающего окна входа/регистрации
	$("#login .login, #login .register").click(function() {
		$("#login-wrapper .selectable-control").removeClass("current");
		var type = $(this).is(".login") ? ".login" : ".register";
		$("#login-wrapper .selectable-control" + type).addClass("current");
		$("#login-wrapper .selectable").addClass("hidden");
		$("#login-wrapper").find(".selectable").eq($("#login-wrapper .selectable-control").index($("#login-wrapper .selectable-control" + type))).removeClass("hidden");
		$("#overlay, #login-wrapper").removeClass("hidden");		
		
		document.onkeydown = function(event) {
			if (window.event) event = window.event;
			switch (event.keyCode ? event.keyCode : event.which ? event.which : null) {
				case 0x1b: // Esc
				$("#overlay, .popup-wrapper").addClass("hidden");
				break;
			}
		}
		return false;
	});
	
	// Прекращение режима ожидания
	$("#progress-container .cancel").click(function() {
		cancelWait();
	});
	
	// Закрытие сообщений об ошибке в поле ввода
	$(".input-message").on("click", ".btn-x", function() {
		$(this).parents(".input-message").addClass("hidden").parent().find(".input").removeClass("error").filter(".input:not(div)").focus();
		return false;
	});
	
	/* Russian (UTF-8) initialisation for the jQuery UI date picker plugin. */
	/* Written by Andrew Stromnov (stromnov@gmail.com). */
	$.datepicker.regional['ru'] = {
		closeText: 'Закрыть',
		prevText: '&#x3c;Пред',
		nextText: 'След&#x3e;',
		currentText: 'Сегодня',
		monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь', 'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
		monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн', 'Июл','Авг','Сен','Окт','Ноя','Дек'],
		dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
		dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
		dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
		weekHeader: 'Нед',
		dateFormat: 'dd.mm.yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		numberOfMonths: 2,
		yearSuffix: ''
	};
	$.datepicker.setDefaults($.datepicker.regional['ru']);
	$.datepicker.setDefaults({
		changeMonth: true,
		changeYear: true,
		dateFormat: _readableDateFormat,
		altFormat: _systemDateFormat 
	});
	
	// Привязка всплывающих календарей
	$(".date .input").each(function() {
		var srvField = $(this).siblings("input[type=hidden]").first();
		$(this).datepicker({
			altField: srvField,
			beforeShow: function(input, inst) {
				$(this).siblings("a.calendar").addClass("btn-x");
			},
			onSelect: function(dateText, inst) { 
				$(this).removeClass("fade").siblings(".input-message").remove();
				$(this).change();
			},
			onClose: function(dateText, inst) { 
				$(this).siblings("a.calendar").removeClass("btn-x");
			}
		}).keydown(function() { return false }).bind('contextmenu', function() { return false });
		
		if ($(this).is(":not(:disabled)")) {
			if ($.trim($(this).val().replace(/[-–—]/,"")) != "") {
				$(this).datepicker("setDate", $.datepicker.parseDate(_readableDateFormat, $(this).val())).removeClass("fade");
			} else {
				if ((srvField.length > 0) && ($.trim(srvField.val()) != "")) {
					$(this).datepicker("setDate", $.datepicker.parseDate(_systemDateFormat, srvField.val())).removeClass("fade");
				} else {
					if (($(this).parents(".search-form").length > 0)) {
						$(this).datepicker("setDate", "0").removeClass("fade");
					}
				}
			}
		}
		
		var val = $(this).val();
		if (typeof $(this).data("min-date") != "undefined") {
			$(this).datepicker("option", "minDate", new Date(parseInt($(this).data("min-date").split("/")[0]), parseInt($(this).data("min-date").split("/")[1]), parseInt($(this).data("min-date").split("/")[2])));
		}
		if (typeof $(this).data("max-date") != "undefined") {
			$(this).datepicker("option", "maxDate", new Date(parseInt($(this).data("max-date").split("/")[0]), parseInt($(this).data("max-date").split("/")[1]), parseInt($(this).data("max-date").split("/")[2]))).val(val);
		}
	});	
	
	// Отображение/скрытие блока выбора даты по клику на иконке
	$(".date .calendar").click(function(e){
		var input = $(this).siblings(".input").first();
		if (!input.is(':disabled')) {
			if (!input.datepicker("widget").is(":visible")) {
				input.datepicker("show");
			} else {
				input.datepicker("hide");
			}		
		}
		return false;
	});
	
	// Очистка даты
	$(".date .btn-x").click(function() {
		$.datepicker._clearDate($(this).siblings(".input"));
		$(this).siblings(".input").val("–").addClass("fade");
		return false;
	});
});

// Сворачивание/разворачивание блоков
function toggleExpandable(/* expander, direction = "down"|"up" */) {
	var expander = arguments[0];
	var direction = arguments.length > 1 ? arguments[1] : false;
	var container = $(expander).closest(".expandable-container");
	if (container.hasClass("minimized")) {
		if (direction != "up") {
			container.find(".foldable").slideUp();
			container.find(".expandable").each(function() {
				if ($(this).closest(".expandable-container")[0] == container[0]) {
					$(this).slideDown(function() {
						$(this).removeAttr("style");
						container.removeClass("minimized");
						expander.html(expander.html().replace("Показать", "Скрыть").replace("Развернуть", "Свернуть").replace("Больше", "Меньше"))
					});
				}
			});
		}		
	} else {
		if (direction != "down") {
			container.find(".foldable").slideDown();
			container.find(".expandable").each(function() {
				if ($(this).closest(".expandable-container")[0] == container[0]) {
					$(this).slideUp(function() {
						$(this).removeAttr("style");
						container.addClass("minimized");
						expander.html(expander.html().replace("Скрыть", "Показать").replace("Свернуть", "Развернуть").replace("Меньше", "Больше"));
					});
				}
			});
		}
	}
}

// Отображение сообщения
function showMessage(/* title, msg, defaultTitle, altTitle */) {
	$("#message-wrapper .message-title").html(arguments[0]);
	$("#message-wrapper .message-body").html(arguments[1]);
	$("#message-wrapper .actions .btn").first().attr("value", "OK").siblings().remove();
	if  (typeof arguments[2] !== "undefined") $("#message-wrapper .actions .default").first().attr("value", arguments[2]);
	if  (typeof arguments[3] !== "undefined") {
		$("#message-wrapper .actions").append("<input class='btn' type='button'>");
		$("#message-wrapper .actions .btn").last().attr("value", arguments[3]);
	}
	$("#overlay, #message-wrapper").removeClass("hidden");
}

// Отображение процесса ожидания
function startWait(/* msg, progressed */) {
	var msg = "";
	var progressed = false;
	switch (arguments.length) {
		case 1:
			if (typeof arguments[0] == "string") {
				msg = arguments[0];
			} else {
				progressed = arguments[0];
			}
			break;
		case 2:
			msg = arguments[0];
			progressed = arguments[1];
			break;
	}
	if (progressed) _interval = setInterval(progressStep, 120);
	$("#overlay, #progress-wrapper").removeClass("hidden");
	$("#progress-wrapper .status").text(msg);
	$("#progress-wrapper .bar").css("backgroundImage", "url(../images/bar-animation-big-blue.gif)")
}
function progressStep() {
	var step = 0.5;
	var absWidth = parseInt($("#progress-wrapper .progress").css("width"));
	var absLimit = parseInt($("#progress-wrapper .bar").css("width"));
	var percent = Math.ceil(10000*absWidth/absLimit)/100;
	if (percent < 50) {
		$("#progress-wrapper .progress").css("width", percent + step + "%");
	} else {
		var growth = step*(100-percent)/50;
		if (absWidth*growth/100 < 1) {
			growth = (Math.random()/5 < (100 - percent)/50)?1:0;
			if ((absWidth + growth) < absLimit) {
				$("#progress-wrapper .progress").css("width", absWidth + growth + "px");
			}
		} else {
			$("#progress-wrapper .progress").css("width", percent + growth + "%");
		}
	}
}
// Прекращение режима ожидания
function cancelWait() {
	$("#overlay, #progress-wrapper").addClass("hidden");
	clearInterval(_interval);
	$("#progress-wrapper .progress").css("width", "0%");
}
