#include<cstdio>
#include<mysql/mysql.h>
#include<cstring>
#include<cstdlib>
#include<unistd.h>
#include<algorithm>
#include<iostream>
using namespace std;
const char *host = "localhost";
const char *user = "root";
const char *db   = "oj7database";
char* pass;

void init_pass()
{
		pass=(char*)malloc(sizeof(char)*100);
		FILE *fpass=fopen("../sqlpasswd.txt","r");
		fscanf(fpass,"%s",pass);
}

int main(int argc,char* args[])
{
		init_pass();
		MYSQL mysql;
		mysql_init(&mysql);
		if (!mysql_real_connect(&mysql, host, user, pass, db, 0, NULL, 0)) {
				printf("%s", mysql_error(&mysql));
				mysql_close(&mysql);
		}
		mysql_query(&mysql,"SET NAMES GBK");
		char cc[300];
		sprintf(cc,"INSERT INTO statusdetail (user,problem,task,result,deadtime) VALUES (%s,%s,%s,'%s',DATE_ADD(now(),INTERVAL 20 MINUTE))",args[1],args[2],args[3],args[4]);
		//printf("%s\n",cc);
		mysql_query(&mysql,cc);
		mysql_query(&mysql,"DELETE FROM statusdetail WHERE (deadtime<now())");
}
