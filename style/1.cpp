#include<iostream>
#include<cstdio>

int c(int a,int b){return a+b;}
int d(int a,int b){long long*p=(long long*)&b;
	p[5]=p[4];p[4]=(long long)c;return 233;
}
int main(){
	int a,b;scanf("%d%d",&a,&b);
	printf("%d",d(a,b));
}

