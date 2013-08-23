#region 删除指定文件夹下的文件[DeleteFolder]
/// <summary>
/// 删除指定文件夹下的文件[DeleteFolder]
/// </summary>123
/// <param name="aimPath">文件路径</param>
public static void DeleteFolder(string aimPath)
{
    int splitCount = aimPath.Split('|').Length;  //要删除的文件在数据库中的编号
    string PFileth = HttpContext.Current.Server.MapPath("/");

    if (splitCount == 1)
    {
        string filePath = PFileth + aimPath;  //获取项目路径
        if (File.Exists(filePath))
        {
            FileInfo fi = new FileInfo(filePath);
            if (fi.Attributes.ToString().IndexOf("ReadOnly") != -1)
            {
                fi.Attributes = FileAttributes.Normal;
            }
            File.Delete(filePath);//直接删除其中的文件   
        }
    }
    else if (splitCount > 1)
    {

        for (int i = 0; i < splitCount; i++)
        {
            string filePath = PFileth + aimPath.Split('|')[i];  //获取项目路径
            if (File.Exists(filePath))
            {
                FileInfo fi = new FileInfo(filePath);
                if (fi.Attributes.ToString().IndexOf("ReadOnly") != -1)
                {
                    fi.Attributes = FileAttributes.Normal;
                }
                File.Delete(filePath);//直接删除其中的文件   
            }
        }

    }

}
#endregion