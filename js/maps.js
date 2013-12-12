function init2 () {
    // Создание экземпляра карты и его привязка к созданному контейнеру
    var office2 = new YMaps.Map(YMaps.jQuery("#office2")[0]);
    // Установка для карты ее центра и масштаба
    office2.setCenter(new YMaps.GeoPoint(36.242503,49.98922), 16);
    
    var s = new YMaps.Style();
    s.balloonContentStyle = new YMaps.BalloonContentStyle(
        new YMaps.Template('<div><div style="font-size:15px;font-weight:bold;margin-bottom:7px;">$[metro]</div><div style="color:green;font-size:10px;">$[botsad]<span style="color:gray;">$[botsad_location]</span></div><div style="color:green;font-size:10px;">$[august23]<span style="color:gray;">$[august23_location]</span></div><div style="color:green;font-size:10px;">$[nauchnaya]<span style="color:gray;">$[nauchnaya_location]</span></div></div>')
    );
    var placemark = new YMaps.Placemark(new YMaps.GeoPoint(36.242503,49.98922), {style: s} );
    placemark.metro = "Метро рядом";
    placemark.botsad = "Советская ";
    placemark.botsad_location = "847 м";
    
    placemark.august23 = "Проспект Гагарина ";
    placemark.august23_location = "961 м";
    
    placemark.nauchnaya = "Архитектора Бекетова ";
    placemark.nauchnaya_location = "1,006 км";
    
    office2.addOverlay(placemark);    
    placemark.openBalloon();
    
    // Создание элемента управления "Информация"
    var informationControl2 = new InformationControl2();
    
    // Создание новой кнопки
    // Добавляем ее к стандартной группе кнопок на панеле инструментов
    var buttonInformation2 = new YMaps.ToolBarRadioButton(YMaps.ToolBar.DEFAULT_GROUP, {
        caption: "Информация"
    });

    // Включение/выключение инструмента "Информация"
    YMaps.Events.observe(buttonInformation2, buttonInformation2.Events.Select, function () {
        office2.addControl(informationControl);
    });  

    YMaps.Events.observe(buttonInformation2, buttonInformation2.Events.Deselect, function () {
        office2.removeControl(informationControl2);
    });

    // Добавление элементов управления на карту
    var toolBar = new YMaps.ToolBar();
    toolBar.add(buttonInformation2);
    office2.addControl(toolBar);
    office2.addControl(new YMaps.Zoom());
    office2.enableScrollZoom();
    
    // миникарта
	office2.addControl(new YMaps.MiniMap(),
		new YMaps.ControlPosition(
			YMaps.ControlPosition.BOTTOM_RIGHT,
			new YMaps.Size (220, 5)
		)
	);
}

