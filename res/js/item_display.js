//������ֲ�����ȷ��
   function confirmOperation(url){
   
    if(confirm("��ȷ��Ҫ���д˲�����")){  	
  	window.open(url,'_self');
  	}
   	
   } 
   

//�����´�

function assign(form){
 if(confirm("��ȷ��Ҫ���� �����´� ������")){	
    with(form){
    	var j=0;
    	
    	var max = form.taskcheck.length;

    	for (var idx = 0; idx < max; idx++) {
			if (eval("document.queryplan.taskcheck[" + idx + "].checked") == true) {
			j =j+1;
			}
		}
		    if(eval("document.queryplan.taskcheck.checked") == true)
		    {		    
		        j = j +1;
		    }			
	    if(j==0){ 
			window.alert("����ѡ��Ҫ�´������!");
			return false;
	    }
    }
	window.alert("��ʾ:ֻ��δ�´������ſ��´�!");
	form.action="TaskServlet?action=assignmoretask";
	
	form.submit();
 }	
}

//��������

function feedbackmore(form){
 if(confirm("��ȷ��Ҫ���� �������� ������")){	
    with(form){
    	var j=0;
    	
    	var max = form.taskcheck.length;

    	for (var idx = 0; idx < max; idx++) {
			if (eval("document.queryplan.taskcheck[" + idx + "].checked") == true) {
			j =j+1;
			}
		}
		    if(eval("document.queryplan.taskcheck.checked") == true)
		    {		    
		        j = j +1;
		    }			
	if(j==0){ 
			window.alert("����ѡ��Ҫ����������!");
			return false;
	}
    }
	window.alert("��ʾ:ֻ��δ����������ſɷ���!");
	form.action="AdviceServlet?urlaction=feedbackmoretask";
	
	form.submit();
 }	
}

//�������

function auditmore(form){
 if(confirm("��ȷ��Ҫ���� ������� ������")){	
    with(form){
    	var j=0;
    	
    	var max = form.taskcheck.length;

    	for (var idx = 0; idx < max; idx++) {
			if (eval("document.queryplan.taskcheck[" + idx + "].checked") == true) {
			j =j+1;
			}
		}
		    if(eval("document.queryplan.taskcheck.checked") == true)
		    {		    
		        j = j +1;
		    }			
	    if(j==0){ 
			window.alert("����ѡ��Ҫ��˵�����!");
			return false;
	    }
    }
	window.alert("��ʾ:ֻ��δ��˵�����ſ����!");
	form.action="AdviceServlet?urlaction=auditmoretask";
	
	form.submit();
 }	
}

//����ѡ��������
function paste(form)
{
    if(confirm("��ȷ��Ҫ���� �������� ������"))
    {
	    with(form)
	    {
		    var j=0;
    	    var max = form.taskcheck.length;
    	
		    for (var idx = 0; idx <max; idx++) 
		    {
			
			    if (eval("document.queryplan.taskcheck[" + idx + "].checked") == true) {
			    j =j+1;
			    }
		    }
		    if(eval("document.queryplan.taskcheck.checked") == true)
		    {		    
		        j = j +1;
		    }		
		    if(j == 0)
		    { 
			    window.alert("����ѡ��Ҫ����������!");
			    return false;
		    }
        }
        //alert("j:"+j);
	    form.action="TaskServlet?action=paste";
	    form.submit();
    }	
}

//���ҵ������б�����ɾ��ѡ��������
function deleteNotSend(form)
{
    if(confirm("��ȷ��Ҫ����  ����ɾ�� ������"))
    {
	    with(form)
	    {
		    var j = 0;
    	    var max = form.taskcheck.length;    	
		    for(var idx = 0; idx < max; idx++) 
		    {		   			
			    if(eval("document.queryplan.taskcheck[" + idx + "].checked") == true) 
			    {
			        j = j+1;
			    }
		    }
		    if(eval("document.queryplan.taskcheck.checked") == true)
		    {		    
		        j = j +1;
		    }
		    if(j==0)
		    { 
			    window.alert("����ѡ��Ҫɾ��������!");
			    return false;
		    }
        }    
	    form.action="TaskServlet?action=deleteMoreTask";
	    form.submit();
    }
}

//���´����д�
function toUrl(s)
 {
 	var url =s;
	window.open(url);
 }
 
//���´����д�
function url_in_newwindow(url,windowname,actiontext){
	var newurl="<a href='#' onclick='javascript:window.open(\""+url+"\",\""+windowname+"\",\"toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,left=100,top=150,width=600,height=350\")' class='link1'>"+actiontext+"</a>";
	
	document.location.href=newurl;
}

