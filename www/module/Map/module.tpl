<script type="text/javascript" src='{$Address}js/prototype-packed.js'></script>
<script type="text/javascript" src='{$Address}js/scriptaculous.js'></script>
<script type="text/javascript" src='{$Address}js/tafelTree/Tree-optimized.js'></script>


<script type="text/javascript" language="JavaScript">
var selectedId = null;
var selectedNode = null;
function nodeSelect(branch)
{
	if (selectedNode != null)
		selectedNode.removeClass("nodeSelected");
	
	selectedId = branch.getId();
	selectedNode = branch;
	selectedNode.addClass("nodeSelected");
}
function nodeClick(node)
{
	selectedId = node.getId();
	selectedNode = node;
	goToPageEdit();
}
</script>
<table align="left">
	<tr>
		<td>{tafelTree id="treeView" struct=$Data.Tree}</td>
	</tr>
</table>
	
<script language="JavaScript" type="text/javascript">
var branch = tafelTree_treeView.getBranchById("0");
//branch.select();
//nodeSelect(branch);
TafelTreeManager.disableKeyboardEvents();
</script>