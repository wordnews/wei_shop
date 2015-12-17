/**
 * 后台搜索函数
 * @param url 当前路径 [ http://cqjunlong.com/admin.php?r=member/index ]
 */
function query(url)
{
    location.href = url + '&' + $('.query').serialize();
}




