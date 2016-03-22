function get_height(height,mxd,value){
		//document.write(height+"\n");
		return height/2.0-1.0*height/2*(value-1500)/mxd;
}
function draw3(){
		var cvs=document.getElementById('rank_picture');
		var ctx = document.getElementById('rank_picture').getContext('2d');
		var width=cvs.width;
		var height=cvs.height-20;


		var lingrad = ctx.createLinearGradient(0,0,0,height);
		lingrad.addColorStop(0, 'rgba(255,0,0,255)');
		lingrad.addColorStop(0.3, 'rgba(252,199,55,255)');
		lingrad.addColorStop(0.7, 'rgba(203,21,209,255)');
		//jkllingrad.addColorStop(0.9, 'rgba(96,211,13,255)');
		ctx.fillStyle = lingrad;
		ctx.fillRect(0,0,width,height+20);


		var lst=document.getElementById('ranklist').innerHTML.split(" ");
		lst.length--;
		var vw=width/(lst.length+1);
		var mxd=0;
		var mxrating=-100;
		for (var i=0;i<lst.length;i++)
		{
				if (lst[i]==-1)continue;
				lst[i]=1000-lst[i];
				mxd=Math.max(mxd,Math.abs(lst[i]-1500));
				mxrating=Math.max(mxrating,lst[i]);
		}
		mxd=Math.max(mxd+50,300);

		for (var i=0;i<lst.length;i++)
		{
				if (i%5!=0)continue;
				ctx.strokeStyle='rgb(0,0,0)';
				ctx.font="8px serif";
				ctx.strokeText('#'+i,vw*i+vw/2,height+12);
		}

		ctx.beginPath();
		ctx.moveTo(vw/2,height/2);
		for (var i=1;i<lst.length;i++)
		{
				if (lst[i]==-1)continue;
				ctx.lineTo(i*vw+vw/2,height*0.9-lst[i]*height*0.8/1000);
				ctx.strokeStyle='rgba(252,199,121,.9)';
				ctx.strokeText((lst[i]/10).toFixed(1)+"%",vw*i+vw/2,height*0.9-lst[i]*height*0.8/1000);
		}
		ctx.strokeStyle='rgb(0,0,0)';
		ctx.stroke();
		ctx.strokeStyle='rgba(128,128,128,.4)';
		ctx.beginPath();
		//ctx.moveTo(10,1.0*height / 2);
		//ctx.lineTo(width-10,1.0*height /2);
		ctx.stroke();
}
