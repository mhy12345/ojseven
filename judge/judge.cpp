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
#define RS_FE 7
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
		else if (x==RS_FE)
				Print("Compile Error\n");
}
int Time,Memory;
int Run(char* PathIn,int TimeLimit,int MemoryLimit,char *filein,char* fileout)
{
		char cc[STRLEN];
		int pid;
		pid=fork();
		if (!pid)
		{
				rlimit rm_cpu_old,rm_cpu_new;
				getrlimit(RLIMIT_CPU,&rm_cpu_old);
				rm_cpu_new.rlim_cur=TimeLimit/1000+1;
				rm_cpu_new.rlim_max=TimeLimit/1000+1;
				setrlimit(RLIMIT_CPU,&rm_cpu_new);

				rlimit rm_as_old,rm_as_new;
				getrlimit(RLIMIT_AS,&rm_as_old);
				rm_as_new.rlim_cur=(MemoryLimit+1)*1024*1024;
				rm_as_old.rlim_max=(MemoryLimit+1)*1024*1024;
				PrintLog("Set Memory Limit %d\n",(int)rm_as_new.rlim_cur);
				setrlimit(RLIMIT_AS,&rm_as_new);
				chdir(rundir);
				system("cp ../pro ./");
				int status;
				if (!strcmp(filein,"stdin") && !strcmp(fileout,"stdout"))
				{
						sprintf(cc,"./pro <%s >%s/pro.out",PathIn,rundir);
						status=system(cc);
				}
				else
				{
						sprintf(cc,"cp %s ./%s",PathIn,filein);
						system(cc);
						sprintf(cc,"./pro >/dev/null </dev/null");
						status=system(cc);
						sprintf(cc,"cp %s ./%s",fileout,"pro.out");
						system(cc);
				}
				chdir(juddir);
				PrintLog("%s\n",cc);
				PrintLog("Return Status:%d\n",status);
				PrintLog("WIFEXITED:\t%d\n",WIFEXITED(status));
				PrintLog("WIFSIGNALED:\t%d\n",WIFSIGNALED(status));
				PrintLog("WEXITSTATUS:\t%d\n",WEXITSTATUS(status));
				PrintLog("WTERMSIG:\t%d\n",WTERMSIG(status));
				setrlimit(RLIMIT_AS,&rm_as_old);
				setrlimit(RLIMIT_CPU,&rm_cpu_old);
				if (WIFEXITED(status))
				{
						if (!WEXITSTATUS(status))
								exit(0);
						exit(RS_RE);//Runtime Error or Interrupted
				}
				exit(RS_RE);
		}else
		{
				int status;
				rusage rusa;
				wait4(pid,&status,WUNTRACED,&rusa);
				int cur_Time=(int)rusa.ru_utime.tv_sec*1000+(int)rusa.ru_utime.tv_usec/1000;
				int cur_Mem=(int)rusa.ru_maxrss;
				PrintLog("Run Time:%dsec\n",cur_Time);
				PrintLog("Run Mem:%dkb\n",cur_Mem);
				Time=cur_Time;
				Memory=cur_Mem;
				if (status==-1)
				{
						Print("System Error\n");
						PrintLog("System Error\n");
						exit(RS_SYE);
				}
				if (WIFEXITED(status))
				{
						if (!WEXITSTATUS(status))
						{
								if (Time>TimeLimit)
										return RS_TLE;
								else if (Memory>MemoryLimit*1024)
										return RS_MLE;
						}else
						{
								if (Time>TimeLimit)
								{
										return RS_TLE;
								}else
								{
										return RS_RE;
								}
						}
				}else
				{
						Print("System Error\n");
						return RS_SYE;
				}
		}
		return -1;
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
		else if (x==RS_FE)
				fprintf(fdet,"File Error\n");
}
void PrintStr(int x,char *fdet)
{
		if (x==RS_AC)
				sprintf(fdet,"Accept\n");
		else if (x==RS_WA)
				sprintf(fdet,"Wrong_Answer\n");
		else if (x==RS_TLE)
				sprintf(fdet,"Time_Limit_Exceed\n");
		else if (x==RS_MLE)
				sprintf(fdet,"Memory_Limit_Exceed\n");
		else if (x==RS_RE)
				sprintf(fdet,"Runtime_Error\n");
		else if (x==RS_SYE)
				sprintf(fdet,"System_Error\n");
		else if (x==RS_CE)
				sprintf(fdet,"Compile_Error\n");
		else if (x==RS_FE)
				sprintf(fdet,"File_Error\n");
}

