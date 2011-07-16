<?
/**
 * Вывод данных модуля
 */

class SpecialModule extends BMC_BaseModule {

    const RecordsOnPage = 20;

    function DataBind() {

        $this->curPage = intval(Request('npageNum', 0))+ intval(Request('pageNum', 0));
        $newsId = intval(Request('newsId'));

        if(isset($_REQUEST['manufacturers']) && $_REQUEST['manufacturers']!='') {
            $manufacturer=$_REQUEST['manufacturers'];
        }
        if(isset($_REQUEST['specsearch']) && $_REQUEST['specsearch']!='') {
            $specsearch=$_REQUEST['specsearch'];
        }

        $this->BindList(@$manufacturer,@$specsearch);
    }

    public function BindList($manufacturer=NULL,$specsearch=NULL) {

        $count = 0;
        $specDb = new DAL_SpecDb();
        if(isset($manufacturer)) {
            $rows = $specDb->GetPageByManufacturer($manufacturer, $this->curPage, self::RecordsOnPage);
            $this->data['ThisManufacturer']=$manufacturer;
        }
        elseif(isset($specsearch)) {
            $rows = $specDb->GetPageBySearch($specsearch, $this->curPage, self::RecordsOnPage);
            $this->data['SpecSearch']=$specsearch;
        }
        else {
            $rows = $specDb->GetPage($this->curPage, self::RecordsOnPage);
        }

		$startIndex = $this->curPage * self::RecordsOnPage + 1;

        if(isset($rows)) {
            $goodDb=new DAL_GoodsDb();
			$sectionDb=new DAL_SectionsDb();

            foreach($rows as &$row) {
				$fullpath='';
                $row['Good']=$goodDb->GetGood($row['GoodId']);
				$row['Index'] = $startIndex++;
			    $sectionDb->GetSectionFullPath($row['Good']['SectionId'],$fullpath);
				$alias=$goodDb->GetPageAliasByGoodId($row['GoodId']);
                $row['Url']="/".$alias[0]['Alias']."/".$fullpath."good".$row['GoodId']."/";
                $manDb=new DAL_ManufacturersDb();
                $row['ManufacturerName']=$manDb->GetNameById($row['Good']['ManufacturerId']);
                unset($manDb);
                $row['PriceWithDiscount']=$row['Good']['Price']*(1 - $row['Discount']/100);
                if(is_int(BLL_ShoppingCartUtility::FindIndexByGoodId($row['GoodId']))) {

                    $row['AddedToCart']="Товар добавлен в <a href='/cart/'>корзину</a>";
                }
                else {

                    $row['AddedToCart']=null;
                }
            }
            unset($goodDb);
        }
        $manDb=new DAL_ManufacturersDb();
        $manufacturers=$manDb->GetAll();
        $this->data['Manufacturers']=$manufacturers;
        unset($manDb);

        if (0 == count($rows))
            return;

        /*Пейджинг*/
        if(isset($manufacturer)) {
            $all = $specDb->GetCountPageByManufacturer($manufacturer);
        }
        elseif(isset($specsearch)) {
            $all = $specDb->GetCountPageBySearch($specsearch);
        }
        else {
            $all = $specDb->GetCount(); // количество всех новостей
        }
        unset($specDb);

        $p = ceil($all/self::RecordsOnPage); // количество страниц
        $pager = "<div class='pager'>";
        if($p>1) {// если больше одной страницы
            for($i = 0;$i < $p;$i++) {
                if($this->curPage == $i) {// выделение страниц
                    $pager .= "<span>".($i+1)."</span>&nbsp;";
                }else {
                    $url = $this->GetVirtualPath();
                    if(isset($manufacturer)) {
                        $pager .= "<a href='?pageNum=".$i."&manufacturers=$manufacturer'>".($i+1)."</a>&nbsp;";
                    }
                    elseif(isset($specsearch)) {
                        $pager .= "<a href='?pageNum=".$i."&specsearch=$specsearch'>".($i+1)."</a>&nbsp;";
                    }
                    else {
                        $pager .= "<a href='?pageNum=".$i."'>".($i+1)."</a>&nbsp;";
                    }
                }
            }
        }
        $this->data["Pager"] = $pager."<div>";
        /*Пейджинг*/

        $this->data['List'] = $rows;
    }

    /**
     * Обработчик нажатия на клавишу заказа
     */
    function handlerBtnAdd($GoodId=null) {

        if(!isset($_SESSION['userId'])) {
            Redirected("/registration/");
        }
        if (IsValid()) {
            if($GoodId) {
                $goodId=$GoodId;
            }
            elseif(Request("goodId")) {
                $goodId = Request("goodId");
            }
            else {
                return false;
            }

            $count = 1;//intval($this->data['txtOrderCount']);

            if ($count > 0)
                BLL_ShoppingCartUtility::Add($goodId, $count);

            //Redirect("/cart.php");
            //$this->data['BtnAddPostBack'] = true;
            //$this->data['AddedToCart'][$goodId] = "Товары добавлены в <a href='/cart/'>корзину</a>";

        }
    }
}


?>