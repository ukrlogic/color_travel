$(function() {
	// Отображение гибкого поиска в кратких сведениях поисковой формы
	$("#book-days").change(function() {
		if ($(this).val() != 0) {
			$("#form-book .etc .flexible").removeClass("hidden").find("span").text($(this).val());
		} else {
			$("#form-book .etc .flexible").addClass("hidden");
		}
	});

    // Добавление сегментов
    $("#complex .add").click(function() {
		var addedSeg = $("#complex .segment.hidden").first();
		addedSeg.removeClass("hidden").find(".date .input").datepicker("setDate", addedSeg.prev(".segment").find(".date .input").datepicker("getDate"));
		if (addedSeg.find("input.airport").first().is(".fade") && !addedSeg.prev(".segment").find("input.airport").last().is(".fade")) addedSeg.find("input.airport").first().val(addedSeg.prev(".segment").find("input.airport").last().val()).removeClass("fade").siblings(".code").html(addedSeg.prev(".segment").find("input.airport").last().siblings(".code").first().html()).siblings(".btn-x").removeClass("hidden").siblings("[type=hidden]").first().val(addedSeg.prev(".segment").find("input.airport").last().siblings("[type=hidden]").first().val());
		
		$("#segments-count").val(parseInt($("#segments-count").val()) + 1);
		$("#complex .remove").removeClass("hidden");
        if ($("#complex .segment.hidden").length == 0) $(this).addClass("hidden");
		return false;
    });

    // Удаление сегментов
    $("#complex .remove").click(function() {
        $("#complex .segment").not(".hidden").last().addClass("hidden");
		$("#segments-count").val(parseInt($("#segments-count").val()) - 1);
        $("#complex .add").removeClass("hidden");
		if ($("#complex .segment").not(".hidden").length == 1) $(this).addClass("hidden");
 		return false;
    });
	
	// Корректировка дат для сегментов
	$(".segment .date").on("change", ".input", function() {
		var setDate = $(this).datepicker("getDate");
		$(this).parents(".segment").nextAll(".segment").find(".date .input").each(function() {
			if (setDate > $(this).datepicker("getDate")) $(this).datepicker("setDate", setDate);
		})
	});

    // Сброс формы в начальное состояние
    $("#book-clear").click(function() {
        $(".segments .segment .half input.input").each(function() {
			$(this).val($(this).data("default-value")).addClass("fade").siblings(".code").html("").siblings("[type=hidden]").val("").siblings(".btn-x").addClass("hidden");
		});
        $(".segments .segment .date .input").datepicker("setDate", "0");
        $(".segments .segment .time").val(0);

		$(".segments .segment").first().siblings(".segment").addClass("hidden");
		$("#complex .remove").addClass("hidden");
		$("#segments-count").val("1");
    });
	
	
	
	


	// Разделение вариантов перелета по цвету
	$(".variant .details table .option:nth-child(odd)").addClass("odd");

	// Подсвечивание выбранного варианта перелета по клику
	$(".variant").on("click", ".option", function() {
		if ($(this).siblings(".option").length > 0) {
			$(this).addClass("selected").siblings(".option").removeClass("selected");
			$(this).find(".radio input").attr("checked", true);
		}
	});
	
	
	
	
	

    // Массовая активация фильтра
    $(".filter .all").click(function() {
        $(this).parents(".filter").find(".list :checkbox").attr("checked", "checked");
        return false;
    });

    // Массовая дезактивация фильтра
    $(".filter .none").click(function() {
        $(this).parents(".filter").find(".list :checkbox").removeAttr("checked");
        return false;
    });

    // Создание и управление диапазонами времени
    $(".time-range .slider").slider({
        range: true,
        min: 0,
        max: 100,
        values: [0, 100],
        change: function(event, ui) {
            var parent = $(this).parents(".time-range").first();
            var minval = parent.find("input.min").first().val();
            var min = minval.split(":")[0] * 60 + minval.split(":")[1] * 1;
            var maxval = parent.find("input.max").first().val();
            var max = maxval.split(":")[0] * 60 + maxval.split(":")[1] * 1;
			
            var tScale = (max - min) / 100;
            var pScale = parent.find("slider").first().width() / 100;
			
            var _newMin = min + Math.floor(ui.values[0] * tScale);
            var _hh = Math.floor(_newMin / 60).toString();
            var _mm = (_newMin - Math.floor(_newMin / 60) * 60).toString();
            var newMin = doubleStrNum(_hh) + ":" + doubleStrNum(_mm);			
            parent.find("span.min").html(newMin).siblings("input.range-min").val(newMin);
			
            var _newMax = max - Math.floor((100 - ui.values[1]) * tScale);
            _hh = Math.floor(_newMax / 60).toString();
            _mm = (_newMax - Math.floor(_newMax / 60) * 60).toString();
            var newMax = doubleStrNum(_hh) + ":" + doubleStrNum(_mm);
            parent.find("span.max").html(newMax).siblings("input.range-max").val(newMax);
			
            function doubleStrNum(n) {
                return (n.length == 1) ? ("0" + n) : n;
            }
        }
    });

    // Массовое восстановление диапазонов времени
    $(".filter .reset-range").click(function() {
        $(this).parents(".filter").find(".slider").each(function() {
            $(this).slider("values", [0, 100])
        });
        return false;
    });

    // Активация/дезактивация автоматического применения фильтров
    $("#filter-autoapply").change(function() {
        var btn = $(this).siblings(".btn")
        if ($(this).is(":checked")) {
            btn.attr("disabled", "disabled").addClass("disabled");
        } else {
            btn.removeAttr("disabled").removeClass("disabled");
        }
    });
			
	// Доступ к предыдущим поисковым запросам
	$("#previous-search .field a").click(function() {
		$(this).parents(".field").find("input").removeAttr("disabled").end().siblings(".field").find("input").attr("disabled", "disabled");
		$("#previous-search").submit();
		return false;
	});
});
