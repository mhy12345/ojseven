#include<cstdio>
#include<mysql/mysql.h>
#include<cstring>
#include<cstdlib>
#include<unistd.h>
#include<algorithm>
using namespace std;
const char *host = "localhost";
const char *user = "root";
const char *db   = "oj7database";
const char *home = "/server/www";
const char *srcdir = "/server/www/judge";
const char *juddir = "/server/www/judge/test";
const char *rundir = "/server/www/judge/test/run";
char* pass;

void get_floder_name(int sid,char *re)
{
	re[0]='\0';
	char tmp[100];
	while(sid)
	{
		int l=strlen(re);
		for(int i=0;i<=l;i++)
			tmp[i]=re[i];
		sprintf(re,"%d/%s",sid&1023,tmp);
		sid/=1024;
	}
	int l=strlen(re);
	re[l-1]='\0';
	return ;
}

void GetProblemInfo(int id,char* name,int &tl,int &ml,int &o2)
{
	MYSQL mysql;
	mysql_init(&mysql);
	if (!mysql_real_connect(&mysql, host, user, pass, db, 0, NULL, 0)) {
		printf("%s", mysql_error(&mysql));
		mysql_close(&mysql);
		return ;
	}
	mysql_query(&mysql,"SET NAMES GBK");
	char cc[90];
	sprintf(cc,"SELECT name,timelimit,memorylimit,iso2 from problems where id=%d",id);
	mysql_query(&mysql,cc);
	MYSQL_RES *result;
	result=mysql_store_result(&mysql);
	MYSQL_ROW row=mysql_fetch_row(result);
	int x;
	strcpy(name,row[0]);
	sscanf(row[1],"%d",&tl);
	sscanf(row[2],"%d",&ml);
	sscanf(row[3],"%d",&o2);
	mysql_close(&mysql);
}
void syserror(int sid)
{
	MYSQL mysql;
	mysql_init(&mysql);
	if (!mysql_real_connect(&mysql, host, user, pass, db, 0, NULL, 0)) {
		printf("%s", mysql_error(&mysql));
		mysql_close(&mysql);
		return ;
	}
	char cc[100];
	sprintf(cc,"UPDATE statuses set result='system error' where id=%d",sid);
	mysql_query(&mysql,cc);
	mysql_close(&mysql);
}

