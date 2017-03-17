<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
$ext_id = trim ((!empty($_POST['ext_id'])) ? $_POST['ext_id'] : "" ); // 
$essence_type = trim ((!empty($_POST['essence_type'])) ? $_POST['essence_type'] : "" ); // 
/*---------------------------------------------------------------------------*/


if ($ext_id) {
					//$connLOC = ConnectLocalTIR(); // Присоска к базе
	$connSSD = ConnectOciDB('EGRP'); // Присоска к базе

switch ($essence_type){
	case "Субъект":
			$sql = "SELECT 	ee.id, ee.arch_id,
				ee.name \"Наименование\",
				ee.reg_doc_type,
				ee.reg_doc_org,
				ee.prop_type,
				ee.s_date \"Действительна с\",
				ee.e_date \"Действительна по\",
				ee.citizen \"Страна\",
				ee.con_desc \"Описание\",
				ee.status,
				ee.dept_id,
				ee.inserted \"Запись создана когда\",
				rei.name \"Запись создана кем\",
				ee.updated \"Запись обновлена когда\",
				reu.name \"Запись обновлена кем\"
			FROM ent_entities ee
            LEFT JOIN rp_emps rei on rei.id = ee.inserted_by 
            LEFT JOIN rp_emps reu on reu.id = ee.updated_by 
			WHERE ee.id= to_number(regexp_substr( '$ext_id', '([0123456789])+')) ";
			break;
	case "Объект":
//					--ro.ADR_ID    \"Ссылка на справочник адресов МО\",
//					--ro.S_REG_ID    \"Ссылка на регистратора, который подтвердил внесение информации\",
//					--ro.E_REG_ID    \"Cсылка на регистратора погасившего запись\",
//					ro.RE_INCLUDE_ID    \"Ссылка на объект, включающий данный (Земельный участок для здания, здание для помещения)\",
			$sql = "SELECT
					ro.ID,
					ro.R_TYPE    \"Тип объекта недвижимости\",
					ro.CAD_NUM    \"Кадастровый номер\",
					ro.PREV_CAD_NUM    \"Предыдущий кадастровый номер\",
					ro.NEXT_CAD_NUM    \"Последующий кадастровый номер\",
					ro.OBJ_NUM    \"Условный номер\",
					ro.OBJ3_NUM    \"Код налоговой при выгрузке\",
					CASE ro.OBJ5_NUM
						WHEN 10 THEN 'Запросить данные из ГКН'
						WHEN 40 THEN 'Получен ответ'
						ELSE ''''||ro.OBJ5_NUM||''''
					END AS \"Данные из ГКН\",
					ro.NAME    \"Полное наименование\",
					ro.ADR_DESC    \"Адрес объекта недвижимости\",
					ro.ADR_PRN_DESC    \"Адрес печати на свидетельстве\",
					ro.ADR_PRN_CHANGED    \"Несовпадение адреса по справ.\",
					ro.BTI_NO    \"Номер Бюро Тех.Инвентаризации\",
					ro.BTI_LITER    \"Литер Бюро Тех.Инвентаризации\",
					ro.STORES_NUM    \"Этажность здания\",
					ro.VAULT_STORES_NUM    \"Подземная этажность здания\",
					ro.STORE_NO    \"Этаж помещения\",
					ro.STAGE_PLAN_NO    \"№ помещения в поэтажном плане\",
					ro.TOTAL_SQ    \"Площадь общая\",
					ro.TSQ_UNITS    \"Един.измерения общей площади\",
					ro.LIVING_SQ    \"Площадь жилая\",
					ro.S_DATE    \"Дата начала периода\",
					ro.S_REMARK    \"Особые отметки регистратора\",
					ro.E_DATE    \"Дата погашения\",
					ro.E_REMARK    \"Особые отметки при ликвидации\",
					ro.CULTURAL    \"Объект культурного наследия\",
					ro.PIK_CONTENTS    \"Свед о прав/объек,вход.в ПИК\",
					ro.PIK_PURPOSE    \"Основной вид предприн.деят.ПИК\",
					ro.RE_COND_ID    \"Ссылка на кондоминиум\",
					ro.CON_DESC    \"Текст печати в свидетельстве\",
					CASE ro.STATUS
						WHEN 'В' THEN 'временная запись'
						WHEN 'Р' THEN 'внесена в Реестр'
						ELSE ro.STATUS
					END
						AS \"Статус записи\",
					ro.DEPT_ID,    
					ro.MOVED,    
					ro.inserted \"Запись создана когда\",
					rei.name \"Запись создана кем\",
					ro.updated \"Запись обновлена когда\",
					reu.name \"Запись обновлена кем\",
					ro.RE_OBJ_TYPE    \"Признак линейности объекта\",
					ro.PUBLIC_VAL    \"Признак общего иммущ.в доме\",
					ro.INFINITY    \"Протяженность\",
					ro.V_TYPE    \"Вид объекта недвижимости\",
					CASE ro.ROW_MODE
						WHEN 'Д' THEN 'Д-данные ЕБД'
						WHEN 'Н' THEN 'Н-данные БД локального подразделения'
						WHEN 'П' THEN 'П-данные полностью переданные в другой регион'
						ELSE ro.ROW_MODE
					END	AS \"состояние записи\"
						
				FROM re_objects ro
				LEFT JOIN rp_emps rei on rei.id = ro.inserted_by 
				LEFT JOIN rp_emps reu on reu.id = ro.updated_by 
				WHERE ro.id= to_number(regexp_substr( '$ext_id', '([0123456789])+'))";
			break;
	case "Право":
			$sql = "SELECT  
				rt.ID,
				rt.RE_ID,
				rt.REG_NO  \"Регистрационный №\",
				rt.TYPE_CODE \"Тип права и ограничения\",
				rt.SECTION \"Раздел права\",
				rt.R_GROUP \"Вид права\",
				rt.PART \"Доля\",
				rt.CON_DESC \"Вид ограничения\",
				rt.RS_PERIOD \"Срок действия ограничения\",
				rt.RS_S_DATE \"Дата возникновения ограничения\",
				rt.RS_E_DATE \"Дата прекращения ограничения\",
				rt.SERV_OWNER \"Лицо/объект:кому уст.сервитут\",
				rt.BRG_ID \"Ссылка на сделку\",
				rt.RE_PRICE \"Цена объекта в сделке\",
				rt.RT_CLAIM \"Правопритяз. из иного региона\",
				rt.RT_LEGAL_CLAIM \"Право требования(суд)\",
				rt.S_DATE \"Дата регистрации\",
				rer.NAME  \"Регистратор\",
				rt.S_REMARK \"Отметки регистратора\",
				rt.E_DATE \"Дата погашения\",
				ree.NAME \"Рег-р погасивший запись\",
				rt.E_REMARK \"Особые отметки регистратора\",
				CASE rt.STATUS
					WHEN 'В' THEN 'временная запись'
					WHEN 'Р' THEN 'внесена в Реестр'
					ELSE STATUS
				END \"Статус записи\",
				rt.DEPT_ID,
				rt.MOVED,
					rt.inserted \"Запись создана когда\",
					rei.name \"Запись создана кем\",
					rt.updated \"Запись обновлена когда\",
					reu.name \"Запись обновлена кем\",
				rt.CURRENCY \"Валюта\",
				rt.PRICE_DESC \"Условия опред.суммы сделки\",
				rt.RS_DESC \"Описание предмета ограничения\",
				rt.PART_MUCH_HOUSE \"Доля вПраве общ.долевой собств\",
				rt.CONDITION \"Условие сделки\",
				CASE rt.ROW_MODE
                        WHEN 'Д' THEN 'Д-данные ЕБД'
                        WHEN 'Н' THEN 'Н-данные БД локального подразделения'
                        WHEN 'П' THEN 'П-данные полностью переданные в другой регион'
                        ELSE rt.ROW_MODE
                    END    AS \"состояние записи\"

				FROM RT_RIGHTS RT
				LEFT JOIN rp_emps rei on rei.id = rt.inserted_by 
				LEFT JOIN rp_emps reu on reu.id = rt.updated_by 
				LEFT JOIN rp_emps rer on rer.id = rt.S_REG_ID
				LEFT JOIN rp_emps ree on ree.id = rt.E_REG_ID
				WHERE rt.id= to_number(regexp_substr( '$ext_id', '([0123456789])+'))";
			break;
	case "Обременение":
			break;
	default:
			$sql = "";
}


	if ($sql) {
//			echo "<p>$sql</p>";
		$stid = oci_parse($connSSD, $sql);
		oci_execute($stid);
		$rows = oci_fetch_all($stid, $res);

		if($rows>0){
		// Форматирование результатов
			echo "<table style='border: 1px solid black; width:100%;'>\n";
			foreach ($res as $k=>$v) {
				echo "<tr style='border: 1px solid #a5a5a5;'>\n";
					echo "    <td style='padding:2px;border-right: 1px solid #a5a5a5;'>$k</td><td>".$v[0] ."</td>\n";
				echo "</tr>\n";
			}
			echo "</table>\n";
		}else{
			echo "<h2>Данных нет!</h2>";
		}
	}
}
//oci_close($connSSD);
?>
