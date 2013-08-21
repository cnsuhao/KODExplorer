package servlets;

import java.io.IOException;
import java.io.PrintWriter;
import beans.*;
import javax.servlet.http.HttpSession;
import javax.servlet.RequestDispatcher;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

public class LoginServlet extends HttpServlet {

	/**
	 * Constructor of the object.
	 */
	public LoginServlet() {
		super();
	}

	/**
	 * Destruction of the servlet. <br>
	 */
	public void destroy() {
		super.destroy(); // Just puts "destroy" string in log
		// Put your code here
	}

	/**
	 * The doGet method of the servlet. <br>
	 *
	 * This method is called when a form has its tag value method equals to get.
	 * 
	 * @param request the request send by the client to the server
	 * @param response the response send by the server to the client
	 * @throws ServletException if an error occurred
	 * @throws IOException if an error occurred
	 */
	public void doGet(HttpServletRequest request, HttpServletResponse response)
			throws ServletException, IOException {

		 // 获取用户输入的用户ID和口令
	      String userid = request.getParameter("username");
	      String userpass = request.getParameter("userpass");
	      // 创建模型对象
	      LoginBean loginBean = new LoginBean();
	      // 调用业务方法进行验证
	      boolean b = loginBean.validate(userid,userpass);
	      // 要转向的文件
	      String forward;
	      // 如果登陆成功，把用户名写入session中，并且转向success.jsp，
//	否则转向failure.jsp
	      if(b){	         
	         // 获取session
	         HttpSession session = (HttpSession)request.getSession(true);
	         // 把用户名保存到session中
	         session.setAttribute("userid",userid);
	          // 目标转向文件是success.jsp
	         forward = "success.jsp";
	      }else{
	         // 目标转向文件是failure.jsp
	         forward = "failure.jsp";
	      }	            
	      // 获取Dispatcher对象
	      RequestDispatcher dispatcher = request.getRequestDispatcher(forward);
	      // 完成跳转
	      dispatcher.forward(request,response);
	}


	/**
	 * The doPost method of the servlet. <br>
	 *
	 * This method is called when a form has its tag value method equals to post.
	 * 
	 * @param request the request send by the client to the server
	 * @param response the response send by the server to the client
	 * @throws ServletException if an error occurred
	 * @throws IOException if an error occurred
	 */
	public void doPost(HttpServletRequest request, HttpServletResponse response)
			throws ServletException, IOException {

		response.setContentType("text/html");
		PrintWriter out = response.getWriter();
		out
				.println("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">");
		out.println("<HTML>");
		out.println("  <HEAD><TITLE>A Servlet</TITLE></HEAD>");
		out.println("  <BODY>");
		out.print("    This is ");
		out.print(this.getClass());
		out.println(", using the POST method");
		out.println("  </BODY>");
		out.println("</HTML>");
		out.flush();
		out.close();
		doGet(request,response);
	}

	/**
	 * Initialization of the servlet. <br>
	 *
	 * @throws ServletException if an error occurs
	 */
	public void init() throws ServletException {
		// Put your code here
	}

}
