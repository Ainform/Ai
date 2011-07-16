<?php
/**
 *
 * @copyright (c) by VisualDesign
 *
 */

class SpecialModuleEdit extends BMC_BaseModule {


    /**
     * Количество новостей на страницу
     */
    const RecordsOnPage = 2;

    /**
     * Конструктор, задает параметры
     *
     * @return void
     */

    function __construct($moduleId) {
        parent::__construct($moduleId);
    }


    /**
     * Номер текущей страницы
     *
     * @var int
     */
    public $curPage;

    private $Model;


    /**
     * Функция для создания html-кода модуля
     *
     * @return void
     */
    function DataBind() {

        $this->Model = new DAL_SpecDb();
        $this->curPage = Request('pageNum', 0);
        $Id = Request('Id');

        if ($Id == null) {
            $this->BindList();
        }
        else {
            $this->BindOne($Id);
        }


    }

    public function BindList() {

        $count = 0;
        $specDb = new DAL_SpecDb();
        $rows = $specDb->GetAdminPage($this->curPage, self::RecordsOnPage);
        
        if(isset($rows)){
        $goodDb=new DAL_GoodsDb();
        foreach($rows as &$row){
            $row['Name']=$goodDb->GetNameById($row['GoodId']);
        }
        unset($goodDb);
        }

        if (0 == count($rows))
            return;

        /*Пейджинг*/
        $all = $specDb->GetAdminCount(); // количество всех

        unset($specDb);
var_dump($all);
        $p = ceil($all/self::RecordsOnPage); // количество страниц
        $pager = "<div class='pager'>";
        if($p>1) {// если больше одной страницы
            for($i = 0;$i < $p;$i++) {
                if($this->curPage == $i) {// выделение страниц
                    $pager .= "<span>".($i+1)."</span>&nbsp;";
                }else {
                    $url = $this->GetVirtualPath();
                    $pager .= "<a href='?pageNum=".$i."'>".($i+1)."</a>&nbsp;";
                }
            }

        }
        
        $this->data["Pager"] = $pager."<div>";
        /*Пейджинг*/

        $this->data['List'] = $rows;
    }

    public function BindOne($Id) {
        $specDb = new DAL_SpecDb();
        $spec = $specDb->GetSpec($Id);
        unset($specDb);

        if (!isset($spec))
            return;

        $goodDb=new DAL_GoodsDb();
        $spec['Name']=$goodDb->GetNameById($Id);
        unset($goodDb);

        $this->data = $spec;

        $this->template = "specEdit.tpl";
    }

    function handlerBtnSave() {

        if (!IsValid()) return;

        $specId = Request('Id');

        if (!empty($specId)) {
            
            $Row = Array();
            $Row['GoodId'] = $specId;
            $Row['DateStart'] = $this->data['DateStart']!=''?date("Y-m-d H:i:s",strtotime($this->data['DateStart'])):'';
            $Row['DateEnd'] = $this->data['DateEnd']!=''?date("Y-m-d H:i:s",strtotime($this->data['DateEnd'])):'';
            $Row['Discount'] = $this->data['Discount'];
            $Row['Path'] = $this->data['Path'];

            $specDb = new DAL_SpecDb();
            $specDb->UpdateSpec($Row);
            unset($specDb);
            Header("Location: ".$this->Url);

        }
    }

    function handlerBtnCancel() {
        RedirectToModuleEdit($this->moduleId);
        die();
    }

    function handlerBtnDel($Id) {
        $specDb = new DAL_SpecDb();
        $specDb->DeleteSpec($id);
        unset($newsDb);

        BMC_SiteMap::Update();

        $this->BindList();
    }

}
?>