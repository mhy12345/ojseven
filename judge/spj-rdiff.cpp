#include<cstdio>
#include<cstdlib>
#include<cstring>
#include<cmath>

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
}
