<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {tafelTree} function plugin
 *
 * Type:     function
 * Name:     tafelTree
 * Purpose:  make a tafelTree from PHP array structure
 * Version:     0.12
 * @link http://tafel.developpez.com/
 * @author Yann-Ga�l GAUTHERON <abalam -at- aewd -dot- net>
 * Example:
 
 		Note that values are javascript code or strings, if it's strings you must add a JS quote inside de PHP quote like
		$struct = array(
			array(
				"id" => "'root0'"
				,"txt" => "'root 0'"
			)
			,array(
				'id' => "'root1'"
				,'txt' => "'TafelTree JS'"
				,'img' => "'base.gif'"
				,'items' => array(
					array(
						'id' => "'child1'"
						,'txt' => "'L\'objet en question'"
						,'items' => array(
							array(
								'id' => "'child2'"
								,'txt' => "'<span>Ses m�thodes</span>'"
							)
							,array(
								'id' => "'3'"
								,'txt' => "'Ses propri�t�s'"
							)
							,array(
								'id' => "'child4'"
								,'txt' => "'Ses functions utilisateur (genre onclick)'"
								,'onclick' => 'function(branch){alert("le texte de ce noeud est le suivant  =>  \n" + branch.struct.txt);}'
								,'items'  =>  array(
									array(
										'id'  =>  "'blu'"
										,'txt'  =>  "'hop'"
										,'check' => 1
										,'tooltip'  =>  "'Mon joli tooltip'"
									)
								)
							)
						)
					)
				)
			)
		);

 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_tafelTree($params, &$smarty)
{
	$html = "";
	/* Initialize */
   	$attributes = array(
		'generate'=>true,
		'imgBase' => "/js/tafelTree/imgs/",
		'openAtLoad'=>true,
		'cookies'=>true,
		'multiline'=>true,
		'defaultImg'=>"document.png",
//		'onDrop'=>'tafelDrop',
//		'defaultImgSelected'=>"globe.gif",
		'defaultImgOpen'=>"folder-open.png",
		'defaultImgClose'=>"folder.png",
		'defaultImgCloseSelected'=>"folder--exclamation.png",
		'defaultImgOpenSelected'=>"folders.png",
		'rtlMode'=>false,
		'dropALT'=>false,
		'dropCTRL'=>false,
//		'checkboxes'=>false,
//		'checkboxesThreeState'=>false,
		'behaviourDrop'=>"child"
	);

    foreach ($params as $_key=>$_value) {
        switch ($_key) {				
            case 'struct':
			case 'id':
				$$_key = $_value;
                break;
				
			case 'generate':
			case 'imgBase':
			case 'openAtLoad':
			case 'cookies':
			case 'ondrop':
			case 'multiline':
			case 'defaultImg':
			case 'defaultImgSelected':
			case 'defaultImgOpen':
			case 'defaultImgClose':
			case 'defaultImgCloseSelected':
			case 'defaultImgOpenSelected':
			case 'rtlMode':
			case 'dropALT':
			case 'checkboxesThreeState':
			case 'behaviourDrop':
			case 'onclick':
				$attributes[$_key] = (string)$_value;
                break;
				
            default:
                $smarty->trigger_error("[tafelTree] parametre inconnu $_key", E_USER_WARNING);
        }
    }

    if (empty($id) || empty($struct)) {
        $smarty->trigger_error("[tafelTree] attribut 'id' et 'struct' requis");
        return false;
    }

	$html .= '<div id="'.$id.'"></div>';
	$html .= '<script type="text/javascript">/*<![CDATA[*/';

        $html .="";
	
	function to_json($array) {
		$data = array();
		foreach ($array as $k => $i) {
			if (is_string($i) && ($k != "onDrop" || $k != "onclick")) $i = "'".$i."'";
			else if (is_bool($i)) $i = $i ? "true" : "false";
			else if (is_array($i)) {
				$i = to_json($i);
			}
			if (isset($array[0])) { /* Index num�riques */
				$data[] = $i;
			} else { /* Index associatifs */
				$data[] = "'".$k."':".$i."";
			}
		}
		$s = implode(",",$data);
		if (isset($array[0])) { /* Index num�riques */
			return "[".$s."]";
		} else { /* Index associatifs */
			return "{".$s."}";
		}
	}

	$html .= 'var tafelTree_'.$id.' = new TafelTree("'.$id.'", '.to_json($struct).', '.to_json($attributes).");";	
	$html .= '/*]]>*/</script>';
	
	return $html;
}
?>