// Элемент управления "Информация"
function InformationControl2 () {
    var geoResult, clickPlace, listener, office2;

    // Вызывается при добавлении элемента управления на карту
    this.onAddToMap = function (parentMap) {
        office2 = parentMap;

        office2.addCursor(YMaps.Cursor.HELP);

        // Создание обработчика кликов по карте
        listener = YMaps.Events.observe(office2, office2.Events.Click, function (office2, mEvent) {

            // Координаты клика мышью
            var clickPoint  = mEvent.getGeoPoint();

            // Удаляем предыдущие результаты (если они были добавлены на карту)
            if (geoResult) {
                office2.removeOverlay(geoResult);
                result = null;
            }

            if (clickPlace) {
                office2.removeOverlay(clickPlace);
                clickPlace = null;
            }

            // Отмечаем точку по которой щелкнул пользователь
            clickPlace = new YMaps.Placemark(clickPoint, {style: anchorStyle});
            clickPlace.description = clickPoint.toString();
            office2.addOverlay(clickPlace);

            // Запуск процесса геокодирования
            this.geocode(clickPoint);
        }, this);
    }

    // Геокодирует точку
    this.geocode = function (clickPoint) {
        // Выключаем обработчиков событий, чтобы к геокодеру ушло не более одного запроса
        // (по окончанию геокодированияю включаем обработчик вновь)
        listener.disable();

        // Запуск процесса геокодирования
        var geocoder = new YMaps.Geocoder(clickPoint);

        // Обработчик успешного завершения геокодирования
        YMaps.Events.observe(geocoder, geocoder.Events.Load, function (geocoder) {
            // Получение результата поиска
            geoResult = this.getResult(geocoder);

            if (geoResult) {
                geoResult.setStyle(markStyle);

                // Задаем содержимое балуна
                var sep = ', ',
                    names = (geoResult.text || '').split(sep),
                    index = geoResult.kind === 'house' ? -2 : -1;
                geoResult.setBalloonContent("<b>" + names.slice(index).join(sep) + "</b><div>" + names.slice(0, index).join(sep) + "</div>");

                // Открываем балун
                office2.addOverlay(geoResult);
                geoResult.openBalloon();
            } else {
                alert("Ничего не найдено!");
            }

            // Включаем обработчик кликов по карте
            listener.enable();
        }, this);

        // Обработчик неудачного геокодирования
        YMaps.Events.observe(geocoder, geocoder.Events.Fault, function (geocoder, err) {
            alert("Произошла ошибка при геокодировании: " + err);

            // Включаем обработчик кликов по карте
            listener.enable();
        });
    }

    // Возвращает результат различной точности в зависимости от масштаба
    this.getResult = function (geocoder) {
        // Точность: город, страна
        function isOther (result) {
            return result.precision == "other";
        }

        // Точность: улица
        function isStreet (result) {
            return result.precision == "street";
        }

        // Точность: дом
        function isHouse (result) {
            return !isOther(result) && !isStreet(result);
        };

        // Выбор точности поиска
        var filter = isHouse;
        if (office2.getZoom() < 10) {
            filter = isOther;
        } else if (office2.getZoom() < 15) {
            filter = isStreet;
        }

        // Возвращает первый найденный результат с нужной точностью
        return geocoder.filter(filter)[0];
    }

    // Вызывается при удалении элемента управления с карты
    this.onRemoveFromMap = function () {
        office2.removeCursor(YMaps.Cursor.HELP);

        // Удаляем метки с карты, если они были добавлены
        if (geoResult) {
            office2.removeOverlay(geoResult);
        }

        if (clickPlace) {
            office2.removeOverlay(clickPlace);
        }

        // Удаляем обработчик кликов по карте
        listener.cleanup();

        mapoffice2= geoResult = clickPlace = listener = null;
    }

    // Создадим стили для значков
    var anchorStyle = new YMaps.Style();
    anchorStyle.iconStyle = new YMaps.IconStyle();
    anchorStyle.iconStyle.href = "http://maps.yandex.ru/css/b-location-balloon/b-location-balloon.anchor.png";
    anchorStyle.iconStyle.size = new YMaps.Point(18, 18);
    anchorStyle.iconStyle.offset = new YMaps.Point(-9, -18);

    var markStyle = new YMaps.Style();
    markStyle.iconStyle = new YMaps.IconStyle();
    markStyle.iconStyle.href = "http://maps.yandex.ru/css/b-location-balloon/b-location-balloon.mark.png";
    markStyle.iconStyle.size = new YMaps.Point(21, 19);
    markStyle.iconStyle.offset = new YMaps.Point(-1, -18);
}

