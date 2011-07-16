<?php
set_time_limit(0);
ob_implicit_flush(1);
/**
 * SearchEdit class
 * Упралвение индексацией сайта
 * 
 * @author Informix
 * @copyright (c) by Informix
 */

class SearchModuleEdit extends BMC_BaseModule
{
	
	var $Urls= array("");
	
	/**
	 * Конструктор, задает параметры
	 *
	 * @return void
	 */
	function __construct($moduleId)
	{
		parent::__construct($moduleId);
	}

	/**
	 * Количество записей на страницу
	 */
	const RecordsOnPage = 10;

	/**
	 * Номер текущей страницы
	 *
	 * @var int
	 */
//	private $curPage;

	var $Model;
	
	/**
	 * Функция для создания html-кода модуля
	 * 
	 * @return void
	 */

	function DataBind()
	{
		$this->Model = new DAL_SearchDb();
		$action = CharOnly(Request("act","List"));
		if($action == "List"){
			$result = $this->query("SELECT * FROM searchindex order by `Date` DESC;");
			if(isset($result[0])){
				$this->data["Info"] = $result[0];
			}else{
				$this->data["Info"] = array("Date"=>time());
			}
		}else{
			/*Реинтдексация сайта*/
			$this->query("TRUNCATE SearchIndex;");
			//$this->GetUrls();
			$this->altUrls();
			$this->Indexer();	
		}
		
	}
	
	private function GetUrls(){
		
		//Debug(BMC_SiteMap::GetSitePages());
		
		include("../App_Code/PHP/Xmller.php");
		$xmler = new Xmller;
		$res = $xmler->load("../config/SiteMap.xml");
		//Debug($res["Sitemap"]);
		
		
		foreach ($res["Sitemap"]["SiteMapNode"] as $i => $Data){
			$pageId = mt_rand(0,999);
			//Debug($Data);
			$this->Urls[$pageId]["Url"] = (!empty($Data["alias"]))?"/".$Data["alias"]."/":"";
			$this->Urls[$pageId]["Title"] = (!empty($Data["title"]))?$Data["title"]:"";
			if(!empty($Data["SiteMapNode"])){
				//Debug($Data["SiteMapNode"],false);
				//$this->Childs($Data["SiteMapNode"],$this->Urls[$pageId]["Url"]);
			}
		}  
        
	}
	
	private function Indexer(){
		//Debug($this->Urls);
		foreach ($this->Urls as $Data){
			$Data["Url"] = (!empty($Data["Url"]))?$Data["Url"]:"/";
			$server = $_SERVER['SERVER_NAME'] . ":" . ($_SERVER['SERVER_PORT'] + 0) . "/";

			if(!preg_match("!&|\?!is",$Data["Url"]))$Data["Url"] .= "/";

			//if(preg_match("!&|\?!is",$Data["Url"]))continue;
			
			$url = "http://". str_replace("//","/",$server . $Data["Url"]);
			//Debug($url,false);continue;
			if(preg_match("!Search!is",$url))continue;
			//Echo $url . "<br>";continue;
			$replace = array(
			"'"=>"\"",
			"\n"=>"",
			"\r"=>"",
			"  "=>" ",
			"\t"=>"",
			);
			
			//Debug($url);
			$text = @file_get_contents($url);

			//Debug($text);
			if(preg_match("!<\!\-\- Indexer \-\->(.*?)<\!\-\- /Indexer \-\->!is",$text,$result)){
				//Debug($result);
				$body = str_replace(array_keys($replace),array_values($replace),$result[1]);
				$body = preg_replace("!<script([^>]+|)>.*?</script>!isu","",$body);
				$arraySave = array(
				"Date" => date("Y-m-d"),
				"ModuleId" => $this->moduleId,
				"Title" => @$Data["Title"],
				"Url" => $url,
				"Body" => $body
				);
				//var_dump($this->Model);
				$this->Model->SaveIndex($arraySave);
				@$this->data["Result"]++;
				//Debug($arraySave,false);
				unset($result);
				unset($arraySave);
			}
			
/*			$newsDb = new DAL_NewsDb();
			$news = $newsDb->GetAllNews();
			unset($newsDb);

			foreach ($news as $item)
				$this->AddSearchContent($item["text"], $item["title"], SiteUrl."?newsId=".$item["id"]);*/
		}
	}
	
	function AddSearchContent($body, $title, $url)
	{
		$arraySave = array(
		"Date" => date("Y-m-d"),
		"ModuleId" => $this->moduleId,
		"Title" => $title,
		"Url" => $url,
		"Body" => $body
		);

		$this->Model->SaveIndex($arraySave);
		@$this->data["Result"]++;
	}
	
	private function altUrls(){
		$pages = BMC_SiteMap::GetSitePages();
		
		//Debug($pages);
		
		foreach($pages as $page)
		{
			$pageId = mt_rand(0,9999);
			$this->Urls[$pageId]["Url"] = str_replace("/admin","",$page->Url);	
			$this->Urls[$pageId]["Title"] = $page->Title;
			$this->BindChild($page);
		}
        
        $goodsDb = new DAL_GoodsDb();
        $AllGoods=$goodsDb->query("SELECT GoodId,SectionId,Title FROM goods");
       // Debug($AllGoods);
        foreach($AllGoods as $good){
            $pageId = mt_rand(0,999);
             $this->Urls[$pageId]["Url"] = "/produktsija/".$good['SectionId']."/good".$good['GoodId']."/";
             $this->Urls[$pageId]["Title"] = $good['Title'];
        }
		
//		echo count($this->Urls);
	//	Debug($this->Urls);
	}
	
	function BindChild($page){
		$tree = array();
		
		//if ($page->HasChild())
		if (isset($page->Childs) && count($page->Childs))
		{
			foreach ($page->Childs as $child)
			{
				$pageId = mt_rand(0,9999);
				$this->Urls[$pageId]["Url"] = str_replace("/admin","",$child->Url);		
				$this->Urls[$pageId]["Title"] = $child->Title;
				$this->BindChild($child);
			}
		}
	}
			
	
	
	private function Childs($Data = array(),$alias = null){
		//Debug($Data,false);
		if(!empty($Data) and is_array($Data)){
			foreach ($Data as $i=>$Child){
				$pageId = mt_rand(0,999);
				$this->Urls[$pageId]["Url"] = (!empty($Child["alias"]))?"$alias".$Child["alias"]:"";
				$this->Urls[$pageId]["Title"] = (!empty($Child["title"]))?$Child["title"]:"";		
				
				if(!empty($Child["SiteMapNode"])){
					$pageId = mt_rand(0,999);
					$this->Childs($Child["SiteMapNode"],$alias);
				}
			}
		}
	}
	
	
	
	
	private function query($query = null){
		return $this->Model->sql($query);
	}
	
	
	
}
	
?>