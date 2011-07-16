<?

/**
  �����-������ ��� Smarty
  @author MeF Dei Corvi
 */
class PHP_Smarty {

    /**
      ������ Smarty
     */
    private static $smarty;

    /**
      ���������� ������ Smarty
      @return Smarty
     */
    public static function GetInstance() {
        if (self::$smarty != null)
            return self::$smarty;

        // ���� ����� Smarty �� ����������, �� ��������� ���
        if (!class_exists("Smarty", false))
            require_once(AppCode . "/PHP/Smarty/Smarty.class.php");

        // ������ ����� ��������� Smarty
        self::$smarty = new Smarty();

        self::SetTheme("Default");
        //self::$smarty->load_filter('pre', 'ajax');

        self::SetOptions(self::$smarty);

        return self::$smarty;
    }

    /*
      ������������� ���������
     */

    protected static function SetOptions($smarty) {
        $config = Array
            (
            "Culture" => Array
                (
                "ShortDateFormat" => "%d.%m.%Y"
            )
        );
        $smarty->assign("Config", $config);
    }

    /**
      ������������� ����(������) �����
      @param string $themeName ��� ����� ����
     */
    public static function SetTheme($themeName) {
//		echo TemplateFolder."/".$themeName."/";
        $dir = TemplateFolder . "/" . $themeName . "/";
        self::$smarty->template_dir = $dir;
        self::$smarty->cache_dir = $dir . "cache/";
        self::$smarty->compile_dir = $dir . "compile/";
    }

}

?>
