chcp 1251 >nul
rem	ИСПОЛЬЗОВАНИЕ: requestgen.exe  /mode [pdf|key|both] | /path [Полный путь к файлу|Путь к папке c файлами]
rem	клюк mode определяет генерировать только pdf или ключ(запрос на регистрацию) или оба варианта
rem	ключ path определяет путь к файл в формате xls или xlsx или xml(указывается полный, а не относительный путь)

rem Папка 
start RequestGen.exe /mode pdf /path C:\Temp\ReqestGenKad\test

rem Excel файл
start RequestGen.exe /mode key /path C:\Temp\ReqestGenKad\shablon_gen_key.xlsx
start RequestGen.exe /mode key /path C:\Temp\ReqestGenKad\shablon_gen_key.xls

rem XML файлы
start RequestGen.exe /mode both /path C:\Temp\20161025.xml
start RequestGen.exe /mode pdf /path C:\Temp\ReqestGenKad\ИвановИИ20161025.xml