function init1 () {
    // Создание экземпляра карты и его привязка к созданному контейнеру
    var map = new YMaps.Map(YMaps.jQuery("#office1")[0]);
    // Установка для карты ее центра и масштаба
    map.setCenter(new YMaps.GeoPoint(36.222408,50.029819), 16);        
    var s = new YMaps.Style();
    s.balloonContentStyle = new YMaps.BalloonContentStyle(
        new YMaps.Template('<div><div style="font-size:15px;font-weight:bold;margin-bottom:7px;">$[metro]</div><div style="color:green;font-size:10px;">$[botsad]<span style="color:gray;">$[botsad_location]</span></div><div style="color:green;font-size:10px;">$[august23]<span style="color:gray;">$[august23_location]</span></div><div style="color:green;font-size:10px;">$[nauchnaya]<span style="color:gray;">$[nauchnaya_location]</span></div></div>')
    );
    var placemark = new YMaps.Placemark(new YMaps.GeoPoint(36.222408,50.029819), {style: s} );
    placemark.metro = "Метро рядом";
    placemark.botsad = "Ботанический сад ";
    placemark.botsad_location = "237 м";
    
    placemark.august23 = "23 августа ";
    placemark.august23_location = "507 м";
    
    placemark.nauchnaya = "Научная ";
    placemark.nauchnaya_location = "1,849 км";
    
    map.addOverlay(placemark);    
    placemark.openBalloon();

    // Создание элемента управления "Информация"
    var informationControl = new InformationControl1();
    
    // Создание новой кнопки
    // Добавляем ее к стандартной группе кнопок на панеле инструментов
    var buttonInformation = new YMaps.ToolBarRadioButton(YMaps.ToolBar.DEFAULT_GROUP, {
        caption: "Информация"
    });

    // Включение/выключение инструмента "Информация"
    YMaps.Events.observe(buttonInformation, buttonInformation.Events.Select, function () {
        map.addControl(informationControl);
    });  

    YMaps.Events.observe(buttonInformation, buttonInformation.Events.Deselect, function () {
        map.removeControl(informationControl);
    });

    // Добавление элементов управления на карту
    var toolBar = new YMaps.ToolBar();
    toolBar.add(buttonInformation);
    map.addControl(toolBar);
    map.addControl(new YMaps.Zoom());
    map.enableScrollZoom();
    
    // миникарта
	map.addControl(new YMaps.MiniMap(),
		new YMaps.ControlPosition(
			YMaps.ControlPosition.BOTTOM_RIGHT,
			new YMaps.Size (220, 5)
		)
	);
}
function InformationControl1 () {
    var geoResult, clickPlace, listener, map;

    // Вызывается при добавлении элемента управления на карту
    this.onAddToMap = function (parentMap) {
        map = parentMap;

        map.addCursor(YMaps.Cursor.HELP);

        // Создание обработчика кликов по карте
        listener = YMaps.Events.observe(map, map.Events.Click, function (map, mEvent) {

            // Координаты клика мышью
            var clickPoint  = mEvent.getGeoPoint();

            // Удаляем предыдущие результаты (если они были добавлены на карту)
            if (geoResult) {
                map.removeOverlay(geoResult);
                result = null;
            }

            if (clickPlace) {
                map.removeOverlay(clickPlace);
                clickPlace = null;
            }

            // Отмечаем точку по которой щелкнул пользователь
            clickPlace = new YMaps.Placemark(clickPoint, {style: anchorStyle});
            clickPlace.description = clickPoint.toString();
            map.addOverlay(clickPlace);

            // Запуск процесса геокодирования
            this.geocode(clickPoint);
        }, this);
    }

    // Геокодирует точку
    this.geocode = function (clickPoint) {
        // Выключаем обработчиков событий, чтобы к геокодеру ушло не более одного запроса
        // (по окончанию геокодированияю включаем обработчик вновь)
        listener.disable();

        // Запуск процесса геокодирования
        var geocoder = new YMaps.Geocoder(clickPoint);

        // Обработчик успешного завершения геокодирования
        YMaps.Events.observe(geocoder, geocoder.Events.Load, function (geocoder) {
            // Получение результата поиска
            geoResult = this.getResult(geocoder);

            if (geoResult) {
                geoResult.setStyle(markStyle);

                // Задаем содержимое балуна
                var sep = ', ',
                    names = (geoResult.text || '').split(sep),
                    index = geoResult.kind === 'house' ? -2 : -1;
                geoResult.setBalloonContent("<b>" + names.slice(index).join(sep) + "</b><div>" + names.slice(0, index).join(sep) + "</div>");

                // Открываем балун
                map.addOverlay(geoResult);
                geoResult.openBalloon();
            } else {
                alert("Ничего не найдено!");
            }

            // Включаем обработчик кликов по карте
            listener.enable();
        }, this);

        // Обработчик неудачного геокодирования
        YMaps.Events.observe(geocoder, geocoder.Events.Fault, function (geocoder, err) {
            alert("Произошла ошибка при геокодировании: " + err);

            // Включаем обработчик кликов по карте
            listener.enable();
        });
    }

    // Возвращает результат различной точности в зависимости от масштаба
    this.getResult = function (geocoder) {
        // Точность: город, страна
        function isOther (result) {
            return result.precision == "other";
        }

        // Точность: улица
        function isStreet (result) {
            return result.precision == "street";
        }

        // Точность: дом
        function isHouse (result) {
            return !isOther(result) && !isStreet(result);
        };

        // Выбор точности поиска
        var filter = isHouse;
        if (map.getZoom() < 10) {
            filter = isOther;
        } else if (map.getZoom() < 15) {
            filter = isStreet;
        }

        // Возвращает первый найденный результат с нужной точностью
        return geocoder.filter(filter)[0];
    }

    // Вызывается при удалении элемента управления с карты
    this.onRemoveFromMap = function () {
        map.removeCursor(YMaps.Cursor.HELP);

        // Удаляем метки с карты, если они были добавлены
        if (geoResult) {
            map.removeOverlay(geoResult);
        }

        if (clickPlace) {
            map.removeOverlay(clickPlace);
        }

        // Удаляем обработчик кликов по карте
        listener.cleanup();

        map = geoResult = clickPlace = listener = null;
    }

    // Создадим стили для значков
    var anchorStyle = new YMaps.Style();
    anchorStyle.iconStyle = new YMaps.IconStyle();
    anchorStyle.iconStyle.href = "http://maps.yandex.ru/css/b-location-balloon/b-location-balloon.anchor.png";
    anchorStyle.iconStyle.size = new YMaps.Point(18, 18);
    anchorStyle.iconStyle.offset = new YMaps.Point(-9, -18);

    var markStyle = new YMaps.Style();
    markStyle.iconStyle = new YMaps.IconStyle();
    markStyle.iconStyle.href = "http://maps.yandex.ru/css/b-location-balloon/b-location-balloon.mark.png";
    markStyle.iconStyle.size = new YMaps.Point(21, 19);
    markStyle.iconStyle.offset = new YMaps.Point(-1, -18);
}
