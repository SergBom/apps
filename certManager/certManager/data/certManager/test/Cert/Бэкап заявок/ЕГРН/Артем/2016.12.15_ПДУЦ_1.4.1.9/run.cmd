chcp 1251 >nul
rem	�������������: requestgen.exe  /mode [pdf|key|both] | /path [������ ���� � �����|���� � ����� c �������]
rem	���� mode ���������� ������������ ������ pdf ��� ����(������ �� �����������) ��� ��� ��������
rem	���� path ���������� ���� � ���� � ������� xls ��� xlsx ��� xml(����������� ������, � �� ������������� ����)

rem ����� 
start RequestGen.exe /mode pdf /path C:\Temp\ReqestGenKad\test

rem Excel ����
start RequestGen.exe /mode key /path C:\Temp\ReqestGenKad\shablon_gen_key.xlsx
start RequestGen.exe /mode key /path C:\Temp\ReqestGenKad\shablon_gen_key.xls

rem XML �����
start RequestGen.exe /mode both /path C:\Temp\20161025.xml
start RequestGen.exe /mode pdf /path C:\Temp\ReqestGenKad\��������20161025.xml