function openfullwindow(url,windowname,actiontext){
	var newurl="<a href='#' onclick='javascript:window.open(\""+url+"\",\""+windowname+"\",\"toolbar=yes,location=no,directories=no,status=yes,menubar=yes,scrollbars=yes,resizable=yes,fullscreen=yes\")' class='link1'>"+actiontext+"</a>";
	
	document.location.href=newurl;
}
//ѡ��ͨѶ¼
function choose_address(form)
{
	with(form){
	 var idx = document.all.address.selectedIndex ;
     var value=document.all.address.options[idx].value;
     var text=document.all.address.options[idx].text;  
     document.all.address.value=value;
     document.all.addressname.value=text;    
    }  
    form.submit();
}
//ѡ���·�ʱ�ύ��
function choose_taskmonth(form)
{
	with(form){
	 var idx = document.all.taskmonth.selectedIndex ;
     var value=document.all.taskmonth.options[idx].value;
     var text=document.all.taskmonth.options[idx].text;  
     document.all.taskmonth.value=value;
     document.all.taskmonth.text=text;
     form.submit();
    }  
}
//������ҵ���µĲ���,����,��Ա����
//ѡ����ҵ��,ˢ�±�
function choose_entp(form)
{
	with(form){
	 var idx = document.all.ENTPdept.selectedIndex ;
     var value=document.all.ENTPdept.options[idx].value;
     var text=document.all.ENTPdept.options[idx].text;  
     document.all.ENTPdept.value=value;
     document.all.ENTPdeptname.value=text;
     form.submit();
    }  
}
//ѡ����,ˢ�±�
function choose_dept(form)
{ with(form){
	 var idx = document.all.deptId.selectedIndex ;
     var value=document.all.deptId.options[idx].value;
     var text=document.all.deptId.options[idx].text;
     document.all.deptId.value=value;
     document.all.deptname.value=text;
     form.submit();
    }  
}
//ѡ�����,ˢ�±�
function choose_sect(form)
{ with(form){
	 var idx = document.all.sectId.selectedIndex ;
     var value=document.all.sectId.options[idx].value;
     var text=document.all.sectId.options[idx].text;
     document.all.sectId.value=value;
     document.all.sectname.value=text;
     form.submit();
    }  
}
//ѡ���Ʒ���
function choose_productType(form)
{
	with(form){
	 var idx = document.all.productType.selectedIndex ;
     var value=document.all.productType.options[idx].value;
     var text=document.all.productType.options[idx].text;  
     document.all.productType.value=value;
     document.all.productTypeName.value=text;
     document.all.formAction.value='productType';
     form.submit();
    }  
}
//ѡ����Ŀ����
function choose_pjtType(form)
{
	with(form){
	 var idx = document.all.pjtType.selectedIndex ;
     var value=document.all.pjtType.options[idx].value;
     var text=document.all.pjtType.options[idx].text;  
     document.all.pjtType.value=value;
     document.all.pjtTypeName.value=text;
     document.all.formAction.value='pjtType';
     form.submit();
    }  
}
//ѡ����ĿС��
function choose_pjtSubType(form)
{
	with(form){
	 var idx = document.all.pjtSubType.selectedIndex ;
     var value=document.all.pjtSubType.options[idx].value;
     var text=document.all.pjtSubType.options[idx].text;  
     document.all.pjtSubType.value=value;
     document.all.pjtSubTypeName.value=text;
     document.all.formAction.value='pjtSubType';
     form.submit();
    }  
}
//ѡ����
function choose_person(form)
{ with(form){
	 var idx = document.all.personId.selectedIndex ;
     var value=document.all.personId.options[idx].value;
     var text=document.all.personId.options[idx].text;
     document.all.personId.value=value;
     document.all.personName.value=text;
     //form.submit();
    }  
}
//ѡ���Ʒ
function choose_product(form)
{ with(form){
	 var idx = document.all.productId.selectedIndex ;
     var value=document.all.productId.options[idx].value;
     var text=document.all.productId.options[idx].text;
     document.all.productId.value=value;
     document.all.productName.value=text;
     //form.submit();
   }  
}
//������������
function choose_report(form)
{ with(form){
	 var idx = document.all.reportId.selectedIndex ;
     var value=document.all.reportId.options[idx].value;
     var text=document.all.reportId.options[idx].text;
     document.all.reportId.value=value;
     document.all.reportname.value=text;
     //form.submit();
    }  
}

function doSearch(form,userid,username){
   if(username.value=="")
     alert("������Ҫ��ѯ������!");
   else
   	 window.open("../newPersonFind.jsp?form="+form.name+"&userid="+userid.name+"&username="+username.name+"&employeeName="+username.value,'SearchPerson','toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,left=100,top=150,width=600,height=400');
}

