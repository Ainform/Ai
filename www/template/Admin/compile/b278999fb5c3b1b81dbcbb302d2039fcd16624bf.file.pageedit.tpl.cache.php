<?php /* Smarty version Smarty-3.0.8, created on 2011-07-12 17:05:33
         compiled from "V:/home/buket_debug/www/admin/module/PagesList/pageedit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:133274e1c2a7ddd81f3-73760538%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b278999fb5c3b1b81dbcbb302d2039fcd16624bf' => 
    array (
      0 => 'V:/home/buket_debug/www/admin/module/PagesList/pageedit.tpl',
      1 => 1310467992,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '133274e1c2a7ddd81f3-73760538',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_TextBox')) include 'V:\home\buket_debug\www\App_Code\PHP\Smarty\plugins\function.TextBox.php';
if (!is_callable('smarty_function_Validator')) include 'V:\home\buket_debug\www\App_Code\PHP\Smarty\plugins\function.Validator.php';
if (!is_callable('smarty_function_CheckBox')) include 'V:\home\buket_debug\www\App_Code\PHP\Smarty\plugins\function.CheckBox.php';
if (!is_callable('smarty_block_ajax_update')) include 'V:\home\buket_debug\www\App_Code\PHP\Smarty\plugins\block.ajax_update.php';
if (!is_callable('smarty_function_DropDownList')) include 'V:\home\buket_debug\www\App_Code\PHP\Smarty\plugins\function.DropDownList.php';
?><script language="javascript" type="text/javascript">
    function addModule()
    {
        var modules = document.getElementById("drdModulesList");
        var module = modules.options[modules.selectedIndex].value;

        new Ajax.Request("<?php echo $_smarty_tpl->getVariable('PageAddress')->value;?>
?id=<?php echo $_GET['id'];?>
",
        {
            postBody: 'handlerBtnAddModule=handlerBtnAddModule&drdModulesList='+module+'&moduleId=<?php echo $_smarty_tpl->getVariable('Module')->value->moduleId;?>
',
            onSuccess: function(transport, json)
            {
                //			alert(1);
            }
        });


        return false;
    }
    function deleteModule()
    {
        return confirm('Удалить модуль на странице? \n Если вы нажмете OK то модуль востановить будет НЕВОЗМОЖНО');
    }
    function editModule(moduleId)
    {
        if (!moduleId)
            return false;

        document.location.href = "/admin/modules/" + moduleId + ".php";
        return false;
    }
    function genAlias(elem)
    {
        if ($F("txtAlias") == "")
        {
            trans = new Translit();
            $("txtAlias").value = trans.UrlTranslit($F(elem), false);
        }
    }
    function countChars(elem, span)
    {
        $(span).innerHTML = "<b>"+elem.value.length+"</b>";
    }
</script>
<table class="admin_table">
    <tr>
        <td style="width:150px;">Заголовок</td>
        <td class="page_header">
		<?php echo smarty_function_TextBox(array('id'=>"txtName",'maxlength'=>"100",'class'=>"text-input small-input",'onblur'=>"genAlias(this)"),$_smarty_tpl);?>

		<?php echo smarty_function_Validator(array('for'=>"txtName",'rule'=>"NotNull",'message'=>"Введите заголовок страницы"),$_smarty_tpl);?>

        </td>
    </tr>
    <tr>
        <td>Title</td>
        <td>
		<?php echo smarty_function_TextBox(array('id'=>"txtTitle",'maxlength'=>"100",'class'=>"text-input small-input",'onkey'=>"countChars(this, 'titleStr')"),$_smarty_tpl);?>

        </td>
    </tr>
    <tr>
        <td>Keywords</td>
        <td>
		<?php echo smarty_function_TextBox(array('id'=>"txtKeywords",'maxlength'=>"100",'class'=>"text-input small-input",'onkey'=>"countChars(this, 'keywordStr')"),$_smarty_tpl);?>

        </td>
    </tr>
    <tr>
        <td>Description</td>
        <td>
                <?php echo smarty_function_TextBox(array('id'=>"txtDescription",'maxlength'=>"250",'class'=>"text-input small-input",'onkey'=>"countChars(this, 'descriptionStr')"),$_smarty_tpl);?>

        </td>
    </tr>
    <tr>
        <td>Псевдоним страницы<br />
            <small>Только латинские буквы</small>
        </td>
        <td>
		<?php echo smarty_function_TextBox(array('id'=>"txtAlias",'maxlength'=>"100",'class'=>"text-input small-input"),$_smarty_tpl);?>

		<?php echo smarty_function_Validator(array('for'=>"txtAlias",'rule'=>"NotNull Latin",'message'=>"Введите псевдоним страницы. Псевдоним страницы может состоять только из букв латинского алфавита"),$_smarty_tpl);?>

        </td>
    </tr>
</table>
<table class="admin_table">
    <tr>
        <td style="border-top:0px">
				<?php echo smarty_function_CheckBox(array('id'=>"chkVisible",'value'=>"Отображать страницу в меню"),$_smarty_tpl);?>

				<?php if (!$_smarty_tpl->getVariable('Data')->value['HasParent']){?>
				<?php }?>
        </td>
    </tr>
</table>
	<?php if ($_smarty_tpl->getVariable('Data')->value['NewPage']!=true){?>

<?php $_smarty_tpl->smarty->_tag_stack[] = array('ajax_update', array()); $_block_repeat=true; smarty_block_ajax_update(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

<table class="module_add_table">
    <thead><tr><th colspan="5">Модули страницы</th></tr></thead>
    <tr>
        <td colspan="5">Тип модуля&nbsp;
            <?php echo smarty_function_DropDownList(array('id'=>"drdModulesList",'class'=>"dropdownlist",'values'=>$_smarty_tpl->getVariable('Data')->value['Modules']),$_smarty_tpl);?>

            &nbsp;<input type="submit" class="button" name="handlerBtnAddModule" title="Добавить модуль на страницу" value="Добавить модуль" />
        </td>
    </tr>
	<?php  $_smarty_tpl->tpl_vars['Item'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('Data')->value['PageModules']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['Item']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['Item']->iteration=0;
 $_smarty_tpl->tpl_vars['Item']->index=-1;
if ($_smarty_tpl->tpl_vars['Item']->total > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['Item']->key => $_smarty_tpl->tpl_vars['Item']->value){
 $_smarty_tpl->tpl_vars['Item']->iteration++;
 $_smarty_tpl->tpl_vars['Item']->index++;
 $_smarty_tpl->tpl_vars['Item']->first = $_smarty_tpl->tpl_vars['Item']->index === 0;
 $_smarty_tpl->tpl_vars['Item']->last = $_smarty_tpl->tpl_vars['Item']->iteration === $_smarty_tpl->tpl_vars['Item']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['modulesList']['first'] = $_smarty_tpl->tpl_vars['Item']->first;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['modulesList']['last'] = $_smarty_tpl->tpl_vars['Item']->last;
?>
    <tr>
        <td width="16px" align="center">
            <input type="image" name="handlerBtnEdit" id="btnEdit" src="/img/admin/op_edit.gif" alt="Редактировать модуль" title="Редактировать модуль" onclick="return editModule(<?php echo $_smarty_tpl->tpl_vars['Item']->value['ModuleId'];?>
)" />
        </td>
        <td width="16px" align="center">
					<?php if (!$_smarty_tpl->getVariable('smarty')->value['foreach']['modulesList']['first']){?>
            <input type="image" name="handlerModuleBtnUp:<?php echo $_smarty_tpl->tpl_vars['Item']->value['ModuleId'];?>
" id="btnUp" src="<?php echo $_smarty_tpl->getVariable('Address')->value;?>
img/admin/arrow_up_16.png" alt="Поднять модуль" title="Поднять модуль" />
					<?php }?>
        </td>
        <td width="16px" align="center">
					<?php if (!$_smarty_tpl->getVariable('smarty')->value['foreach']['modulesList']['last']){?>
            <input type="image" name="handlerModuleBtnDown:<?php echo $_smarty_tpl->tpl_vars['Item']->value['ModuleId'];?>
" id="btnDown" src="<?php echo $_smarty_tpl->getVariable('Address')->value;?>
img/admin/arrow_down_16.png" alt="Опустить модуль" title="Опустить модуль"/>
					<?php }?>
        </td>
        <td><?php echo $_smarty_tpl->tpl_vars['Item']->value['Title'];?>
</td>
        <td width="16px" align="center">
            <input type="image" name="handlerBtnModuleDelete:<?php echo $_smarty_tpl->tpl_vars['Item']->value['ModuleId'];?>
" id="btnDelete" src="/img/admin/close_16.png" alt="Удалить модуль" onclick="return deleteModule();" title="Удалить модуль" />
        </td>
    </tr>
	<?php }} else { ?>
    <tr><td>Модулей на данной странице пока нет</td></tr>
	<?php } ?>
</table>
	<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_ajax_update(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>


<br />
		<?php }?>
<p align="center">
		<?php if ($_smarty_tpl->getVariable('Data')->value['NewPage']==true){?>
    <input type="submit" name="handlerBtnSave" value="Добавить" title="Добавить страницу" class="button"></input>
		<?php }else{ ?>
    <input type="submit" name="handlerBtnSave" value="Сохранить" title="Сохранить изменения" class="button"></input>
		<?php }?>
    <input type="submit" name="handlerBtnCancel" value="Отмена" title="Отменить изменения" class="button"></input>
</p>