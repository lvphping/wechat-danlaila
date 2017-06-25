<?php
	use phpWeChat\Member;

	!defined('IN_APP') && exit('Access Denied!');
	
	switch($action)
	{
		case 'login': //2016/6/22
			if(isset($redirect))
			{
				set_cookie('redirect',$redirect);
			}
			else
			{
				set_cookie('redirect',U('weshop','i'));
			}
			
			if(isset($dosubmit))
			{
				$telephone=trim($telephone);
				$password=trim($password);

				if(!is_telephone($telephone))
				{
					exit('telephone');
				}

				$mem=Member::getUserByTelephone($telephone);
				if(!$mem)
				{
					exit('telephone');
				}

				if($mem['userpwd']!=md5($password))
				{
					exit('password');
				}

				set_cookie('userid',$mem['userid']);
				if(!$mem['openid'] && $_SESSION['openid'])
				{
					Member::memUpdate($mem['userid'],array('openid'=>$_SESSION['openid']));
				}
				exit('success');
			}
			break;
		case 'register': //2016/6/22
			if(isset($redirect))
			{
				set_cookie('redirect',$redirect);
			}
			
			if(isset($dosubmit) && $dosubmit)
			{
				if(!trim($telephone) || !is_telephone($telephone))
				{
					exit('telephone');
				}

				if(strlen($smscode)!=4 || $_SESSION['smscode']!=trim($smscode))
				{
					exit('smscode');
				}

				if(strlen($userpwd)<6)
				{
					exit('userpwd');
				}

				if($userpwd!=$reuserpwd)
				{
					exit('reuserpwd');
				}

				$info=array();
				$info['username']=$telephone;
				$info['telephone']=$telephone;
				$info['userpwd']=$userpwd;
				$info['openid']=$_SESSION['openid'];
				
				$_userid=Member::register($info);

				if($_userid>0)
				{
					set_cookie('userid',$_userid);
					
					exit('success');
				}
				else
				{
					echo($_userid);exit();
				}
			}
			break;
		case 'getpwd': //2016/6/22
			if(isset($dosubmit) && $dosubmit)
			{
				if(!trim($telephone) || !is_telephone($telephone))
				{
					exit('telephone');
				}

				if(strlen($smscode)!=4 || $_SESSION['smscode']!=trim($smscode))
				{
					exit('smscode');
				}

				if(strlen($userpwd)<6)
				{
					exit('userpwd');
				}

				if($userpwd!=$reuserpwd)
				{
					exit('reuserpwd');
				}

				$info=array();
				$info['username']=$telephone;
				$info['telephone']=$telephone;
				$info['userpwd']=$userpwd;
				$info['openid']=$_SESSION['openid'];
				
				$_userid=Member::getUserByTelephone($telephone,'userid');

				if($_userid)
				{
					Member::resetPassword($_userid,$userpwd);
					exit('success');
				}
				else
				{
					echo(-1);
					exit();
				}
			}
			break;
		default:
			break;
	}
?>