function doSearchEmployee(form,userid,username){
	if(username.value==""){
        alert("������Ҫ��ѯ������!");
    }else{
    	window.open("./wbs/Plan_Select_Executor.jsp?formName="+form+"&condition="+username.value,'ѡ����Ա','width=450,height=450,status=yes,resizable=yes,top=200,left=200,scrollbars=yes');
    	
	}
   //ѡ��ִ����
}


function doSearch2(form,userid,username,validname){
   if(username.value=="")
     alert("������Ҫ��ѯ������!");
   else
   	 window.open("../newPersonFind.jsp?form="+form.name+"&userid="+userid.name+"&username="+username.name+"&employeeName="+username.value+"&validname="+validname.name,'SearchPerson','toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,left=100,top=150,width=600,height=400');
}

//��������Ⱥ��
function doSearchTeam(form,userid,username,appname,modulename,relatedId){
   window.open("../TeamMemberFind.jsp?form="+form.name+"&userid="+userid.name+"&username="+username.name+"&appname="+appname+"&modulename="+modulename+"&relatedId="+relatedId,'SearchPerson','toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,left=100,top=150,width=600,height=400');
}


//ɾ��
function Delete(url) {
 if(confirm("ȷ��Ҫɾ����")){
   document.location.href=url;
  }
}

function noToolBarWindow(url){ 
 newpage = window.open(url,'newpage','toolbar=no'); 
 newpage.focus(); 
 self.close(); 
} 


//Ϊÿһ����ʾ��ʽ��һ������,Ҳ����Ϊÿһ��IDдһ����ʾ����,һ�����غ���
    //�ص㹤�� 1, �������� 2,�汾�ƻ�11,�������12
   function TASKPROPERTY_D1(){
    	TASK_NAME.style.display="block";
    	//TASK_TARGET.style.display="none";
    	/*
    	MONEY.style.display="none";
    	MONEYUSE.style.display="none";
    	HR_REQUEST.style.display="none";
    	HR_NUM.style.display="none";
    	HR_DESC.style.display="none";    	
    	*/
    	ACCEPT_CRITERION.style.display="none";
    }
    //��ѵ���� 3,���� 6,6sigma 7,ר����׼ 8,�����Ż� 9,CMM 10,
	function TASKPROPERTY_D2(){    
    	TASK_NAME.style.display="block";
    	//TASK_TARGET.style.display="none";  
    	/*  	
    	MONEY.style.display="none";
    	MONEYUSE.style.display="none";
    	HR_REQUEST.style.display="none";
    	HR_NUM.style.display="none";
    	HR_DESC.style.display="none";  
    	*/
    	TASK_CLASS.style.display="none";
    	MONITOR.style.display="none";  	
    	
    	ACCEPT_CRITERION.style.display="none";
    }
    //��ʱ���� 13
    function TASKPROPERTY_D5(){    
    	TASK_NAME.style.display="block";
    	TASK_CLASS.style.display="none";
    	MONITOR.style.display="none";
    	
    	PLAN_BEGIN_TIME.style.display="block";
    	PLAN_END_TIME.style.display="block";  	
    	COMPLETE_STYLE.style.display="none"; 
    	
    	TASK_CONTENT.style.display="block";
    	
    	ACCEPT_CRITERION.style.display="block";
    }
    
 function SELECT_TASKFROM(taskfrom){
     if( taskfrom=="1" ){
     	taskfromsec.style.display="block"; 
	taskfromproject.style.display="none"; 
     }else if(taskfrom=="2"){
        taskfromsec.style.display="none";  
        taskfromproject.style.display="block"; 
     }    
 }
   
//�ж��Ƿ��»س���,������»س���,�򵯳���Աѡ�񴰿�
function form_onkeyup(form,personId,personName)
{ 
	key = window.event.keyCode; 		
	if(key == 13)//�ж��Ƿ��»س���
	{
	    doSearch(form, personId, personName);
	    return false;   
	}else{
	    return key;
	}
}

//�ж��Ƿ��»س���,������»س���,����һ�������
function form_onkeyup2(form,personId,personName,validName)
{ 
		key=window.event.keyCode; 
		if(key==13)//�ж��Ƿ��»س���
		{   
		    //��������
		    doSearch2(form,personId,personName,validName);
		    personName.focus();
		    return false; 
	     } 
}

//�ύ��ʱ,���в�����Ч
function  ini(curForm){
	 len = curForm.elements.length;
		var i=0;
		for (i=0; i<len; i++) {
				curForm.elements[i].disabled=true;
		}
}

//��Ѱ��Ա
function searchEmployee(form,personId,personName){
	key=window.event.keyCode; 
	if(key==13){ //�ж��Ƿ��»س���
		//��������
		doSearchEmployee(form,personId,personName);
		//personName.focus();
		return false; 
	}   
}
