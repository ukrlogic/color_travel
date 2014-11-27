$(function() {	
	// Валидация формы бронирования
	$("#form-book").submit(function() {
		$(this).find(".input").removeClass("error");
		var allowed = true;
		switch ($("#book-type").val()) {
			case _tripType.route:
				// Проверка наличия пунктов вылета/прилета
				$(".segments .segment").not(".hidden").find(".half input.input").each(function() {
					if (!$(this).val() || $(this).hasClass("fade")) {
						allowed = false;
						$(this).addClass("error").parents(".input-container").effect("pulsate", {times:3}, 300);
					}
				});
				// Проверка совпадения пунктов туда/обратно
				$(".segments .segment").not(".hidden").each(function() {
					var from = $(this).find(".half").first().find("input.input");
					var to = $(this).find(".half").last().find("input.input");
					if (!from.hasClass("fade") && (from.val() == to.val())) {
						allowed = false;
						to.addClass("error").parents(".input-container").effect("pulsate", {times:3}, 300);
					}
				});
				// Проверка последовательности дат вылета
				$(".segments .segment").not(".hidden").find(".form-side .date .input").each(function() {
					if ($(this).datepicker("getDate") < $(this).parents(".segment").prev(".segment").find(".form-side .date .input").datepicker("getDate")) {
						allowed = false;
						$(this).addClass("error").parents(".input-container").effect("pulsate", {times:3}, 300);
					}
				});
				break;
			default:
				// Проверка наличия пункта вылета
				if (!$("#book-from").val() || $("#book-from").hasClass("fade")) {
					allowed = false;
					$("#book-from").parents(".input").addClass("error").parents(".input-container").effect("pulsate", {times:3}, 300);
				}
				// Проверка наличия пункта прилета
				if (!$("#book-to").val() || $("#book-to").hasClass("fade")) {
					allowed = false;
					$("#book-to").addClass("error").parents(".input-container").effect("pulsate", {times:3}, 300);
				}
				// Проверка совпадения пунктов туда/обратно
				if (!$("#book-from").hasClass("fade") && ($("#book-from").val() == $("#book-to").val())) {
					allowed = false;
					$("#book-to").addClass("error").parents(".input-container").effect("pulsate", {times:3}, 300);
				}
				// Проверка дат вылета туда/обратно
				if (!validateToDate($("#book-from-date"))) {
					allowed = false;
				}
				break;		
		}
		// Проверка количества пассажиров
		if (!validatePassengers($("#book-psg"))) { 
			allowed = false;
			$("#book-psg").addClass("error").parents(".input-container").effect("pulsate", {times:3}, 300);
		}
		if (allowed && ((typeof _noBookWait === "undefined") || (_noBookWait == false))) startWait("Идет поиск...", true);
		return allowed;
	});
});
