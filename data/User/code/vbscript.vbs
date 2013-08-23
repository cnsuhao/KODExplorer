Set OS = GetObject("winmgmts:\\.\root\cimv2")
Set CF = OS.ExecQuery("Select * From Win32_ShortcutFile WHERE Name = '" & Replace(WScript.Arguments(0),"\","\\") & "'")
Set WS = WScript.CreateObject("WScript.Shell")
For Each objFile in CF
WS.Run ("explorer /e,/select," & objFile.Target)
Next