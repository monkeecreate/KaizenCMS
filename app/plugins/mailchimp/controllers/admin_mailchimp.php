<?php
class admin_mailchimp extends adminController {
	function __construct() {
		parent::__construct("mailchimp");
		
		$this->menuPermission("mailchimp");
		
		$this->tplAssign("aAccount", $this->loadMailChimp()->getAccountDetails());
	}
	
	### Campaigns #################################
	function campaigns_index() {
		// Clear saved form info
		$_SESSION["admin"]["admin_mailchimp"] = null;
		
		$oMailChimp = $this->loadMailChimp();
		
		$this->tplAssign("aCampaigns", $oMailChimp->campaigns(null, 0, 1000));
		$this->tplDisplay("admin/campaigns/index.php");
	}
	
	### Lists #####################################
	function lists_index() {
		// Clear saved form info
		$_SESSION["admin"]["admin_mailchimp"] = null;
		
		$oMailChimp = $this->loadMailChimp();
		
		$this->tplAssign("aLists", $oMailChimp->lists());
		$this->tplDisplay("admin/lists/index.php");
	}
	function lists_show() {
		
	}
	function lists_members() {
		$oMailChimp = $this->loadMailChimp();
		
		$this->tplAssign("aListId", $this->urlVars->dynamic["id"]);
		$this->tplAssign("aMembers", $oMailChimp->listMembers($this->urlVars->dynamic["id"], "subscribed", null, 0, 10));
		$this->tplDisplay("admin/lists/members.php");
	}
	function lists_load_members() {
		$oMailChimp = $this->loadMailChimp();
		
		$sPage = $_GET['iDisplayStart'] / $_GET['iDisplayLength'];
		$aMembers = $oMailChimp->listMembers($this->urlVars->dynamic["id"], "subscribed", null, $sPage, $_GET['iDisplayLength']);
		
		$sOutput = '{';
		$sOutput .= '"sEcho": '.intval($_GET['sEcho']).', ';
		$sOutput .= '"iTotalRecords": '.$aMembers["total"].', ';
		$sOutput .= '"iTotalDisplayRecords": '.$aMembers["total"].', ';
		$sOutput .= '"aaData": [ ';
		foreach($aMembers["data"] as $aMembers) {
			$sOutput .= '[';
			$sOutput .= '"'.$aMembers["email"].'"';
			$sOutput .= ',"<a href=\'/admin/mailchimp/lists/'.$this->urlVars->dynamic["id"].'/member/'.urlencode($aMembers["email"]).'/\' title=\'Edit Member\'><img src=\'/images/admin/icons/pencil.png\' alt=\'edit icon\'></a>"';
			$sOutput .= '],';
		}
		$sOutput = substr_replace( $sOutput, "", -1 );
		$sOutput .= '] }';

		echo $sOutput;
	}
	function lists_member() {
		$oMailChimp = $this->loadMailChimp();
		
		$aMember = $oMailChimp->listMemberInfo($this->urlVars->dynamic["id"], array(urldecode($this->urlVars->dynamic["email"])));
		
		$aLists = $oMailChimp->lists(array("list_id" => implode(",", array_keys($aMember["data"][0]["lists"])).",".$this->urlVars->dynamic["id"], "subscribed"));
		$aMember["data"][0]["lists"] = $aLists["data"];
		
		$aListFields = $oMailChimp->listMergeVars($this->urlVars->dynamic["id"]);
		
		$this->tplAssign("aListId", $this->urlVars->dynamic["id"]);
		$this->tplAssign("aMember", $aMember["data"][0]);
		$this->tplAssign("aListFields", $aListFields);
		$this->tplDisplay("admin/lists/member.php");
	}
	function lists_member_s() {	
		$oMailChimp = $this->loadMailChimp();
			
		$aMerges = array("FNAME" => $_POST["fname"], "LNAME" => $_POST["lname"], "phone" => $_POST["phone"], "address" => $_POST["address"], "city" => $_POST["city"], "state" => $_POST["state"], "zip" => $_POST["zip"]);
		$oMailChimp->listUpdateMember($this->urlVars->dynamic["id"], $_POST["email"], $aMerges, 'html', false);
		
		if($oMailChimp->errorCode) {
			$this->forward("/admin/mailchimp/lists/".$this->urlVars->dynamic["id"]."/members/?error=".urlencode("Member was not saved, please try again. Error: ".$oMailChimp->errorMessage));
		} else {
			$this->forward("/admin/mailchimp/lists/".$this->urlVars->dynamic["id"]."/members/?info=".urlencode("Changes successfully saved!"));
		}
	}
}