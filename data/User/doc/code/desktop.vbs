'读取注册表，获取桌面位置。
Dim reg
Set reg=WScript.CreateObject("WScript.Shell") 
Dim regs
regs=reg.RegRead("HKEY_CURRENT_USER\Software\Microsoft\Windows\CurrentVersion\Explorer\Shell Folders\Desktop") 
'MsgBox(regs)

'追加字符串
temp1="copy C:\EditPlus\others\EditPlus.lnk "+chr(34)
temp2="\EditPlus.lnk"+chr(34)
'MsgBox(temp1+regs+temp2)

'写文件
Set fso = CreateObject("Scripting.FileSystemObject")    
set ts=fso.opentextfile("desktop.cmd",2,true)     
ts.Write temp1+regs+temp2 &VbCrLf
'ts.Write temp1+regs+temp2 
ts.close

Set ws=WScript.CreateObject("WScript.Shell") 
ws.run "desktop.cmd"
ws.run "cmd /k del desktop.cmd &exit"
MsgBox("         恭喜！Editplus 3.2    "+VbCrLf+"         kalcaddle 超强集成版"+VbCrLf+"         绿化安装成功^_^..."+VbCrLf+VbCrLf+VbCrLf+"强烈建议开启ClearType字体美化渲染"+VbCrLf+"字体边缘平滑更加赏心悦目！"+VbCrLf+"(勾选“启用 ClearType” 点击应用即可)")

