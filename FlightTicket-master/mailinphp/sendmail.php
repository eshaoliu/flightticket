<?php
require_once "email.class.php";
	//******************** ������Ϣ ********************************
	$smtpserver = "smtp.126.com";//SMTP������
	$smtpserverport =25;//SMTP�������˿�
	$smtpusermail = "new2008oh@126.com";//SMTP���������û�����
	$smtpemailto = $_POST['toemail'];//���͸�˭
	$smtpuser = "new2008oh";//SMTP���������û��ʺ�
	$smtppass = "������������";//SMTP���������û�����
	$mailtitle = $_POST['title'];//�ʼ�����
	$mailcontent = "<h1>".$_POST['content']."</h1>";//�ʼ�����
	$mailtype = "HTML";//�ʼ���ʽ��HTML/TXT��,TXTΪ�ı��ʼ�
	//************************ ������Ϣ ****************************
	$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//�������һ��true�Ǳ�ʾʹ�������֤,����ʹ�������֤.
	$smtp->debug = false;//�Ƿ���ʾ���͵ĵ�����Ϣ
	$state = $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype);

	echo "<div style='width:300px; margin:36px auto;'>";
	if($state==""){
		echo "�Բ����ʼ�����ʧ�ܣ�����������д�Ƿ�����";
		echo "<a href='index.html'>��˷���</a>";
		exit();
	}
	echo "��ϲ���ʼ����ͳɹ�����";
	echo "<a href='index.html'>��˷���</a>";
	echo "</div>";
?> 	