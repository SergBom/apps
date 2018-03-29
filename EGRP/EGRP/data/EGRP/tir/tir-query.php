<?php
/*
"sessionLISTfns" => 'SELECT SESSION_ROOT, DATE_BEGIN, DATE_END, DATE_FIRST, DOC_CNT, FNS_CNT,
					CASE WHEN FNS_CNT >0 THEN \'1\'	ELSE \'0\' END as F,
					CASE WHEN SESSION_ROOT in ( :List ) THEN \'0\' ELSE \'1\' END as S
					from V$FNS#SESSION_ROOT
					WHERE FNS_CNT>0
						AND SESSION_ROOT in ( :List )
						AND 0<1
					ORDER BY DATE_LAST DESC',

	"sessionLISTu" => 'SELECT * FROM V$FNS#SESSION_ROOT
					:AddWhere
					ORDER BY DATE_FIRST desc',

					*/

$sqlTIR = array(
	// Список Сессий на сервере ТИР, которых нет в локальной базе
	"sessionLISTu" => 'SELECT T1.SESSION_ROOT, to_char(T1.DATE_BEGIN,\'YYYY.MM.DD\') as DATE_BEGIN, to_char(T1.DATE_END,\'YYYY.MM.DD\') as DATE_END, to_char(T1.DATE_FIRST,\'YYYY.MM.DD\') as DATE_FIRST, T1.DOC_CNT, T1.FNS_CNT
                    from V$FNS#SESSION_ROOT@TIR51 T1
                    WHERE  T1.SESSION_ROOT not in ( SELECT T3.SESSION_ROOT FROM T$SESSION T3)
                    ORDER BY T1.DATE_LAST DESC',
					
	// Список Сессий на сервере ТИР, которых нет в локальной базе и содержат данные ФНС				
	"sessionLISTfns" => 'SELECT T1.SESSION_ROOT, to_char(T1.DATE_BEGIN,\'YYYY.MM.DD\') as DATE_BEGIN, to_char(T1.DATE_END,\'YYYY.MM.DD\') as DATE_END,	to_char(T1.DATE_FIRST,\'YYYY.MM.DD\') as DATE_FIRST, T1.DOC_CNT, T1.FNS_CNT,
                    CASE WHEN T1.FNS_CNT >0 THEN \'1\'   ELSE \'0\' END as F,
                    CASE WHEN T1.SESSION_ROOT in ( SELECT T2.SESSION_ROOT FROM T$SESSION T2 WHERE T2.FNS_ERR=0) THEN \'0\' ELSE \'1\' END as S
                    from V$FNS#SESSION_ROOT@TIR51 T1
                    WHERE T1.FNS_CNT>0
                        AND T1.SESSION_ROOT in ( SELECT T3.SESSION_ROOT FROM T$SESSION T3 WHERE T3.FNS_ERR=0)
                    ORDER BY T1.DATE_LAST DESC',
					
	
	"sessionLIST" => 'SELECT Session_Root,
					MIN (Session_Type) AS Session_Type,
					Date_Begin,
					Date_End,
					SUM (1) AS Session_Cnt,
					MIN (Date_Create) AS Date_First,
					MAX (Date_Create) AS Date_Last,
					MAX (Doc_Cnt) AS Doc_Cnt,
					SUM (
						CASE WHEN (Fns_Status = 0) THEN (Flk_Cnt - Fns_Cnt) ELSE 0 END)
						AS Fns_Cnt,
					SUM(Flk_Err) AS  Flk_Err,
					SUM(Fns_Err) AS  Fns_Err
					FROM (    SELECT CONNECT_BY_ROOT (S.Session#) Session_Root,
								S.Session_Type,
								S.Date_Create,
								S.Date_Begin,
								S.Date_End,
								(SELECT COUNT (*)
									FROM T$Fns#Document
									WHERE Session# = S.Session#)
								AS Doc_Cnt,
								(SELECT COUNT (*)
									FROM T$Fns#Document
									WHERE     Session# = S.Session#
										AND Doc_Status = 1
										AND S.Session_Status = 6)
								AS Flk_Cnt,
								(SELECT COUNT (*)
									FROM T$Fns#Document_Flk_Log
									WHERE     Session# = S.Session#
									AND Doc_Guid IS NOT NULL)
								AS Flk_Err,
								(SELECT COUNT (DISTINCT Doc_Guid)
									FROM T$Fns#Document_Res_Log
									WHERE     Session# = S.Session#
										AND Doc_Guid IS NOT NULL
										AND S.Session_Status = 6)
								AS Fns_Cnt,
								(SELECT COUNT (*)
									FROM T$Fns#Document_Res_Log
									WHERE     Session# = S.Session#
										AND Doc_Guid IS NOT NULL
										AND S.Session_Status = 6)
								AS Fns_Err,
								(SELECT COUNT (*)
									FROM T$Fns#Session_File
								WHERE     Session# = S.Session#
											AND File_Status <> 2
										AND S.Session_Status = 6)
									AS Fns_Status
							FROM T$Fns#Session S
							:AddWhere
						START WITH Parent# IS NULL
						CONNECT BY Parent# = PRIOR Session#)
			GROUP BY Session_Root, Date_Begin, Date_End
			ORDER BY DATE_LAST DESC',
			
	"sessionNUM" => 'SELECT Session_Root,
					MIN (Session_Type) AS Session_Type,
					Date_Begin,
					Date_End,
					SUM (1) AS Session_Cnt,
					MIN (Date_Create) AS Date_First,
					MAX (Date_Create) AS Date_Last,
					MAX (Doc_Cnt) AS Doc_Cnt,
					SUM (
						CASE WHEN (Fns_Status = 0) THEN (Flk_Cnt - Fns_Cnt) ELSE 0 END)
						AS Fns_Cnt,
					SUM(Flk_Err) AS  Flk_Err,
					SUM(Fns_Err) AS  Fns_Err
					FROM (    SELECT CONNECT_BY_ROOT (S.Session#) Session_Root,
								S.Session_Type,
								S.Date_Create,
								S.Date_Begin,
								S.Date_End,
								(SELECT COUNT (*)
									FROM T$Fns#Document
									WHERE Session# = S.Session#)
								AS Doc_Cnt,
								(SELECT COUNT (*)
									FROM T$Fns#Document
									WHERE     Session# = S.Session#
										AND Doc_Status = 1
										AND S.Session_Status = 6)
								AS Flk_Cnt,
								(SELECT COUNT (*)
									FROM T$Fns#Document_Flk_Log
									WHERE     Session# = S.Session#
									AND Doc_Guid IS NOT NULL)
								AS Flk_Err,
								(SELECT COUNT (DISTINCT Doc_Guid)
									FROM T$Fns#Document_Res_Log
									WHERE     Session# = S.Session#
										AND Doc_Guid IS NOT NULL
										AND S.Session_Status = 6)
								AS Fns_Cnt,
								(SELECT COUNT (*)
									FROM T$Fns#Document_Res_Log
									WHERE     Session# = S.Session#
										AND Doc_Guid IS NOT NULL
										AND S.Session_Status = 6)
								AS Fns_Err,
								(SELECT COUNT (*)
									FROM T$Fns#Session_File
								WHERE     Session# = S.Session#
											AND File_Status <> 2
										AND S.Session_Status = 6)
									AS Fns_Status
							FROM T$Fns#Session S
						START WITH Parent# IS NULL
						CONNECT BY Parent# = PRIOR Session#)
				WHERE Session_Root = :Session_Root
			GROUP BY Session_Root, Date_Begin, Date_End
			ORDER BY DATE_LAST DESC',
	"listFLK" => 'SELECT ROWNUM, S1.*
			FROM (
				SELECT 
					CASE SYSTEM_TYPE WHEN \'R\' THEN \'ЕГРП\'
						WHEN \'C\' THEN \'ГКН\'
						WHEN \'U\' THEN \'----\'
						ELSE SYSTEM_TYPE
					END as SYSTEM_TYPE,
					CASE ESSENCE_TYPE WHEN \'O\' THEN \'Объект\'
						WHEN \'R\' THEN \'Право\'
						WHEN \'E\' THEN \'Обременение\'
						WHEN \'S\' THEN \'Субъект\'
						WHEN \'U\' THEN \'----\'
						ELSE ESSENCE_TYPE
					END as ESSENCE_TYPE,
					CASE WHEN length(CADASTRALNUMBER) >0 THEN \'КН: \'|| CADASTRALNUMBER ELSE \'\' END ||
					CASE WHEN length(CONDITIONALNUMBER) >0 THEN \' УКН: \'||CONDITIONALNUMBER ELSE \'\' END as CAD_NUM,
					DESCRIPTION,PATH, ATTRIBUTE_NAME, ATTRIBUTE_VALUE,
					T1.DOC_GUID, T1.REC_GUID, T1.EXTERNAL_ID
				FROM T$FNS#DOCUMENT_FLK_LOG T1
					inner join V$FNS#CADASTRAL T2 on T1.DOC_GUID = T2.DOC_GUID
				WHERE 
					T1.SESSION# = :SESSION
					AND
					(SYSTEM_TYPE = \'R\' OR SYSTEM_TYPE = \'U\')
				ORDER BY CADASTRALNUMBER asc, CONDITIONALNUMBER asc
			) S1',

	"listFNS" => 'SELECT ROWNUM, S1.*
			FROM (
				SELECT 
					\'ФНС\' as SYSTEM_TYPE,
					CASE WHEN length(CADASTRALNUMBER) > 0 THEN \'КН: \'|| CADASTRALNUMBER ELSE \'\' END ||
					CASE WHEN length(CONDITIONALNUMBER) >0 THEN \'УКН: \'||CONDITIONALNUMBER ELSE \'\' END as CAD_NUM,
					ERROR_TEXT,ERROR_PATH,ATTRIBUTE_VALUE
				FROM T$FNS#DOCUMENT_RES_LOG T1
					inner join V$FNS#CADASTRAL T2 on T1.DOC_GUID = T2.DOC_GUID
				WHERE
					T1.SESSION# = :SESSION
				ORDER BY CADASTRALNUMBER asc, CONDITIONALNUMBER asc
			) S1',
			
	"stat1" => "select  rownum,S1.* FROM (
					select description,'-' as Tire, count(*) as Nums from T\$FNS#DOCUMENT_FLK_LOG where SESSION# = :SESSION
					group by description
					order by Nums desc
					) S1",
					

//					,S1.CNT_PRCL "Зем. участки"
//					,S1.CNT_CRDS "Здания"
//					,S1.CNT_FLAT "Помещения"
//					,S1.TOTAL "Всего"
	"stat2" => "select  rownum,
					S1.description
					,S1.PATH
					,S1.CNT_PRCL
					,S1.CNT_CRDS
					,S1.CNT_FLAT
					,S1.TOTAL
				FROM (
					SELECT DISTINCT C.SESSION#,
                    DESCRIPTION,
                    PATH,
                    NVL (SUM (DECODE (DOC_TYPE, 'P', 1)), 0) AS CNT_PRCL,
                    NVL (SUM (DECODE (DOC_TYPE, 'C', 1)), 0) AS CNT_CRDS,
                    NVL (SUM (DECODE (DOC_TYPE, 'F', 1)), 0) AS CNT_FLAT,
                    COUNT (REC_GUID) AS TOTAL
				FROM v\$fns#cadastral A, T\$FNS#DOCUMENT_FLK_LOG B, T\$FNS#SESSION C
				WHERE     A.DOC_GUID = B.DOC_GUID
					AND DOC_STATUS = '-1'
					AND A.SESSION# = C.SESSION#(+)
					AND EVent = 'E'
					AND C.SESSION# = :SESSION
				GROUP BY C.SESSION#, DESCRIPTION, PATH
				ORDER BY DESCRIPTION, PATH
			) S1"

);


$sqlLOC = array(
	"sessionLIST" => "SELECT
				SESSION_ROOT,
				SESSION_TYPE,
				to_char(DATE_BEGIN,'YYYY.MM.DD') AS DATE_BEGIN,
				to_char(DATE_END,'YYYY.MM.DD') AS DATE_END,
				SESSION_CNT,
				to_char(DATE_FIRST,'YYYY.MM.DD') AS DATE_FIRST,
				to_char(DATE_LAST,'YYYY.MM.DD') AS DATE_LAST,
				DOC_CNT,
				FNS_CNT,
				FLK_ERR,
				FNS_ERR,
				VYGRUZKA#,
				STATUS
			FROM T\$SESSION
			ORDER BY VYGRUZKA# desc",

	"sessionLIST1" => 'SELECT Session_Root,
					MIN (Session_Type) AS Session_Type,
					Date_Begin,
					Date_End,
					SUM (1) AS Session_Cnt,
					MIN (Date_Create) AS Date_First,
					MAX (Date_Create) AS Date_Last,
					MAX (Doc_Cnt) AS Doc_Cnt,
					SUM (
						CASE WHEN (Fns_Status = 0) THEN (Flk_Cnt - Fns_Cnt) ELSE 0 END)
						AS Fns_Cnt,
					SUM(Flk_Err) AS  Flk_Err,
					SUM(Fns_Err) AS  Fns_Err
					FROM (    SELECT CONNECT_BY_ROOT (S.Session#) Session_Root,
								S.Session_Type,
								S.Date_Create,
								S.Date_Begin,
								S.Date_End,
								(SELECT COUNT (*)
									FROM T$Fns#Document
									WHERE Session# = S.Session#)
								AS Doc_Cnt,
								(SELECT COUNT (*)
									FROM T$Fns#Document
									WHERE     Session# = S.Session#
										AND Doc_Status = 1
										AND S.Session_Status = 6)
								AS Flk_Cnt,
								(SELECT COUNT (*)
									FROM T$Fns#Document_Flk_Log
									WHERE     Session# = S.Session#
									AND Doc_Guid IS NOT NULL)
								AS Flk_Err,
								(SELECT COUNT (DISTINCT Doc_Guid)
									FROM T$Fns#Document_Res_Log
									WHERE     Session# = S.Session#
										AND Doc_Guid IS NOT NULL
										AND S.Session_Status = 6)
								AS Fns_Cnt,
								(SELECT COUNT (*)
									FROM T$Fns#Document_Res_Log
									WHERE     Session# = S.Session#
										AND Doc_Guid IS NOT NULL
										AND S.Session_Status = 6)
								AS Fns_Err,
								(SELECT COUNT (*)
									FROM T$Fns#Session_File
								WHERE     Session# = S.Session#
											AND File_Status <> 2
										AND S.Session_Status = 6)
									AS Fns_Status
							FROM T$Fns#Session S
						START WITH Parent# IS NULL
						CONNECT BY Parent# = PRIOR Session#)
			GROUP BY Session_Root, Date_Begin, Date_End
			ORDER BY DATE_LAST DESC',

	"listFLK" => 'SELECT ROWNUM, S1.*
			FROM (
				SELECT 
					CASE SYSTEM_TYPE WHEN \'R\' THEN \'ЕГРП\'
					WHEN \'C\' THEN \'ГКН\'
					WHEN \'U\' THEN \'Неопр.\'
					ELSE SYSTEM_TYPE
					END as SYSTEM_TYPE,
					CASE ESSENCE_TYPE WHEN \'O\' THEN \'Объект\'
					WHEN \'R\' THEN \'Право\'
					WHEN \'E\' THEN \'Обременение\'
					WHEN \'S\' THEN \'Субъект\'
					WHEN \'U\' THEN \'Неопределено\'
					ELSE ESSENCE_TYPE
					END as ESSENCE_TYPE,
					CASE WHEN length(CADASTRALNUMBER) > 0 THEN \' КН: \'|| CADASTRALNUMBER ELSE \'\' END ||
					CASE WHEN length(CONDITIONALNUMBER) >0 THEN \' УКН: \'||CONDITIONALNUMBER ELSE \'\' END as CONCADNUM,
					T1.TAG_NAME,T1.REC_GUID,T1.DOC_GUID,T1.EXTERNAL_ID,T1.DESCRIPTION,T1.PATH,ATTRIBUTE_NAME,ATTRIBUTE_VALUE,
					USER_ID,USER_DATE,USER_COMMENT,STATUS_ERROR 
				FROM T$FNS#DOCUMENT_FLK_LOG T1
					LEFT join T$FNS#CADASTRAL T2 on T1.DOC_GUID = T2.DOC_GUID
				WHERE 
					T1.SESSION# = :SESSION
					AND
					(SYSTEM_TYPE = \'R\' OR SYSTEM_TYPE = \'U\')
				ORDER BY CADASTRALNUMBER asc, CONDITIONALNUMBER asc
			) S1',
			
	"listFLK1" => 'SELECT * FROM V$FLK WHERE SESSION# = :SESSION',
     
	"listFLK2" => '	SELECT ROWNUM, S1.*
				FROM (
				SELECT 
                    T2.CAD_RAION,
                    T3.NAME,
					CASE SYSTEM_TYPE WHEN \'R\' THEN \'ЕГРП\'
					WHEN \'C\' THEN \'ГКН\'
					WHEN \'U\' THEN \'Неопр.\'
					ELSE SYSTEM_TYPE
					END as SYSTEM_TYPE,
					CASE ESSENCE_TYPE WHEN \'O\' THEN \'Объект\'
					WHEN \'R\' THEN \'Право\'
					WHEN \'E\' THEN \'Обременение\'
					WHEN \'S\' THEN \'Субъект\'
					WHEN \'U\' THEN \'Неопределено\'
					ELSE ESSENCE_TYPE
					END as ESSENCE_TYPE,
					CASE WHEN length(CADASTRALNUMBER)  >0 THEN \' КН: \'|| CADASTRALNUMBER ELSE \'\' END ||
					CASE WHEN length(CONDITIONALNUMBER) >0 THEN \' УКН: \'||CONDITIONALNUMBER ELSE \'\' END as CONCADNUM,
					T1.TAG_NAME,T1.REC_GUID,T1.DOC_GUID,T1.EXTERNAL_ID,T1.DESCRIPTION,T1.PATH,ATTRIBUTE_NAME,ATTRIBUTE_VALUE,
					USER_ID,USER_DATE,USER_COMMENT,STATUS_ERROR 
				FROM T$FNS#DOCUMENT_FLK_LOG T1
					LEFT join T$FNS#CADASTRAL T2 on T1.DOC_GUID = T2.DOC_GUID
                    left join T$DISTRICT_DATA T3 on T3.CADNUM = T2.CAD_RAION
				WHERE 
					T1.SESSION# = :SESSION
					AND
					(SYSTEM_TYPE = \':SYSTYPE\' OR SYSTEM_TYPE = \'U\')
				ORDER BY CADASTRALNUMBER asc, CONDITIONALNUMBER asc
			) S1',

	"listFNS" => '',

	"stat1" => "select  rownum,S1.* FROM (
					select description,'-' as Tire, count(*) as Nums from T\$FNS#DOCUMENT_FLK_LOG where SESSION# = :SESSION
					group by description
					order by Nums desc
					) S1",

	"stat1N" => "select  rownum,S1.* FROM (
					select description,'-' as Tire, count(*) as Nums from T\$FNS#DOCUMENT_FLK_LOG where SESSION# = :SESSION and SYSTEM_TYPE=':ST'
					group by description
					order by Nums desc
					) S1",
          
  "stat11a" => "select   
(case when REGEXP_LIKE(RD.SHORT_NAME , '(Сектор Полярные Зори (44|22))|Центральный аппарат') then'Отдел регистрации прав'
             when REGEXP_LIKE(RD.SHORT_NAME , 'Полярный') then'Североморский межрайонный (г. Полярный)'
             when REGEXP_LIKE(RD.SHORT_NAME , 'Апатитский') then'Кировский межрайонный (г. Апатиты)'
             when REGEXP_LIKE(RD.SHORT_NAME , 'Кировский') then'Кировский межрайонный (г. Кировск)'
             when REGEXP_LIKE(RD.SHORT_NAME , 'Умба|Полярнозоринский') then'Кандалакшский отдел'
             when REGEXP_LIKE(RD.SHORT_NAME , 'Никельский') then'Печенгский отдел'
             when REGEXP_LIKE(RD.SHORT_NAME , 'Североморский') then'Североморский межрайонный (г. Североморск)'
       else RD.SHORT_NAME end )  OTDEL, FLK.DESCRIPTION ERROR ,STAT.ERROR_SOCR OTMETKA, count(FLK.REC_GUID) CCOUNT
from T\$SESSION ses, 
     T\$FNS#DOCUMENT_FLK_LOG flk,
     T\$FNS#CADASTRAL obj,
     T\$STATUS_ERROR stat,
     re_objects@ssd ros,
     rp_depts rd
where SES.SESSION_ROOT=FLK.SESSION#
and   FLK.STATUS_ERROR=STAT.ID
and  FLK.DOC_GUID=OBJ.DOC_GUID
and  OBJ.EXTERNAL_ID=ros.id(+)
and ros.dept_id=rd.id(+)
and FLK.SYSTEM_TYPE=':ST'
and FLK.SESSION# = :SESSION
group by
(case when REGEXP_LIKE(RD.SHORT_NAME , '(Сектор Полярные Зори (44|22))|Центральный аппарат') then'Отдел регистрации прав'
             when REGEXP_LIKE(RD.SHORT_NAME , 'Полярный') then'Североморский межрайонный (г. Полярный)'
             when REGEXP_LIKE(RD.SHORT_NAME , 'Апатитский') then'Кировский межрайонный (г. Апатиты)'
             when REGEXP_LIKE(RD.SHORT_NAME , 'Кировский') then'Кировский межрайонный (г. Кировск)'
             when REGEXP_LIKE(RD.SHORT_NAME , 'Умба|Полярнозоринский') then'Кандалакшский отдел'
             when REGEXP_LIKE(RD.SHORT_NAME , 'Никельский') then'Печенгский отдел'
             when REGEXP_LIKE(RD.SHORT_NAME , 'Североморский') then'Североморский межрайонный (г. Североморск)'
       else RD.SHORT_NAME end ) , 
FLK.DESCRIPTION,  STAT.ERROR_SOCR
order by 1, FLK.DESCRIPTION, STAT.ERROR_SOCR
          ",
	"stat2"	=> 'SELECT  rownum, S1.*, D.NAME as CAD_NAME
					FROM (	SELECT DISTINCT C.SESSION_ROOT,
							A.CAD_RAION,
							DESCRIPTION,
							PATH,
							NVL (SUM (DECODE (DOC_TYPE, \'P\', 1)), 0) AS CNT_PRCL,
							NVL (SUM (DECODE (DOC_TYPE, \'C\', 1)), 0) AS CNT_CRDS,
							NVL (SUM (DECODE (DOC_TYPE, \'F\', 1)), 0) AS CNT_FLAT,
							COUNT (REC_GUID) AS TOTAL
						FROM v$fns#cadastral A, T$FNS#DOCUMENT_FLK_LOG B, T$SESSION C
					WHERE     A.DOC_GUID = B.DOC_GUID
						AND DOC_STATUS = \'-1\'
						AND A.SESSION# = C.SESSION_ROOT(+)
					--  AND SESSION_STATUS = 6
						AND EVent = \'E\'
						AND C.SESSION_ROOT = :SESSION
					GROUP BY C.SESSION_ROOT, A.CAD_RAION, DESCRIPTION, PATH
					ORDER BY TOTAL desc
					) S1
					LEFT JOIN T$DISTRICT_DATA D
						ON S1.CAD_RAION = D.CADNUM',
						
	"stat3"	=> 'select rownum, S1.* 
				FROM (
					SELECT DISTINCT
						DESCRIPTION,
						NVL (SUM (DECODE (STATUS_ERROR, NULL, 1)), 0) AS ERN, -- остались Ошибками
						NVL (SUM (DECODE (STATUS_ERROR, 0, 1)), 0) AS ER0, -- остались Ошибками
						NVL (SUM (DECODE (STATUS_ERROR, 1, 1)), 0) AS ER1, -- Исправили
						NVL (SUM (DECODE (STATUS_ERROR, 2, 1)), 0) AS ER2, -- Не возм. исправить
						NVL (SUM (DECODE (STATUS_ERROR, 3, 1)), 0) AS ER3, -- Не обнаружена ошибка
						COUNT (REC_GUID) AS TOTAL
					FROM v$fns#cadastral A, T$FNS#DOCUMENT_FLK_LOG B, T$SESSION C
						WHERE A.DOC_GUID = B.DOC_GUID
						AND DOC_STATUS = \'-1\'
						AND A.SESSION# = C.SESSION_ROOT
						AND EVent = \'E\'
						AND C.SESSION_ROOT = :SESSION
					GROUP BY C.SESSION_ROOT, DESCRIPTION
					ORDER BY TOTAL desc
				) S1',
	
	"stat31" => 'select rownum, D.NAME as CAD_NAME, S1.* 
					FROM (
						SELECT DISTINCT --C.SESSION_ROOT,
							A.CAD_RAION,
							-- DESCRIPTION,
							NVL (SUM (DECODE (STATUS_ERROR, NULL, 1)), 0) AS ERN, -- остались Ошибками
							NVL (SUM (DECODE (STATUS_ERROR, 0, 1)), 0) AS ER0, -- остались Ошибками
							NVL (SUM (DECODE (STATUS_ERROR, 1, 1)), 0) AS ER1, -- Исправили
							NVL (SUM (DECODE (STATUS_ERROR, 2, 1)), 0) AS ER2, -- Не возм. исправить
							NVL (SUM (DECODE (STATUS_ERROR, 3, 1)), 0) AS ER3, -- Не обнаружена ошибка
							COUNT (REC_GUID) AS TOTAL
						FROM v$fns#cadastral A, T$FNS#DOCUMENT_FLK_LOG B, T$SESSION C
						WHERE A.DOC_GUID = B.DOC_GUID
							AND DOC_STATUS = \'-1\'
							AND A.SESSION# = C.SESSION_ROOT
							AND EVent = \'E\'
							AND C.SESSION_ROOT = :SESSION
						GROUP BY C.SESSION_ROOT, A.CAD_RAION --, DESCRIPTION
						ORDER BY CAD_RAION, TOTAL desc
					) S1
						LEFT JOIN T$DISTRICT_DATA D
							ON S1.CAD_RAION = D.CADNUM',

	"stat31a" => 'select rownum, D.SHORT_NAME as CAD_NAME, S1.* 
                    FROM (
                        SELECT DISTINCT
                            A.DEPT_ID,
                            NVL (SUM (DECODE (STATUS_ERROR, NULL, 1,0,1,0)), 0) AS ERN, -- остались Ошибками
--                            NVL (SUM (DECODE (STATUS_ERROR, 0, 1)), 0) AS ER0, -- остались Ошибками
                            NVL (SUM (DECODE (STATUS_ERROR, 1, 1)), 0) AS ER1, -- Исправили
                            NVL (SUM (DECODE (STATUS_ERROR, 2, 1)), 0) AS ER2, -- Не возм. исправить
                            NVL (SUM (DECODE (STATUS_ERROR, 3, 1)), 0) AS ER3, -- Не обнаружена ошибка
                            COUNT (REC_GUID) AS TOTAL
                        FROM v$fns#cadastral A, T$FNS#DOCUMENT_FLK_LOG B, T$SESSION C
                        WHERE A.DOC_GUID = B.DOC_GUID
                            AND DOC_STATUS = \'-1\'
                            AND A.SESSION# = C.SESSION_ROOT
                            AND EVent = \'E\'
                            AND C.SESSION_ROOT = :SESSION
                        GROUP BY C.SESSION_ROOT, A.DEPT_ID
                        ORDER BY DEPT_ID, TOTAL desc
                    ) S1
                        LEFT JOIN  rp_depts D
                            ON S1.DEPT_ID = D.ID',

	"stat31b" => 'select rownum, O2.NAME, S1.* 
                    FROM (
                        SELECT DISTINCT
                            O.id,
                            NVL (SUM (DECODE (STATUS_ERROR, NULL, 1,0,1,0)), 0) AS ERN, -- остались Ошибками
                            NVL (SUM (DECODE (STATUS_ERROR, 1, 1)), 0) AS ER1, -- Исправили
                            NVL (SUM (DECODE (STATUS_ERROR, 2, 1)), 0) AS ER2, -- Не возм. исправить
                            NVL (SUM (DECODE (STATUS_ERROR, 3, 1)), 0) AS ER3, -- Не обнаружена ошибка
                            COUNT (REC_GUID) AS TOTAL
                        FROM v$fns#cadastral A, T$FNS#DOCUMENT_FLK_LOG B, T$SESSION C, RP_DEPTS R, T$OTDELS_TMP O
                        WHERE A.DOC_GUID = B.DOC_GUID
                            AND DOC_STATUS = \'-1\' AND EVent = \'E\'
                            AND A.SESSION# = C.SESSION_ROOT
                            AND C.SESSION_ROOT = :SESSION
                            AND A.DEPT_ID = R.ID (+)
                            and R.DEPT_ID=O.ID (+)
                        GROUP BY C.SESSION_ROOT, O.ID
                        ORDER BY  TOTAL desc
                    ) S1
                        left join T$OTDELS_TMP O2
                            on S1.ID = O2.ID',
							
	"stat33" => 'select rownum, S2.*
					FROM (
					SELECT D.NAME, S1.* , ROUND(s1.NER1*100/NTOTAL,2) as PISPR, ROUND((NER1+NER2+NER3)*100/NTOTAL,2) as POBR
                    FROM (
                        SELECT DISTINCT B.SESSION#,
                            E.N1,
                            NVL (SUM (DECODE (STATUS_ERROR, NULL, 1, 0, 1,0)), 0) AS NERN, -- не обработанные Ошибки
                            0 AS NER0, -- остались Ошибками
                            NVL (SUM (DECODE (STATUS_ERROR, 1, 1)), 0) AS NER1, -- Исправили
                            NVL (SUM (DECODE (STATUS_ERROR, 2, 1)), 0) AS NER2, -- Не возм. исправить
                            NVL (SUM (DECODE (STATUS_ERROR, 3, 1)), 0) AS NER3, -- Не обнаружена ошибка
                            COUNT (REC_GUID) AS NTOTAL
                        FROM v$fns#cadastral A, T$FNS#DOCUMENT_FLK_LOG B, T$SESSION C, T$DISTRICT_DATA E 
                        WHERE A.DOC_GUID = B.DOC_GUID
                            AND E.CADNUM = A.CAD_RAION
                            AND DOC_STATUS = \'-1\'
                            AND A.SESSION# = C.SESSION_ROOT
                            AND EVent = \'E\'
                            AND C.SESSION_ROOT = :SESSION
                        GROUP BY B.SESSION#, E.N1
                    ) S1
                        LEFT JOIN T$OTDELS_TMP D
                            ON S1.N1 = D.ID --AND S1.SESSION# = D.SESSION#
						ORDER By D.ID
					) S2',
	"stat33a" => 'select rownum, O2.NAME, S1.*, ROUND(s1.ER1*100/TOTAL,2) as PISPR, ROUND((ER1+ER2+ER3)*100/TOTAL,2) as POBR 
                    FROM (
                        SELECT DISTINCT
                            O.id,
                            NVL (SUM (DECODE (RF, 1, 1)), 0) AS ERF, -- Исправили
							NVL (SUM (DECODE (STATUS_ERROR, NULL, 1,0,1,0)), 0) AS ERN, -- остались Ошибками
                            NVL (SUM (DECODE (STATUS_ERROR, 1, 1)), 0) AS ER1, -- Исправили
                            NVL (SUM (DECODE (STATUS_ERROR, 2, 1)), 0) AS ER2, -- Не возм. исправить
                            NVL (SUM (DECODE (STATUS_ERROR, 3, 1)), 0) AS ER3, -- Не обнаружена ошибка
                            COUNT (REC_GUID) AS TOTAL
                        FROM v$fns#cadastral A, T$FNS#DOCUMENT_FLK_LOG B, T$SESSION C, RP_DEPTS R, T$OTDELS_TMP O
                        WHERE A.DOC_GUID = B.DOC_GUID
                            AND DOC_STATUS = \'-1\' AND EVent = \'E\'
                            AND A.SESSION# = C.SESSION_ROOT
                            AND C.SESSION_ROOT = :SESSION
                            AND A.DEPT_ID = R.ID (+)
                            and R.DEPT_ID=O.ID (+)
                            and B.SYSTEM_TYPE = \':ST\'
                        GROUP BY C.SESSION_ROOT, O.ID
                        ORDER BY  TOTAL desc
                    ) S1
                        left join T$OTDELS_TMP O2
                            on S1.ID = O2.ID',

	"stat_common" => 'SELECT A.SESSION_ROOT,
						to_char(DATE_BEGIN,\'YYYY.MM.DD\') AS DATE_BEGIN,
						to_char(DATE_END,\'YYYY.MM.DD\') AS DATE_END,
						CNT_ERTH,	
						CNT_HOME,
						CNT_APTM,
						TOT AS TOTAL_DOC,
						CNT_BAD_ERTH,
						CNT_BAD_HOME,
						CNT_BAD_APTM,
						CNT_NICE_ERTH,
						CNT_NICE_HOME,
						CNT_NICE_APTM,
						CNT_NICE,
						CNT_BAD,
						TOTAL,
						CNT_ERR_ERTH,
						CNT_ERR_HOME,
						CNT_ERR_APTM,
						DOCUMENT_RES_LOG,
						RES_DOC
					FROM (  SELECT DISTINCT
						C.SESSION_ROOT,
						DATE_BEGIN,
						DATE_END,
						NVL (SUM (DECODE (A.DOC_TYPE, \'P\', 1)), 0) AS CNT_ERTH,
						NVL (SUM (DECODE (A.DOC_TYPE, \'C\', 1)), 0) AS CNT_HOME,
						NVL (SUM (DECODE (A.DOC_TYPE, \'F\', 1)), 0) AS CNT_APTM,
						COUNT (B.DOC_GUID) AS TOT,
						NVL ( SUM ( CASE  WHEN A.DOC_STATUS = -1 AND A.DOC_TYPE = \'P\' THEN 1 END), 0) AS CNT_BAD_ERTH,
						NVL ( SUM ( CASE WHEN A.DOC_STATUS = -1 AND A.DOC_TYPE = \'C\' THEN 1 END), 0) AS CNT_BAD_HOME,
						NVL ( SUM ( CASE WHEN A.DOC_STATUS = -1 AND A.DOC_TYPE = \'F\' THEN 1 END), 0) AS CNT_BAD_APTM,
						NVL ( SUM ( CASE WHEN A.DOC_STATUS = 1 AND A.DOC_TYPE = \'P\' THEN 1 END), 0) AS CNT_NICE_ERTH,
						NVL ( SUM ( CASE WHEN A.DOC_STATUS = 1 AND A.DOC_TYPE = \'C\' THEN 1 END), 0) AS CNT_NICE_HOME,
						NVL ( SUM ( CASE WHEN A.DOC_STATUS = 1 AND A.DOC_TYPE = \'F\' THEN 1 END), 0) AS CNT_NICE_APTM,
						NVL (SUM (CASE WHEN A.DOC_STATUS = 1 THEN 1 END), 0) AS CNT_NICE,
						NVL (SUM (CASE WHEN A.DOC_STATUS = -1 THEN 1 END), 0) AS CNT_BAD
					FROM v$fns#cadastral A, T$DOCUMENT B, T$SESSION C
					WHERE     A.DOC_GUID = B.DOC_GUID
						AND A.SESSION# = C.SESSION_ROOT(+)
					GROUP BY C.SESSION_ROOT,
						DATE_BEGIN,
						DATE_END
					ORDER BY C.SESSION_ROOT) A,
						(  SELECT SESSION_ROOT,
							NVL (SUM (TOT_ERROR), 0) AS TOTAL,
							NVL (SUM (ERR_PRCL), 0) AS CNT_ERR_ERTH,
							NVL (SUM (ERR_CRD), 0) AS CNT_ERR_HOME,
							NVL (SUM (ERR_FLAT), 0) AS CNT_ERR_APTM
						FROM (  SELECT DISTINCT
								C.SESSION_ROOT,
								DESCRIPTION,
								PATH,
								NVL (SUM (DECODE (DOC_TYPE, \'P\', 1)), 0) AS ERR_PRCL,
								NVL (SUM (DECODE (DOC_TYPE, \'C\', 1)), 0) AS ERR_CRD,
								NVL (SUM (DECODE (DOC_TYPE, \'F\', 1)), 0) AS ERR_FLAT,
								COUNT (REC_GUID) AS TOT_ERROR
							FROM T$fns#cadastral A,
								T$FNS#DOCUMENT_FLK_LOG B,
								T$SESSION C
							WHERE  A.DOC_GUID = B.DOC_GUID
								AND DOC_STATUS = \'-1\'
								AND A.SESSION# = C.SESSION_ROOT(+)
								AND EVent = \'E\'
							GROUP BY C.SESSION_ROOT, DESCRIPTION, PATH)
						GROUP BY SESSION_ROOT) B,
							(  SELECT SESSION#,
								COUNT (ERROR_PATH) AS DOCUMENT_RES_LOG,
								COUNT (DISTINCT DOC_GUID) AS RES_DOC
								FROM T$FNS#DOCUMENT_RES_LOG
								GROUP BY SESSION#) C
						WHERE A.SESSION_ROOT = B.SESSION_ROOT(+) AND A.SESSION_ROOT = C.SESSION#(+)
							AND A.SESSION_ROOT = :SESSION
					ORDER BY SESSION# desc'
				

);



?>