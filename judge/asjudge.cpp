#include<iostream>
#include<cstdio>
#include<cstdlib>
#include<cstring>
#include<algorithm>
#include<sys/types.h>
#include<sys/time.h>
#include<sys/resource.h>
#include<sys/wait.h>
#include<sys/signal.h>
#include<signal.h>
#include<unistd.h>
#include<map>
#include<string>
#include<fstream>
#include"function.hpp"
using namespace std;
#define STRLEN 200
#define RS_SYE 10
#define RS_NOR 0
#define RS_AC 1
#define RS_TLE 2
#define RS_RE 3
#define RS_MLE 4
#define RS_WA 5
#define RS_CE 6
#define PrintLog(...)\
{\
		FILE *Log=fopen("/server/www/judge/log.txt","a");\
		fprintf(Log,##__VA_ARGS__);\
		fclose(Log);\
}
const char* home="/server/www";
const char* srcdir="/server/www/judge";
const char* juddir="/server/www/judge/test";
const char* rundir="/server/www/judge/test/run";
void Print(const char *cc)
{
		printf("\033[1m");
		if (strstr(cc,"Accept"))
		{
				printf("\033[32m%s",cc);
		}else if (strstr(cc,"Wrong Answer"))
		{
				printf("\033[31m%s",cc);
		}else if (strstr(cc,"Time Limit Exceed"))
		{
				printf("\033[33m%s",cc);
		}else if (strstr(cc,"Memory Limit Exceed"))
		{
				printf("\033[34m%s",cc);
		}else if (strstr(cc,"Compile Error"))
		{
				printf("\033[35m%s",cc);
		}else if (strstr(cc,"Runtime Error"))
		{
				printf("\033[36m%s",cc);
		}else
				printf("%s\n",cc);
		printf("\033[0m");
}
void Print(int x)
{
		if (x==RS_AC)
				Print("Accept\n");
		else if (x==RS_WA)
				Print("Wrong Answer\n");
		else if (x==RS_TLE)
				Print("Time Limit Exceed\n");
		else if (x==RS_MLE)
				Print("Memory Limit Exceed\n");
		else if (x==RS_RE)
				Print("Runtime Error\n");
		else if (x==RS_SYE)
				Print("System Error\n");
		else if (x==RS_CE)
				Print("Compile Error\n");
}
void PrintRes(int x,FILE *fdet)
{
		if (x==RS_AC)
				fprintf(fdet,"Accept\n");
		else if (x==RS_WA)
				fprintf(fdet,"Wrong Answer\n");
		else if (x==RS_TLE)
				fprintf(fdet,"Time Limit Exceed\n");
		else if (x==RS_MLE)
				fprintf(fdet,"Memory Limit Exceed\n");
		else if (x==RS_RE)
				fprintf(fdet,"Runtime Error\n");
		else if (x==RS_SYE)
				fprintf(fdet,"System Error\n");
		else if (x==RS_CE)
				fprintf(fdet,"Compile Error\n");
}

int main()
{
		printf("Start Answer && Special Judge mode\n");
		chdir(juddir);
		FILE *dcfg=fopen("data.cfg","r");
		if (!dcfg)
		{
				printf("Data Configure Not Found!\n");
				return 0;
		}
		FILE *dcst=fopen("constraint.txt","r");
		if (!dcst)
		{
				printf("Constraint File Not Found!\n");
				return 0;
		}
		int sid,pid,uid;
		fscanf(dcst,"%d%d%d",&sid,&pid,&uid);
		int dl,dr;
		char floder[100];
		get_floder_name(sid,floder);
		char fmtin[200],fmtout[200],fmtans[200];
		char pathstd[200],pathout[200];
		char spjname[200];
		char pathscore[200];
		char pathin[200];
		fscanf(dcfg,"%d%d",&dl,&dr);
		fscanf(dcfg,"%s%s%s",fmtin,fmtout,fmtans);
		fscanf(dcfg,"%s",spjname);
		printf("%d %d %s %s\n",dl,dr,fmtin,fmtout);
		char cc[500];
		int res=-1;
		chdir(juddir);
		sprintf(cc,"%s/status/%s/detail.txt",home,floder);
		FILE *fdet=fopen(cc,"w");
		fclose(fdet);
		sprintf(cc,"%s/status/%s/result.txt",home,floder);
		FILE *fres=fopen(cc,"w");
		int Res=RS_AC;
		int TotPass=0;
		sprintf(cc,"cp %s/problems/%d/data/%s %s/checker",home,pid,spjname,juddir);
		system(cc);
		sprintf(pathscore,"%s/score.txt",juddir);
		for (int i=dl;i<=dr;i++)
		{
				sprintf(cc,"%s/status/%s/detail.txt",home,floder);
				FILE *fdet=fopen(cc,"a");
				fprintf(fdet,"#%d:\n",i);
				fclose(fdet);
				chdir(juddir);
				res=-1;
				sprintf(cc,"%s/problems/%d/data/%s",home,pid,fmtans);
				sprintf(pathstd,cc,i);
				sprintf(cc,"%s/%s",rundir,fmtout);
				sprintf(pathout,cc,i);
				sprintf(cc,"%s/problems/%d/data/%s",home,pid,fmtin);
				sprintf(pathin,cc,i);
				chdir(srcdir);
				sprintf(cc,"%s/detail.txt",juddir);
				FILE *ftmp=fopen(cc,"w");fclose(ftmp);
				sprintf(cc,"%s/checker %s %s %s %d %s %s/detail.txt",juddir,pathin,pathout,pathstd,100/(dr-dl+1),pathscore,juddir);
				//input-path output-path answer-path score score-path detail-path
				printf("%s\n",cc);
				system(cc);
				sprintf(cc,"cat %s/detail.txt >> %s/status/%s/detail.txt",juddir,home,floder);
				system(cc);
				FILE *fscore=fopen(pathscore,"r");
				if (fscanf(fscore,"%d",&res)==-1)
						res=0;
				fclose(fscore);
				sprintf(cc,"rm %s",pathscore);
				system(cc);
				TotPass+=res;
		}
		sprintf(cc,"rm %s/*",juddir);
		if (TotPass>=(dr-dl+1)*10)
				Res=RS_AC;
		else
				Res=RS_WA;
		PrintRes(Res,fres);
		fprintf(fres,"%d %d\n",0,0);
		//fprintf(fres,"%d %d\n",TotPass,(dr-dl+1)*10);
		fprintf(fres,"%d %d\n",TotPass,100);
		fclose(fres);
}
