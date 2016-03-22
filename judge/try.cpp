#include<cstdio>
#include<cstring>
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

int main()
{
	char tmp[100];
	get_floder_name(1025,tmp);
	printf("%s",tmp);
}
