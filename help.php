<!DOCTYPE html>
<html>
	<?php include 'environment-init.php';?>
	<?php include 'header.php';?>
	<link href="include/prism.css" rel="stylesheet" />
	<script src="include/prism.js"></script>
	<title>Helps to OjSeven</title>
	<div class='container'>
		<h1>Helps to OjSeven</h1>
		<div class='content'>
			<h2>评测环境</h2>
			<pre><font size="4">
CPU : Intel® Xeon(R) CPU X3440 @ 2.53GHz × 8 
System : Ubuntu 14.04 LTS 64-bit
Memory : 3.8 GiB
C++ : "g++ %.cpp -o % -DONLINE_JUDGE"
C : "gcc %.c -o % -DONLINE_JUDGE"
Pascal : "fpc %.pas -o%"
			</font></pre>
		</div>
		<div class='content'>
			<h2> Vim 乱码 </h2>
			添加入vimrc:
			<pre><code class='language-cpp line-numbers'>set fileencodings=utf-8,ucs-bom,gb18030,gbk,gb2312,cp936
set termencoding=utf-8
set encoding=utf-8</code></pre>
		</div>
		<div class='content'>
			<h2> Special Judge Formats</h2>
			<pre><code class='language-cpp line-numbers'>Special Judge 参数
args 1 : 输入文件路径
args 2 : 选手输出文件路径
args 3 : 答案文件输出路径
args 4 : 总分
args 5 : 分数文件路径
args 6 : Special Judge Details
加题后需要在题目配置文件末尾加一行spj文件名，并上传数据中加入spj的Linux可执行文件</code></pre>
			<pre><code class='language-cpp line-numbers'>#include&lt;cstdio&gt;
#include&lt;cstdlib&gt;
#include&lt;cstring&gt;
#include&lt;cmath&gt;

using namespace std;
const long double eps=1e-6;


int main(int,char* argv[])
{
	FILE *result=fopen(argv[5],"w");
	fprintf(result,"0\n");
	fclose(result);
	FILE *user_out=fopen(argv[2],"r");
	printf("0\n");
	FILE *standard_out=fopen(argv[3],"r");
	freopen(argv[6],"w",stderr);
	long double x,y;
	while (true)
	{
		int r1=fscanf(standard_out,"%Lf",&y);
		int r2=fscanf(user_out,"%Lf",&x);
		if (r1==-1 && r2==-1)
		{
			FILE *result=fopen(argv[5],"w");
			fprintf(result,"%s",argv[4]);
			return 0;
		}
		if (r1==-1)
		{
			fprintf(stderr,"Missing Standart Output\n");
			return 0;
		}
		if (r2==-1)
		{
			fprintf(stderr,"Missing User Output\n");
			return 0;
		}
		if (abs(x-y)>eps)
		{
			fprintf(stderr,"Expect %.10Lf,But Read%.10Lf",y,x);
			return 0;
		}
	}
	return 0;
}</code></pre>

		</div>
		<div class='content'>
			<h2> Answer Only And Special Judge Formats</h2>
			<pre><code class='language-cpp line-numbers'>Answer Only Special Judge 参数
args 1 : 输入文件路径
args 2 : 选手输出文件路径
args 3 : 答案文件输出路径
args 4 : 总分
args 5 : 分数文件路径
args 6 : Special Judge Details

题目配置文件：
第一行数据编号起点与终点
第二行输入数据文件名(%d匹配数字)
第三行选手输出文件名(%d匹配数字)
第四行标准输出文件名(%d匹配数字)
第五行Sepcial Judge Name

*注：
1.由于权限问题，请上传spj的源码，并在服务器端编译
2.需要实现判断输入文件存在性</code></pre>
<br/>
</div>
<?php include 'home-buildlog.html'; ?>
</div>
</html>

