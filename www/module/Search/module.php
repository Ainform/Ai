<?php
	/**
	 * SearchModule class
	 * Класс для поиска на сайте
	 *
	 * @author Informix
	 * @version 1.0
	 * @copyright (c) by Informix
	 */


	class SearchModule extends BMC_BaseModule {

		var $Model;
		var $Urls = array();

		var $results = "";

		const RecordsOnPage = 10;

		function DataBind(){
			$this->Model = new DAL_SearchDb();

			//$this->GetUrls();$this->Indexer();Debug("stop");//Индексатор
			$this->searcher();

			$smarty = PHP_Smarty::GetInstance();
			//$smarty->assign("ShowTitle", false);
			$smarty->assign("Query", preg_replace("![^a-z0-9а-я\s]!isu", "",Request("q",null)));

			$this->PageTitle = "Результаты поиска";
		}

		function tolower($content)
		{
			$content = strtr($content, "АБВГДЕЁЖЗИЙКЛМНОРПСТУФХЦЧШЩЪЬЫЭЮЯ",  "абвгдеёжзийклмнорпстуфхцчшщъьыэюя");
			return $content;
		}

		private function searcher(){
			//exit;
			$query = preg_replace("![^a-z0-9а-я\s]!isu", "",Request("q",null));
			//$query = Request("q",null);
			$page = intval(Request("pageNum",0)) * self::RecordsOnPage;
			if(!empty($query)){
				//$sql = "SELECT * FROM SearchIndex WHERE Title LIKE '%$query%' and Body LIKE '%$query%' limit $page,10";

				//$sql = "SELECT Id, ModuleId, Date, Title, Url, REPLACE(lower(Body), lower('$query'), '\{\{$query</b>\}\}') as Body FROM SearchIndex WHERE";
				$sql = "SELECT Id, ModuleId, Date, Title, Url, SUBSTRING(
				REPLACE(lower(Body),
				lower('$query'),
				'\{\{$query\}\}'),
				POSITION(lower('$query') in BODY) - 100,POSITION(lower('$query') in BODY) + 200) as Body FROM";

				$where = " searchindex WHERE";

				$words = explode(" ",$query);

				foreach ($words as $i=>$word)
				{
					$word = trim($word);
					$wordLen = strlen($word);
					if($wordLen>5){
						$len = -1;
					}elseif ($wordLen>6){
						$len = -2;
					}

					//$word = substr_replace($word,str_repeat("",abs($len)),$len);

					$end = ((count($words)-1) == $i) ? "" : " OR ";
					$where .= " Body LIKE '%$word%'";
					$where .= " or Title LIKE '%$word%'$end";
				}

				$all = "SELECT count(*) as cnt FROM $where";
				$sql .= "$where limit $page,".self::RecordsOnPage;

				$result = $this->query($sql);

				$query = preg_replace("![^a-z0-9а-я\s]!isu", "",Request("q",null));


				if(!isset($_SESSION['userId'])){
					//узнаём какие разделы не должен видеть пользователь, еслин он не авторизован
					$pages = BMC_SiteMap::GetPages();

					foreach($pages as $page){
						if($page->Horizontal){
							$AccessDenied[]=$page->Alias;
						};
					}
				}

				$denycounter =0;
				foreach ($result as $key=>&$item)
				{

					if(!isset($_SESSION['userId'])){
						foreach($AccessDenied as $deny){

							if(strpos($item['Url'],$deny)){
								unset($result[$key]);
								$denycounter++;
								continue(2);
							}
						}
					}
					$item["Body"] = str_replace(">", " >", str_replace("<", " <", $item["Body"]));

					$item["Body"] = strip_tags($item["Body"]);

					$item["Title"] = html_entity_decode(html_entity_decode($item["Title"],ENT_QUOTES,'UTF-8'));//пипец, друзья

					if (strpos($item["Body"], ">"))
						$item["Body"] = substr($item["Body"], strpos($item["Body"], ">") + 1);

					if (strpos($item["Body"], $query) !== false)
					{
						$item["Body"] = str_replace("{{", "<b class='search_res'>", $item["Body"]);
						$item["Body"] = str_replace("}}", "</b>", $item["Body"]);

						if (strpos($item["Body"], ">") && strpos($item["Body"], "<") === false)
							$item["Body"] = substr($item["Body"], strpos($item["Body"], ">") + 1);
					}
					else
						$item["Body"] = "";
				}

				$this->AddOrderToRows(&$result, $page);

				$all = $this->query($all);

				$this->data["SearchResult"] = $result;
				$this->data["SearchQuery"] = $query;
				$this->data["SearchAll"] = (!empty($all[0]["cnt"])) ? $all[0]["cnt"] : 0;
				//отнимаем те результаты, которые показывать не нужно
				$this->data["SearchAll"]=$this->data["SearchAll"]-$denycounter;
				$page = intval(Request("pageNum",0));
				$this->data["Pager"] = $this->GetPager($this->moduleId,$page,self::RecordsOnPage,$this->data["SearchAll"],$query);
				$this->data["PagerTitle"] = strpos($this->data["Pager"], "1") === false ? "" : "Страницы:&nbsp;";
			}
		}

		private function Indexer(){
			foreach ($this->Urls as $Data){
				$Data["Url"] = (!empty($Data["Url"]))?$Data["Url"]:"/";
				$url = "http://cms:81".$Data["Url"];
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
					"Title" => $Data["Title"],
					"Url" => $url,
					"Body" => $body
					);
					$this->Model->SaveIndex($arraySave);
					//Debug($arraySave,false);
					unset($result);
					unset($arraySave);
				}
			}
		}

		private function GetUrls(){
			include("App_Code/PHP/Xmller.php");
			$xmler = new Xmller;
			$res = $xmler->load("config/SiteMap.xml");

			foreach ($res["Sitemap"]["SiteMapNode"] as $i => $Data){
				$pageId = mt_rand(0,999);
				//Debug($Data);
				$this->Urls[$pageId]["Url"] = (!empty($Data["alias"]))?"/".$Data["alias"]."/":"";
				$this->Urls[$pageId]["Title"] = (!empty($Data["title"]))?$Data["title"]:"";
				if(!empty($Data["SiteMapNode"])){
					//Debug($Data["SiteMapNode"],false);
					$this->Childs($Data["SiteMapNode"],$this->Urls[$pageId]["Url"]);
				}
			}
		}

		public function GetPager($moduleId, $page, $recordsOnPage,$allNews,$query){
			$p = ceil($allNews/$recordsOnPage); // количество страниц
			$pager = "<div class='pager'>";
			if($p>1){// если больше одной страницы
				for($i = 0;$i < $p;$i++){
					if($page == $i){// выделение страниц
						$pager .= "<span>".($i+1)."</span>&nbsp;";
					}else{
						//$url = $this->GetVirtualPath();
						$pager .= "<a href='?q=$query&pageNum=".$i."'>".($i+1)."</a>&nbsp;";
					}
				}
			}
			return $pager."<div>";
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

		private function OldGetUrls(){

			$return = array();
			$pages = BMC_SiteMap::GetSitePages(true);

			//Debug(reverse($pages));

			foreach ($pages as $page){
				$alias = $this->GetAlias($page->PageId);
				//$alias = $page->Alias;
				//Debug($alias,false);
				//$pageId = (!empty($page->PageId))?"page_".$page->PageId:"module_".$page->ModulId;
				//$pageId = $page->PageId;
				$pageId = mt_rand(0,999);
				/*Создание данных для интекса*/
				$this->Urls[$pageId]["Url"] = $alias;
				$this->Urls[$pageId]["Title"] = (!empty($page->Title))?$page->Title:"";
				$this->BindChild($page,$alias);
			}
			Debug($this->Urls);
		}




		function BindChild($page,$alias)
		{
			$tree = array();
			$main = $alias;
			if ($page->HasChild())
				foreach ($page->Childs as $child)
				{
					$alias = $alias . "/" . $child->alias;
					//Debug($alias);
					$pageId = mt_rand(0,999);
					$this->Urls[$pageId]["Url"] = $alias;
					$this->Urls[$pageId]["Title"] = (!empty($child->Title))?$child->Title:"";
					if ($child->IsInnerModule)continue;

					if (isset($child->PageId)){
						$this->Urls[$pageId]["Url"] = $alias;
						$this->Urls[$pageId]["Title"] = (!empty($child->Title))?$child->Title:"";
						$this->BindChild($child,$main);
						unset($alias);
					}


			}
			return $tree;
		}

		private function GetAlias($PageId = null){
			$page = new DAL_PagesDb();
			$result = $page->GetPage($PageId);
			return (empty($result["Alias"]))?"/":"/".$result["Alias"];
		}

		private function query($query = null){
			return $this->Model->sql($query);
		}

	}

?>