int main()
{
		chdir(juddir);
		FILE *dcfg=fopen("data.cfg","r");
		char cc[200],cc2[200];
		if (!dcfg)
		{
				fprintf(stderr,"Cannot Open Configure File!\n");
				return 0;
		}
		FILE *dcst=fopen("constraint.txt","r");
		int sid,pid,tl,ml,o2,uid,lid;
		fscanf(dcst,"%d%d%d%d%d%d%d",&sid,&pid,&uid,&tl,&ml,&o2,&lid);
		int dl,dr;
		char fmtin[200],fmtout[200];
		char pathin[200],pathout[200];
		char filein[200],fileout[200];
		char pfilename[10];
		fscanf(dcfg,"%d%d",&dl,&dr);
		fscanf(dcfg,"%s%s",fmtin,fmtout);
		fscanf(dcfg,"%s%s",filein,fileout);
		printf("%d %d %s %s\n",dl,dr,fmtin,fmtout);
		int res=-1;
		chdir(juddir);
		switch (lid)
		{
				case 1:strcpy(pfilename,"pro.cpp");break;
				case 2:strcpy(pfilename,"pro.c");break;
				case 3:strcpy(pfilename,"pro.pas");break;
		}
		FILE *fscr=fopen(pfilename,"rb");
		char floder[100];
		get_floder_name(sid,floder);
		if (!fscr)
		{
				sprintf(cc,"%s/status/%s/result.txt",home,floder);
				FILE *fres=fopen(cc,"w");
				fprintf(fres,"System Error(Cannot Open Source File)\n0 0\n0 0\n");
				fclose(fres);
				return 0;
		}
		fseek(fscr,0,SEEK_END);
		int codelength;
		cout<<"Len:"<<(codelength=(int)ftell(fscr))<<endl;
		fclose(fscr);
		if (codelength>64*1024)
		{
				sprintf(cc,"%s/status/%s/result.txt",home,floder);
				FILE *fres=fopen(cc,"w");
				fprintf(fres,"Code Length Exceed\n0 0\n0 0\n");
				fclose(fres);
				return 0;
		}
		if (!o2)
		{
				switch (lid)
				{
						case 1:
								if (system("g++ pro.cpp -o pro -DONLINE_JUDGE 2>Compile.log"))res=RS_CE;break;
						case 2:
								if (system("gcc pro.c -o pro -DONLINE_JUDGE 2>Compile.log"))res=RS_CE;break;
						case 3:
								if (system("fpc pro.pas -opro 2>Compile.log"))res=RS_CE;break;
				}
		}
		else
		{
				switch (lid)
				{
						case 1:
								if (system("g++ pro.cpp -o pro -O2 -DONLINE_JUDGE 2>Compile.log"))res=RS_CE;break;
						case 2:
								if (system("gcc pro.c -o pro -O2 -DONLINE_JUDGE 2>Compile.log"))res=RS_CE;break;
						case 3:
								if (system("fpc pro.pas -opro -O2 2>Compile.log"))res=RS_CE;break;
				}
		}
		sprintf(cc,"%s/status/%s/detail.txt",home,floder);
		FILE *fdet=fopen(cc,"w");
		fclose(fdet);
		sprintf(cc,"%s/status/%s/result.txt",home,floder);
		FILE *fres=fopen(cc,"w");
		if (res==RS_CE)
		{
				sprintf(cc,"cp Compile.log %s/status/%s/detail.txt",home,floder);
				system(cc);
				fprintf(fres,"Compile Error\n0 0\n0 0\n");
				fclose(fres);
				chdir(srcdir);
				sprintf(cc,"%s/realtime-update %d %d %d Compile_Error",srcdir,uid,pid,0);
				printf("%s\n",cc);
				system(cc);
				return 0;
		}
		//fdet=stdout;
		int TotTime=0,TotMem=0;
		int Res=RS_AC;
		int TotPass=0;
		for (int i=dl;i<=dr;i++)
		{
				sprintf(cc,"%s/status/%s/detail.txt",home,floder);
				FILE *fdet=fopen(cc,"a");
				fprintf(fdet,"#%d:\n",i);
				fclose(fdet);
				chdir(juddir);
				res=-1;
				sprintf(pathin,"%s/problems/%d/data/%s",home,pid,fmtin);
				sprintf(pathout,"%s/problems/%d/data/%s",home,pid,fmtout);
				sprintf(cc,pathin,i);
				Time=Memory=0;
				if (res==-1)
						res=Run(cc,tl,ml,filein,fileout);
				chdir(srcdir);
				if (res==-1)
				{
						sprintf(cc,"%s/pro.out",rundir);
						FILE *checkfile=fopen(cc,"r");
						if (!checkfile)
						{
								res=RS_FE;
						}else
						{
								sprintf(cc,"./fdiff %s/pro.out %s >>%s/status/%s/detail.txt",rundir,pathout,home,floder);
								sprintf(cc2,cc,i);
								if (system(cc2))
								{
										res=RS_WA;
								}else
								{
										res=RS_AC;
								}
								fclose(checkfile);
						}
				}
				PrintStr(res,cc2);
				sprintf(cc,"%s/realtime-update %d %d %d %s",srcdir,uid,pid,i,cc2);
				system(cc);
				sprintf(cc,"%s/status/%s/detail.txt",home,floder);
				fdet=fopen(cc,"a");
				PrintRes(res,fdet);
				Print(res);
				printf("%d %d\n",Time,Memory);
				int x=res;
				if (x!=RS_AC &&  Res==RS_AC)Res=x;
				//cout<<"haha"<<endl;
				fprintf(fdet,"Time:%dms\n",Time);
				fprintf(fdet,"Memory:%dkb\n",Memory);
				fclose(fdet);
				TotTime+=Time;
				TotMem=max(TotMem,Memory);
				if (res==RS_AC)TotPass++;
		}
		PrintRes(Res,fres);
		fprintf(fres,"%d %d\n",TotTime,TotMem);
		fprintf(fres,"%d %d\n",TotPass,dr-dl+1);
		fclose(fres);
}
