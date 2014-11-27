$(function() {
	$.datepicker.setDefaults({
		minDate: "0",
		maxDate: "+330"
	});
	


	// Восстановление стираемых полей ввода по умолчанию
	$(".input.clearable").blur(function() {
		if ($.trim($(this).val()) == "") {
			$(this).val($(this).data("default-value")).addClass("fade").siblings(".btn-x").addClass("hidden");
		}
	});	
	// Активация возможности очистки поля
	$(".input.clearable").change(function() {
		if (($.trim($(this).val()) != "") && ($(this).val() != $(this).data("default-value"))) {
			$(this).siblings(".btn-x").removeClass("hidden");
		}
	});	
	// Очистка введенного ранее поля
	$(".input-container .btn-x").click(function() {
		$(this).siblings(".input.clearable").val("").focus().siblings(".code").html("").siblings("[type=hidden]").val("");
		$(this).addClass("hidden");
		return false;
	});
	
	// Установка значений по умолчанию
	$(".clearable.airport").data("default-value", "Город или код аэропорта");
	// Обработка очищаемых полей при загрузке или возврате на старницу с помщью браузера
	$(".clearable.airport").each(function() {
		if ($(this).val() != $(this).data("default-value")) {
			$(this).removeClass("fade").siblings(".btn-x").removeClass("hidden");
		} else {
			$(this).addClass("fade").siblings(".btn-x").addClass("hidden");
		}
	});
	// Отображение и выбор быстрых предложений по городам на пустом поле
	$("input.airport").focus(function() {
		if ($(this).val() == "") {
			$(this).siblings(".input-message").removeClass("hidden").find(".btn-x").click(function() {
				$(this).parents(".input-message").remove();
			});
		}
	});
	$("input.airport").siblings(".input-message").find("a.pseudo").click(function(){
		$(this).parents(".input-message").siblings("input.airport").val($(this).data("text")).removeClass("fade").siblings(".btn-x").removeClass("hidden").siblings(".code").html($(this).data("code")).siblings("[type=hidden]").val($(this).data("code")).end().end().end().end().remove();
	    return false;
	});
	
	// Обработка ручного ввода
	$("input.airport").keypress(function() {
		$(this).siblings(".code").html("").siblings(".input-message").remove();
	});
	
	// Привязка автозаполнения аэропорта
	$("input.airport").each(function() {
		bindAutocomplete($(this))
	});
	
	// Начальные установки кратких описаний критериев поиска
	$(".input.carriers").each(function() {
		setBriefCarriers($(this));
	});
	$(".input.passengers").each(function() {
		setBriefPassengers($(this));
	});
	setBriefClass($(".search-form .input.class"));
	
	// Отображение элементов списка количественного выбора в зависимости от установленного значения
	$("ul.picker").each(function() {
		stylizePicker($(this));
	});
	
	// Выбор типа путешествия
	$(".trip-selector li a").click(function() {
		var formType = $(this).parents("form").attr("id").replace("form-", "");
		var tripType = $(this).parents("li").first().attr("class").replace(/\s|current/g, "");
		switch (tripType) {
			case _tripType.one:
				$("#complex").addClass("hidden");
	    		$("#simple").removeClass("hidden");
		    	$("#" + formType + "-to-date").attr("disabled", "disabled").val("–").parents(".field").addClass("disabled");
				$("#" + formType + "-to-time").attr("disabled", "disabled");
				
				$(this).parents("form").find(".icon-route").removeClass(_tripType.rnd);
				break
			case _tripType.rnd:
				$("#complex").addClass("hidden");
		    	$("#simple").removeClass("hidden");
		      	var toDate = $("#" + formType + "-from-date").datepicker("getDate");
				toDate.setDate(toDate.getDate() + 7);
				$("#" + formType + "-to-date").removeAttr("disabled").datepicker("setDate", toDate).parents(".field").removeClass("disabled");
				$("#" + formType + "-to-time").removeAttr("disabled");
				
				$(this).parents("form").find(".icon-route").addClass(_tripType.rnd);
				break
			case _tripType.route:
		        $("#simple").addClass("hidden");
		        $("#complex").removeClass("hidden");   
				var firstSegRoute = $("#complex .segments .segment").first().find("input.airport");
				// Перенос даты и времени вылета для первого сегмента
				if (firstSegRoute.first().is(".fade") || $("#" + formType + "-from").val() == firstSegRoute.first().val()) {
					$("#complex .segments .segment").first().find(".date .input").datepicker("setDate", $("#" + formType + "-from-date").datepicker("getDate")).end().find(".time").val($("#simple .time").first().val());
				}		
				// Перенос пункта вылета для первого сегмента
				if (!$("#" + formType + "-from").is(".fade") && firstSegRoute.first().is(".fade")) {
					firstSegRoute.first().val($("#" + formType + "-from").val()).removeClass("fade").change().siblings(".code").html($("#" + formType + "-from").siblings("[type=hidden]").first().val()).siblings("[type=hidden]").first().val($("#" + formType + "-from").siblings("[type=hidden]").first().val());
				}
				// Перенос пункта прилета для первого сегмента
				if (!$("#" + formType + "-to").is(".fade") && firstSegRoute.last().is(".fade")) {
					firstSegRoute.last().val($("#" + formType + "-to").val()).removeClass("fade").change().siblings(".code").html($("#" + formType + "-to").siblings("[type=hidden]").first().val()).siblings("[type=hidden]").first().val($("#" + formType + "-to").siblings("[type=hidden]").first().val());
				}
		}
		$("#" + formType + "-type").val(tripType);
	});
	
		
	// Отображение/скрытие выпадающих блоков с выбором у "текстовых" полей
	$("body").on("click", ".visual", function(e) {		
		var box = $(this).siblings(".visual-box");
		var control = $(this).parents(".input-container").find("a.visual").first();
		
		if (box.is(".hidden")) {
			// Скрытие прочих выпадающих блоков
			$(".visual-box").not(".hidden").addClass("hidden").parents(".field").removeClass("z-100");
			
			box.removeClass("hidden").parents(".field").addClass("z-100");
			control.addClass("selected");
		} else {
			box.addClass("hidden").parents(".field").removeClass("z-100");
			control.removeClass("selected");
		}
		box.click(function(e) {
			e.stopPropagation();
		});
		$("html").on("click", "body", function(e){
			box.addClass("hidden").parents(".field").removeClass("z-100");
			control.removeClass("selected");
			e.stopPropagation();
		});
		return false;
	}).on("keydown", ".visual", function() { return false }).on("contextmenu", ".visual", function(){ return false }).on("change", ".visual", function() {
		// Предотвращение выбора перевозчиков на основе естественного поведения поля ввода
		var expectedStr = $(this).is(".carriers") ? carriersString($(this)) : passengersString($(this));
		if ($(this).val() != expectedStr) $(this).val(expectedStr);
	});
	
	
	
	
	// Подгрузка городов/штатов при выборе страны из списка
	$(".visual-box select.country").change(function () {
	     var countrySelect = $(this);
		var hasStates = false;
		var country = $(this).val();
		$(this).siblings(".has-states").each(function() {
			if ($(this).val() == country) {
				hasStates = true;
				return false;
			}
		});
		
		var statesSelect = $(this).parents(".visual-box").find("select.state").first();
		
		if (hasStates) {
			// Подгрузка штатов для страны
			$.ajax({
				url: 'Controls/GetStateForCountry/' + $(countrySelect).val(),
				dataType: "json",
				success: function(data) {
					$(statesSelect).parents(".field").first().removeClass("hidden"); 
					$(statesSelect).find("option").remove();
					for (i = 0; i < data.length; i++) {
						statesSelect.append($("<option />").val(data[i].code).text(data[i].label));
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log("Не удалось обработать запрос на сервере. Попробуйте еще раз через некоторое время.");
				}
			});

	
	} else {
			$(statesSelect).parents(".field").first().addClass("hidden"); 
		}
		
		if ($(this).val() != "") {
			$(this).parents(".visual-box").find(".airport").removeAttr("disabled");

		// Подгрузка городов для страны
			$.ajax({
				url: 'Controls/GetAirportForCountry/' + $(countrySelect).val(),
				dataType: "json",
				success: function(data) {
					var airportSelect = $(countrySelect).parents(".visual-box").find("select.airport").first();
					$(airportSelect).find("option").remove();
					for (i = 0; i < data.length; i++) {
						airportSelect.append($("<option />").val(data[i].code).text(data[i].label));
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log("Не удалось обработать запрос на сервере. Попробуйте еще раз через некоторое время.");
					console.log(jqXHR, textStatus, errorThrown);
				}
			});	
		} else {
			$(this).parents(".visual-box").find(".airport").val("").attr("disabled", "disabled");
		}
	});
	
	
	// Подгрузка городов при выборе штата из списка
	$(".visual-box select.state").change(function () {
		var countrySelect = $(this).parents(".visual-box").find("select.country").first();
		var stateSelect = $(this);
		var airportSelect = $(this).parents(".visual-box").find("select.airport").first();
		$.ajax({
			url: 'Controls/GetAirportForState/' + $(countrySelect).val() + '/' + $(stateSelect).val(),
			dataType: "json",
			success: function(data) {
				$(airportSelect).find("option").remove();
				for (i = 0; i < data.length; i++) {
					airportSelect.append($("<option />").val(data[i].code).text(data[i].label));
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				cancelWait();
				showMessage("<span class='alert'>Ошибка</span>", "Не удалось обработать запрос на сервере. Попробуйте еще раз через некоторое время.");
			}
		});	
	});
	
	// Подстановка в поле аэропорта и страны после выбора
	$(".visual-box .airport").change(function() {
		if ($(this).val() != "") {
			var state = $(this).parents(".visual-box").find('.field:visible .state option:selected').html();
			state = (state != undefined) && ($(this).parents(".visual-box").find('.state').val() != "") ? state + ", " : "";
			$(this).parents(".input-container").find("input.airport").removeClass("fade").removeClass("error").val($(this).find('option:selected').html() + ", " + state + $(this).parents(".visual-box").find('.country option:selected').html()).keypress().siblings(".code").html($(this).val()).siblings(".btn-x").removeClass("hidden").siblings(":hidden").val($(this).val());
			$(this).parents(".visual-box").addClass("hidden").siblings("a.visual").removeClass("btn-x");
		}
	});
	// Корректировка даты обратного вылета
	$(".date.from .input").change(function() {
		if ($(this).parents("form").find("input.trip-type").val() == _tripType.rnd) {
			adjustToDate($(this));
			validateToDate($(this));
		}
	});
	
	// Валидация выбранной даты обратного вылета
	$(".date.to .input").change(function() {
		if ($(this).parents("form").find("input.trip-type").val() == _tripType.rnd) {
			validateToDate($(this).parents("form").find(".date.from .input").first());
		}
	});
		
	
	
	// Управление списками количественного выбора
	$("ul.picker li a").hover(function() {		
		if ($(this).is(".none")) {
			var setNumber = $(this).parents(".picker").siblings("input").val();
			$(this).parents(".picker").find("a").each(function() {
				if ($(this).text() <= setNumber) {
					$(this).addClass("fade");
				}
			});
		} else {
			var curNumber = $(this).text();
			$(this).parents(".picker").find("a").each(function() {
				if ($(this).text() < curNumber) {
					$(this).removeClass("set").addClass("less");
				} else {
					if ($(this).text() > curNumber) {
						$(this).removeClass("less set");
					} else {
						$(this).addClass("set");
					}
				}
			});
		}
	}).mouseleave(function() {
		stylizePicker($(this).parents(".picker"));
	}).click(function() {
		$(this).parents(".picker").siblings("input").val($(this).text());
		setBriefPassengers($(this).parents(".input-container").find(".passengers"));
		return false;
	});
	
	
	// Установка значений по умолчанию для пассажиров
	$(".input.passengers").siblings(".btn-x").click(function() {
		resetPassengers($(this).parents("form").attr("id").replace("form-", ""));
	});	
	// Отслеживание и активация/дезактивация полей выбора перевозичков
	$(".input.carriers").each(function() {
		var input = $(this);
		$(this).siblings(".visual-box").find(".input").first().change(function() {
			if ($(this).val() != 0) {
				$(this).parent(".field").next(".field").removeClass("disabled").find(".input").removeAttr("disabled");
			} else {
				$(this).parent(".field").siblings(".field").addClass("disabled").find(".input").val(0).attr("disabled", "disabled");
			}
			setBriefCarriers(input);
		}).parent(".field").next(".field").find(".input").change(function() {
			if ($(this).val() != 0) {
				$(this).parent(".field").next(".field").removeClass("disabled").find(".input").removeAttr("disabled");
			} else {
				$(this).parent(".field").next(".field").addClass("disabled").find(".input").val(0).attr("disabled", "disabled");
			}
			setBriefCarriers(input);
		}).parent(".field").next(".field").find(".input").change(function() {
			setBriefCarriers(input);
		});
	});
	
	// Установка значений по умолчанию для перевозчиков
	$(".input.carriers").siblings(".btn-x").click(function() {
		resetCarriers($(this).parents("form").attr("id").replace("form-", ""));
	});
	// Отображение класса в кратких сведениях поисковой формы
	$(".search-form .input.class").change(function() {
		setBriefClass($(this));
	});
	
	// Отображение прямого перелета в кратких сведениях поисковой формы
	$(".search-form input.straight").change(function() {
		if ($(this).is(":checked")) {
			$(this).parents("form").find(".etc .straight").removeClass("hidden");
		} else {
			$(this).parents("form").find(".etc .straight").addClass("hidden");
		}
	});
	
	// Сброс формы поиска в начальное состояние
	$(".search-form .btn.clear").click(function() {
		$(this).parents("form").find(".input").removeClass("error");
		$(this).parents("form").find(".input.clearable").siblings(".btn-x").addClass("hidden");
		var formType = $(this).parents("form").attr("id").replace("form-", ""); 
		
		$("#" + formType + "-from").val($("#" + formType + "-from").data("default-value")).addClass("fade").siblings(".code").html("").siblings("[type=hidden]").val("");
		$("#" + formType + "-from-date").datepicker("setDate", "0");
		$("#" + formType + "-from-time").val(0);
		
		$("#" + formType + "-to").val($("#" + formType + "-to").data("default-value")).addClass("fade").siblings(".code").html("").siblings("[type=hidden]").val("");
		if ($("#" + formType + "-type").val() == _tripType.rnd) {
			$("#" + formType + "-to-date").datepicker("setDate", "+7");
			$("#" + formType + "-to-time").val(0);
		}
		
		resetPassengers(formType);

		resetCarriers(formType);
		
		$("#" + formType + "-class").val(0);
		$(".etc .class").text($("#" + formType + "-class :selected").text());
		
		$("#" + formType + "-straight").removeAttr("checked");
		$(".etc .straight").addClass("hidden");
		
		$("#" + formType + "-days").val(0);
		$(".etc .flexible").addClass("hidden");
	});
});

// Валидация даты
function validateToDate(fromDateInput) {
	var correct = true;
	var toDateInput = fromDateInput.parents("form").find(".date.to .input").first();
	if (fromDateInput.parents("form").find("input.trip-type").val() == _tripType.rnd && fromDateInput.datepicker("getDate") > toDateInput.datepicker("getDate")) {
		toDateInput.addClass("error").parents(".input-container").effect("pulsate", {times:4}, 1000);
		correct = false;
	} else {
		toDateInput.removeClass("error");
	}
	return correct;
}

// Установка даты обратного вылета не ранее прямого вылета
function adjustToDate(fromDateInput) {
	var toDateInput = fromDateInput.parents("form").find(".date.to .input").first();
	var toDate = $.datepicker.parseDate(_readableDateFormat, fromDateInput.val());
	if (toDateInput.datepicker("getDate") < toDate) {
		toDateInput.datepicker("setDate", toDate);
	}
}


// Проверка на значение по умолчанию
function carriersIsDefault(input) {
	return $(input).siblings(".visual-box").find("select").first().val() == "";
}
// Подстановка краткого перечня перевозчиков при выборе
function setBriefCarriers(input) {
	var str = carriersString(input);
	$(input).siblings(".visual-box").siblings(".input").val(str);
	$(input).parents("form").find(".etc .carrier").text(str);
	if (carriersIsDefault(input)) {
		$(input).siblings(".btn-x").addClass("hidden");
	} else {
		$(input).siblings(".btn-x").removeClass("hidden");
	}}

// Формирование краткого перечня перевозчиков на основе выбранного
function carriersString(input) {
	var str = "";
	$(input).siblings(".visual-box").find(".input:enabled").each(function() {
		if (($(this).val() != 0) && $(this).val() != "") str += (str.length > 0) ? " / " + $(this).find(":selected").text() : $(this).find(":selected").text();
	});
	if (str == "") str = $(input).siblings(".visual-box").find(".input:enabled").first().find(":selected").text();
	return str;
}


// Установка значений по умолчанию для перевозчиков
function resetCarriers(formType) {
	$("#" + formType + "-carriers").siblings(".visual-box").find(".input").first().val("").parent(".field").siblings(".field").addClass("disabled").find(".input").val("").attr("disabled", "disabled");
	setBriefCarriers($("#" + formType + "-carriers"));
}

// Проверка на значение по умолчанию
function passengersIsDefault(input) {
	var isDefault = true;
	$(input).siblings(".visual-box").find("input").each(function() {
		if ((!$(this).is(".adults") && $(this).val() != 0) || ($(this).is(".adults") && $(this).val() == 0)) {
			isDefault = false;
			return false;
		}
	});
	return isDefault;
}
// Подстановка в поле краткого перечня пассажиров при выборе
function setBriefPassengers(input) {
	str = passengersString(input)
	if (str.length == 0) {
		$(input).addClass("fade").val("—");
	} else {
		$(input).removeClass("fade").val(str);
	}
	validatePassengers(input);
	$(input).parents("form").find(".etc .psg").text(str);
	if (passengersIsDefault(input)) {
		$(input).siblings(".btn-x").addClass("hidden");
	} else {
		$(input).siblings(".btn-x").removeClass("hidden");
	}}

// Формирование краткого перечня пассажиров на основе выбранного
function passengersString(input) {
	var str = "";
	$(input).siblings(".visual-box").find("input").each(function() {
		if ($(this).val() != 0) {
			var cat = "";
			switch ($(this).attr("class"))	{
				case "infants-noseat":
					cat = "мл. б/м";
					break;
				case "infants":
					cat = "мл.";
					break;
				case "kids":
					cat = "дет.";
					break;
				case "youth":
					cat = "мол.";
					break;
				case "adults":
					cat = "взр.";
					break;
				case "old":
					cat = "пож.";
					break;
			}
			str += (str.length > 0) ? " + " + $(this).val() + " " + cat : $(this).val() + " " + cat
		}
	});
	return str;
}

// Валидация количества пассажиров
function validatePassengers(input) {
	var correct = true;
	msg = "";
	// Проверка общего кол-ва
	var sum = 0;
	$(input).siblings(".visual-box").find("input").each(function() {
		sum += parseInt($(this).val());
	});
	if (sum > 6) {
		msg += "Всего должно быть не более 6 пассажиров. ";
		correct = false;
	} else {
		if (sum == 0) {
			msg += "Кто-то все же должен лететь. ";
			correct = false;
		}
	}
	// Проверка кол-ва младенцев
	if (parseInt($(input).siblings(".visual-box").find(".infants").first().val()) > (parseInt($(input).siblings(".visual-box").find(".adults").first().val()) + parseInt($(input).siblings(".visual-box").find(".youth").first().val()) + parseInt($(input).siblings(".visual-box").find(".old").first().val()))) {
		msg += "Младенцев не должно быть больше, чем молодежи, взрослых и пожилых в сумме. "
		correct = false;
	}
	if (parseInt($(input).siblings(".visual-box").find(".infants-noseat").first().val()) > (parseInt($(input).siblings(".visual-box").find(".adults").first().val()) + parseInt($(input).siblings(".visual-box").find(".youth").first().val()) + parseInt($(input).siblings(".visual-box").find(".old").first().val()))) {
		msg += "Младенцев без места не должно быть больше, чем молодежи, взрослых и пожилых в сумме. "
		correct = false;
	}
	// Сигнализация об ошибках
	if (correct) {
		$(input).removeClass("error");
		$(input).siblings(".visual-box").find(".error").addClass("hidden");
	} else {
		$(input).addClass("error");
		$(input).siblings(".visual-box").find(".error").removeClass("hidden").find(".message").html(msg);
	}
	return correct;
}

// Установка значений по умолчанию для пассажиров
function resetPassengers(formType) {
	$("#" + formType + "-psg").siblings(".visual-box").find("input").val(0);
	$("#" + formType + "-psg-adults").val(1);
	setBriefPassengers($("#" + formType + "-psg"));
	$("#" + formType + "-psg").siblings(".visual-box").find(".picker").each(function() {
		stylizePicker($(this));
	});
}
// Отображение элементов списка количественного выбора в зависимости от установленного значения
function stylizePicker(elem) {
	var setNumber = elem.siblings("input").val();
	elem.find("a").each(function() {
		if ($(this).text() < setNumber) {
			$(this).removeClass("set fade").addClass("less");
		} else {
			if ($(this).text() > setNumber) {
				$(this).removeClass("less set fade");
			} else {
				$(this).removeClass("fade").addClass("set");
			}
		}
	});
}

// Подстановка класса перелета в краткое описание формы
function setBriefClass(elem) {
	elem.parents("form").find(".etc .class").text(elem.find(":selected").text());
}
//var availableTags = [
//	{label: "Киев, Борисполь, Украина", code: "KBP"}, 
//	{label: "Москва, Россия", code: "MOW"}, 
//	{label: "Нью-Йорк, США", code: "NYC"}
//];

// Привязка автозаполнения аэропорта
function bindAutocomplete(elem) {
	$(elem).autocomplete({
		 source: function(request, response) {
		 	$.ajax({
		 		url: "/Controls/LookupCity",
		 		type: "POST",
		 		dataType: "json",
		 		data: {
		 			searchText: request.term,
		 			maxResults: 10
		 		},
		 		success: function(data) {
		 			response($.map(data, function(item) {
		 				return {
		 					label: item.label,
		 					value: item.label,
		 					code: item.code
		 				}
		 			}));
		 		}
		 	});
		 },
		//source: availableTags,
		minLength: 0,
		delay: 200,
		select: function (event, ui) {
			$(elem).val(ui.item.label).siblings(".code").html(ui.item.code).siblings("[type=hidden]").val(ui.item.code);
			$(elem).change();
			$(elem).autocomplete("destroy");
			bindAutocomplete(elem);
		},
		response: function( event, ui ) {
			if (ui.content.length == 1) {
				$(elem).val(ui.content[0].label).siblings(".code").html(ui.content[0].code).siblings("[type=hidden]").val(ui.content[0].code);
				
				// DIRTY DIRTY BUG FIX
				$(elem).autocomplete("close");
				$(elem).change();
				
				return false;
			}
		}
	}).data("ui-autocomplete")._renderItem = function(ul, item) {
		return $( "<li>" ).append("<a><span class='small code'>" + item.code + "</span> " + item.label + "</a>" ).appendTo(ul);
	}
	$(elem).data("ui-autocomplete")._resizeMenu = function () {
        var ul = this.menu.element;
        ul.outerWidth(this.element.outerWidth());
	};
}

