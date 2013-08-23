echo 启动服务器……

reg import php5\pws-php5cgi.reg
reg import php5\pws-php5isapi.reg

echo 添加服务到注册表

sc create mysql binPath= "D:\EditPlus\tools\php\MySQL5\bin\mysqld.exe" start= auto displayName= mysql
sc start mysql


:安装apache
:start /min D:\EditPlus\tools\php\Apache2\bin\httpd.exe -k install
:start /min D:\EditPlus\tools\php\Apache2\bin\httpd.exe -k start

:下面才能正真实现快速开机启动,auto为自启动，delayed-auto为延迟自启动
sc create httpd binPath= "D:\EditPlus\tools\php\Apache2\bin\httpd.exe  -k runservice" start= auto displayName= httpd
sc start httpd


