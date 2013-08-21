<%@ page contentType="text/html;charset=gb2312"%>
<script language="JavaScript">
   // 验证是否满足最小长度
   function minLength(str,length)
   {
	   if(str.length>=length)
		   return true;
	   else
		   return false;
   }
   // 判断是否满足最大长度
   function maxLength(str,length)
   {
	   if(str.length<=length)
		   return true;
	   else
		   return false;
   }
</script>
<html>
   <head>
      <title>用户登陆</title>
   </head>
   <body>
      <h2>用户登录</h2>
      <form name="form1" action="${pageContext.request.contextPath}/login" method="post"   onsubmit="return isValidate(form1)">
	      用户名：<input type="text" name="username"> <br>
	      口令：<input type="password" name="userpass"><br>
	      <input type="reset" value="重置">
	      <input type="submit" value="提交"><br>
      </form>
   </body>
</html>