void init_pass()
{
	pass=(char*)malloc(sizeof(char)*100);
	FILE *fpass=fopen("../sqlpasswd.txt","r");
	fscanf(fpass,"%s",pass);
}
void check_schedule(MYSQL &mysql)
{
	MYSQL_RES *result,*result2;
	MYSQL_ROW row,row2;
	mysql_query(&mysql,"SELECT action,value FROM schedule WHERE activate_time<=now() AND done=FALSE");
	result=mysql_store_result(&mysql);
	char cc[200];
	while ((row=mysql_fetch_row(result))!=NULL)
	{
		if (!strcmp(row[0],"contest-stop"))
		{
			printf("Event:Contest_Stop\n");
			sprintf(cc,"php schedule-contest-stop.php");
			system(cc);
		}
		if (!strcmp(row[0],"contest-start"))
		{
			printf("Event:Contest_Start\n");
			sprintf(cc,"php schedule-contest-start.php");
			system(cc);
		}
		if (!strcmp(row[0],"contest-make"))
		{
			printf("Event:Contest_Make\n");
			sprintf(cc,"php schedule-contest-make.php");
			system(cc);
		}
		if (!strcmp(row[0],"rating-update"))
		{
			printf("Event:Rating_Update\n");
			sprintf(cc,"php schedule-rating-update.php");
			system(cc);
		}
		if (!strcmp(row[0],"contest-waiting"))
		{
			printf("Event:Contest_Waiting\n");
			sprintf(cc,"php schedule-contest-waiting.php");
			system(cc);
		}
		if (!strcmp(row[0],"contest-pending"))
		{
			printf("Event:Contest_Pending\n");
			sprintf(cc,"php schedule-contest-pending.php");
			system(cc);
			break;
		}
		if (!strcmp(row[0],"contest-export-score"))
		{
			printf("Event:Contest_Export_Score\n");
			sprintf(cc,"php schedule-contest-export-result.php");
			system(cc);
			break;
		}
		if (!strcmp(row[0],"acm-updatescore"))
		{
			printf("Event:ACM Updating\n");
			sprintf(cc,"php schedule-acm-updatescore.php");
			system(cc);
			break;
		}
	}
	if (result)mysql_free_result(result);
}
void check_status(MYSQL &mysql)
{
	MYSQL_RES *result,*result2;
	MYSQL_ROW row,row2;
	mysql_query(&mysql,"SELECT id,problem,user,language from statuses where result=\"Pending\"");
	result=mysql_store_result(&mysql);
	char cc[200];
	usleep(10);
	getcwd(cc,100);
	while ((row=mysql_fetch_row(result))!=NULL)
	{
		int sid,pid,uid,lid;
		int isspj,issubmit;
		char pname[30];int tl,ml,o2;
		char lan[30],pfilename[30];
		chdir(home);
		sscanf(row[0],"%d",&sid);
		sscanf(row[1],"%d",&pid);
		sscanf(row[2],"%d",&uid);
		sscanf(row[3],"%s",lan);
		if (strcmp(lan,"C++")==0)
			lid=1,strcpy(pfilename,"pro.cpp");
		else if (strcmp(lan,"C")==0)
			lid=2,strcpy(pfilename,"pro.c");
		else if (strcmp(lan,"Pascal")==0)
			lid=3,strcpy(pfilename,"pro.pas");
		GetProblemInfo(pid,pname,tl,ml,o2);
		printf("Status id:%d Problem id:%d User id:%d\n",sid,pid,uid);
		printf("TimeLimit:%d MemoryLimit:%d Is O2:%s\n",tl,ml,(o2)?"YES":"NO");
		sprintf(cc,"mkdir %s\n",juddir);system(cc);
		sprintf(cc,"mkdir %s\n",rundir);system(cc);
		sprintf(cc,"SELECT isspj,issubmit FROM problems WHERE id=%d",pid);
		mysql_query(&mysql,cc);
		result2=mysql_store_result(&mysql);
		row2=mysql_fetch_row(result2);
		sscanf(row2[0],"%d",&isspj);
		sscanf(row2[1],"%d",&issubmit);
		char floder[100];
		get_floder_name(sid,floder);
		if (issubmit)
		{
			sprintf(cc,"unzip %s/status/%s/answer.zip -d %s",home,floder,rundir);system(cc);
			printf("%s\n",cc);
			sprintf(cc,"cp ./problems/%d/data.cfg %s\n",pid,juddir);system(cc);
			sprintf(cc,"%s/constraint.txt",juddir);
			FILE *cfile=fopen(cc,"w");
			fprintf(cfile,"%d\n%d\n%d\n",sid,pid,uid);
			fclose(cfile);
			chdir(srcdir);
			if (!isspj)
				system("./ajudge");
			else
				system("./asjudge");
		}else
		{
			sprintf(cc,"cp ./status/%s/%s %s/%s\n",floder,pfilename,juddir,pfilename);system(cc);
			sprintf(cc,"cp ./problems/%d/data.cfg %s\n",pid,juddir);system(cc);
			sprintf(cc,"%s/constraint.txt",juddir);
			FILE *cfile=fopen(cc,"w");
			fprintf(cfile,"%d\n%d\n%d\n%d\n%d\n%d\n%d\n",sid,pid,uid,tl,ml,o2,lid);
			fclose(cfile);
			chdir(srcdir);
			if (isspj)
				system("./sjudge");
			else
				system("./judge");
		}
		sprintf(cc,"%s/status/%s/result.txt",home,floder);
		FILE *fres=fopen(cc,"r");
		if (!fres)
			printf("Error!!!\n");
		char rc[30];
		fgets(rc,sizeof(rc),fres);
		if (rc[strlen(rc)-1]=='\n')rc[strlen(rc)-1]=0;
		int tt,mm;
		fscanf(fres,"%d%d",&tt,&mm);
		int s1,s2;
		fscanf(fres,"%d%d",&s1,&s2);
		fclose(fres);
		sprintf(cc,"update statuses set result='%s' where id=%d",rc,sid);
		if (mysql_query(&mysql,cc))
			printf("Error!!!!:%s\n",mysql_error(&mysql));
		sprintf(cc,"update statuses set time=%d where id=%d",tt,sid);
		if (mysql_query(&mysql,cc))
			printf("Error!!!!:%s\n",mysql_error(&mysql));
		sprintf(cc,"update statuses set memory=%d where id=%d",mm,sid);
		if (mysql_query(&mysql,cc))
			printf("Error!!!!:%s\n",mysql_error(&mysql));
		sprintf(cc,"update statuses set score='%d/%d' where id=%d",s1,s2,sid);
		if (mysql_query(&mysql,cc))
			printf("Error!!!!:%s\n",mysql_error(&mysql));
		if (strcmp(rc,"Accept")==0)
		{
			sprintf(cc,"SELECT count(DISTINCT problem) FROM statuses WHERE user=%d AND result='Accept'",uid);
			mysql_query(&mysql,cc);
			result2=mysql_store_result(&mysql);
			row2=mysql_fetch_row(result2);
			sprintf(cc,"UPDATE users SET acs=%s WHERE id=%d",row2[0],uid);
			mysql_query(&mysql,cc);
			/*
			  mhy naive;--shimakaze
			   sprintf(cc,"select count(distinct problem) from statuses where user=%d and result='Accept' and submittime > (select date_format(now(),'%%y-%%m-%%d'))",uid);
			   mysql_query(&mysql,cc);
			   result2=mysql_store_result(&mysql);
			   row2=mysql_fetch_row(result2);
			   sprintf(cc,"update users set Dacs=%s where id=%d",row2[0],uid);
			   mysql_query(&mysql,cc);
			   */
		}
		sprintf(cc,"rm -r %s",juddir);system(cc);
	}
	if (result)mysql_free_result(result);
	chdir(srcdir);
}

int main()
{
	char cc[100];
	sprintf(cc,"%s/judge",home);
	chdir(cc);
	init_pass();
	MYSQL mysql;
	mysql_init(&mysql);
	if (!mysql_real_connect(&mysql, host, user, pass, db, 0, NULL, 0)) {
		printf("%s", mysql_error(&mysql));
		mysql_close(&mysql);
	}
	printf("YES, Connecting succeed!\n");
	mysql_query(&mysql,"SET NAMES GBK");
	while(true)
	{
		sleep(1);
		check_status(mysql);
		check_schedule(mysql);
	}
	return 0;
}
